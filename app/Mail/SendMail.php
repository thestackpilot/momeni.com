<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $slug;
    public $template;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $slug, $template = 'email.email')
    {
        $this->data = $data;
        $this->slug = $slug;
        $this->template = $template;
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
        else if(isset($this->data['pdf']) && $this->data['pdf'])
        {
            $form_data           = [ 'data' => $this->data ];
            $form_pdf            = PDF::loadView($this->template, $form_data);
            $pdf_content         = $form_pdf->output();

            $email = $this->subject($this->slug)->view($this->template);
            if (isset($this->data['pdf'])) {
                $pdf_content = base64_decode($this->data['pdf']);
                $email->attachData($pdf_content, $this->slug . '.pdf', ['mime' => 'application/pdf']);
            }
            return $email;
        }
        else
        {
            return $this->subject( $this->slug )->view($this->template);
        }
    }
}
