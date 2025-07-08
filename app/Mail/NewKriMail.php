<?php

namespace App\Mail;

use App\Models\Indicateur;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewKriMail extends Mailable
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
            subject: 'Un nouveau indicateur de risque a été ajouté',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.notifications.new_kri_mail',
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
