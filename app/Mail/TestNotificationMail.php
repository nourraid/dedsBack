<?php
namespace App\Mail;

use App\Models\AiTest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $test;
    public $note;

    public function __construct(AiTest $test, $note)
    {
        $this->test = $test;
        $this->note = $note;
    }

    public function build()
    {
        return $this->subject('Notification about your test results')
                    ->view('emails.test_notification')
                    ->with([
                        'test' => $this->test,
                        'note' => $this->note,
                    ]);
    }
}
