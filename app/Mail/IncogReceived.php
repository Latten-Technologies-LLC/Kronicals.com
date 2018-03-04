<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class IncogReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $incogId;
    public $fullname;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        // User info
        $this->name = $data['fullname'];

        // Url
        $this->url = $data['url'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view.name');
    }
}
