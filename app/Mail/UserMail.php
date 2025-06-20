<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;
    protected $type;
    protected $password;
    protected $added_by;
    /**
     * Create a new message instance.
     */
    public function __construct(User $user,$password, $added_by)
    {
        $this->user = $user;
        $this->password = $password;
        $this->added_by = $added_by;
        switch ($user->roles->first()->name) {
            case 'admin':
                $this->type = 'Admin';
                break;
            case 'service_user':
                $this->type = 'Utilisateur Service';
                break;
            case 'viewer':
                $this->type = 'Lecteur';
                break;

            default:
                $this->type = 'Indéfini';
                break;
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vous avez été ajouté sur Riskless',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.user.new_user_mail',
            with: [
                'user' => $this->user,
                'type' => $this->type,
                'password' => $this->password,
                'added_by' => $this->added_by,
            ],
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
