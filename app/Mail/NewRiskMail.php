<?php

namespace App\Mail;

use App\Models\FicheRisque;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewRiskMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $risque;
    protected $username;

    /**
     * Create a new message instance.
     */
    public function __construct(FicheRisque $risque,$username)
    {
        $this->risque =$risque;
        $this->username =$username;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Un nouveau risque a été ajouté',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.notifications.new_risk_mail',
            with :[
                'fiche_risque'=>$this->risque,
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
