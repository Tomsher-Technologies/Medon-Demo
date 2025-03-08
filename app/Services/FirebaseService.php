<?php

namespace App\Services;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class FirebaseService
{
    protected $firebase;

    public function __construct()
    {
        $serviceAccountPath = storage_path('app/medonrider-e328c-firebase-adminsdk-zf60y-67e038ed77.json');

        $this->firebase = (new Factory)
            ->withServiceAccount($serviceAccountPath)
            ->createAuth();
    }

    /**
     * Get Firebase Auth instance.
     *
     * @return \Kreait\Firebase\Auth
     */
    public function getAuth()
    {
        return $this->firebase;
    }

    public function sendNotificationToMultipleDevices(array $deviceTokens, string $title, string $body)
    {
        $messaging = Firebase::messaging();

        // Create the notification object with the title and body
        $notification = Notification::create($title, $body);

        $chunkedTokens = array_chunk($deviceTokens, 500);

        $allFailedTokens = [];

        foreach ($chunkedTokens as $tokensChunk) {
            $messages = array_map(function ($deviceToken) use ($notification) {
                return CloudMessage::withTarget('token', $deviceToken)
                    ->withNotification($notification);
            }, $tokensChunk);

            try {
                // Send the notifications (multicast message)
                $response = $messaging->sendMulticast($messages);

                // Process failed tokens
                $failedTokens = $response->failures()->pluck('token')->toArray();
                $allFailedTokens = array_merge($allFailedTokens, $failedTokens);
            } catch (\Exception $e) {
                // Log or handle the exception
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }
        $this->handleFailedTokens($allFailedTokens);

        return response()->json(['status' => 'Notification sent', 'failed_tokens' => $allFailedTokens]);
   
    }

    public function handleFailedTokens(array $failedTokens)
    {
        foreach ($failedTokens as $failedToken) {
            // Find the user and mark the device token as invalid (or remove)
            $user = User::where('device_token', $failedToken)->first();
            if ($user) {
                $user->device_token = null;  // Or you can update as needed
                $user->save();
            }
        }
    }
}
