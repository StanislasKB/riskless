<?php

namespace App\Mail;

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
    public $serviceName;
    public $recipientName;
    public $actionUrl;

    /**
     * Create a new message instance.
     *
     * @param  \\App\\Models\\PlanAction  $planAction
     * @param  string  $serviceName
     * @param  string  $recipientName
     * @param  string  $actionUrl
     */
    public function __construct($planAction, string $serviceName, string $recipientName, string $actionUrl)
    {
        $this->planAction   = $planAction;
        $this->serviceName  = $serviceName;
        $this->recipientName= $recipientName;
        $this->actionUrl    = $actionUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Alerte Plan d’Action – ' . $this->serviceName,
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
                'planAction'   => $this->planAction,
                'serviceName'  => $this->serviceName,
                'recipientName'=> $this->recipientName,
                'actionUrl'    => $this->actionUrl,
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
