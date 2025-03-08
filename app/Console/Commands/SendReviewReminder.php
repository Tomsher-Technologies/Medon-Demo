<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Mail\ReviewReminderMail;
use Illuminate\Support\Facades\Mail;

class SendReviewReminder extends Command
{
    protected $signature = 'send:review-reminder';
    protected $description = 'Send product review reminders to customers 7 days after delivery';

    public function handle()
    {
        // Fetch order details where delivery was 7 days ago
        $orderDetails = OrderDetail::with(['order.user', 'product'])
                ->whereDate('delivery_date', now()->subDays(7))
                ->get();

        // Group products to send one email per user per order
        $grouped = $orderDetails->groupBy(function ($detail) {
            return $detail->order->user->id;
        });

        foreach ($grouped as $userId => $details) {
            $user = $details->first()->order->user;

            $links = [];
            foreach ($details as $detail) {
                $product = $detail->product;

                $alreadyReviewed = $user->reviews()
                    ->where('product_id', $product->id)
                    ->exists();

                if (!$alreadyReviewed) {
                    $links[] = [
                        'name' => $product->name,
                        'url' => env('WEB_URL') . 'product-details/'.$product->slug
                    ];
                }
            }

            if (count($links) > 0) {
                Mail::to($user->email)->send(new ReviewReminderMail($user, $links));
            }
        }

        $this->info('Review reminders sent successfully.');
    }
}
