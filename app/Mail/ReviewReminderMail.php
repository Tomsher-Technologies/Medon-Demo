<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;

class ReviewReminderMail extends Mailable
{
    public $user;
    public $links;

    public function __construct($user, $links)
    {
        $this->user = $user;
        $this->links = $links;
    }

    public function build()
    {
        return $this->subject("We'd love your feedback on your recent purchase!")
                    ->view('emails.review_reminder');
    }
}
