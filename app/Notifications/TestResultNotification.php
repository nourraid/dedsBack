<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $test;
    public $note;

    public function __construct($test, $note)
    {
        $this->test = $test;
        $this->note = $note;
    }

    public function build()
    {
        return $this->subject('نتيجة الاختبار الخاص بك')
                    ->view('emails.test_notification');
    }
}

