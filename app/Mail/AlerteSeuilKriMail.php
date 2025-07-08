<?php

namespace App\Mail;

use App\Models\Indicateur;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AlerteSeuilKriMail extends Mailable
{
    use Queueable, SerializesModels;
   protected $kri, $username;

    /**
     * Create a new message instance.
     */
    public function __construct(Indicateur $kri, $username)
    {
        $this->kri =$kri;
        $this->username =$username;
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Le seuil d\'un indicateur de risque a été dépassé',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.notifications.alerte_seuil_kri_mail',
             with :[
                'indicateur'=>$this->kri,
                'username'=>$this->username,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
