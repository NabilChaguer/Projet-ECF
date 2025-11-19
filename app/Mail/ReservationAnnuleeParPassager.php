<?php

namespace App\Mail;

use App\Models\Covoiturage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationAnnuleeParPassager extends Mailable
{
    use Queueable, SerializesModels;

    public $covoiturage;

    public function __construct(Covoiturage $covoiturage)
    {
        $this->covoiturage = $covoiturage;
    }

    public function build()
    {
        return $this->subject("Un passager s'est désisté")
                    ->view('emails.reservation-annulee-par-passager');
    }
}

