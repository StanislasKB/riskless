<?php

namespace App\Mail;

use App\Models\PlanAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PlanActionAlertMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $planAction;
    public $username;
   
    /**
     * Create a new message instance.
     *
     * @param  \\App\\Models\\PlanAction  $planAction
     * @param  string  $serviceName
     * @param  string  $recipientName
     * @param  string  $actionUrl
     */
    public function __construct(PlanAction $planAction, $username)
    {
        $this->planAction   = $planAction;
        $this->username  = $username;
        
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Un nouveau plan d\'action a été ajouté ' ,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.plan_action.alert',
            with: [
                'plan_action'   => $this->planAction,
                'username'  => $this->username,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \\Illuminate\\Mail\\Mailables\\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
