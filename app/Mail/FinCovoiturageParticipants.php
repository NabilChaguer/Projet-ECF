<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;

class FinCovoiturageParticipants extends Mailable
{
    use Queueable, SerializesModels;

    public Reservation $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre trajet est terminé - Merci de le valider',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.fin-covoiturage-participants',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}


