<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $data;
    public $slug;
  
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $slug)
    {
        $this->data = $data;
        $this->slug = $slug;
    }
  
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ( isset($this->data['attachment']) && $this->data['attachment'] )
        {
            return $this->subject( $this->slug )->attach( $this->data['attachment'] )->view('email.email');
        }
        else
        {
            return $this->subject( $this->slug )->view('email.email');
        }
    }
}
