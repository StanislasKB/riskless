<?php

namespace App\Mail;

use App\Models\PlanAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AlerteRetardPAMail extends Mailable
{
    use Queueable, SerializesModels;
protected $pa, $username;

    /**
     * Create a new message instance.
     */
    public function __construct(PlanAction $pa, $username)
    {
        $this->pa =$pa;
        $this->username =$username;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Alerte Retard d\'un plan d\'action',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.notifications.alerte_retard_pa_mail',
            with :[
                'plan_action'=>$this->pa,
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
