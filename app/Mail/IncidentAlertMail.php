<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class IncidentAlertMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $incident;
    public $serviceName;
    public $recipientName;
    public $actionUrl;

    /**
     * Create a new message instance.
     *
     * @param  \\App\\Models\\Incident  $incident
     * @param  string  $serviceName
     * @param  string  $recipientName
     * @param  string  $actionUrl
     */
    public function __construct($incident, string $serviceName, string $recipientName, string $actionUrl)
    {
        $this->incident     = $incident;
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
            subject: 'Alerte d\'incident – ' . $this->serviceName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.incident.incident_alert',
            with: [
                'incident'     => $this->incident,
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
