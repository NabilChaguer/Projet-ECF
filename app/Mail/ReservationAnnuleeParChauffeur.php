<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Covoiturage;

class ReservationAnnuleeParChauffeur extends Mailable
{
    use Queueable, SerializesModels;

    public $covoiturage;

    public function __construct(Covoiturage $covoiturage)
    {
        $this->covoiturage = $covoiturage;
    }

    public function build()
    {
        return $this->subject('Covoiturage annulé par le chauffeur')
                    ->view('emails.reservation-annulee-par-chauffeur')
                    ->with(['covoiturage' => $this->covoiturage]);
    }
}



