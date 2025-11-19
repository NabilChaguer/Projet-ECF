<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Covoiturage extends Model
{
    protected $fillable = [
        'utilisateur_id',
        'voiture_id',
        'nb_place',
        'prix_personne',
        'lieu_depart',
        'lieu_arrivee',
        'date_depart',
        'heure_depart',
        'date_arrivee',
        'heure_arrivee',
        'statut',
        'ecologique',
    ];

    protected $casts = [
        'ecologique' => 'boolean',
        'date_depart' => 'date',
        'date_arrivee' => 'date',
        'heure_depart' => 'string',
        'heure_arrivee' => 'string',
    ];

    public function voiture()
    {
        return $this->belongsTo(Voiture::class, 'voiture_id');
    }

    public function avis()
    {
        return $this->hasMany(Avis::class, 'covoiturage_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'covoiturage_id');
    }

    public function chauffeur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    public function peutAnnuler()
    {
        $now = now();

        $date = \Carbon\Carbon::parse($this->date_depart)->toDateString();
        $heure = $this->heure_depart ? \Carbon\Carbon::parse($this->heure_depart)->format('H:i:s') : '00:00:00';

        // Fusion propre
        $depart = \Carbon\Carbon::parse("$date $heure");
        if ($depart->isPast()) {
            return false;
        }

        // Chauffeur → peut annuler son propre covoiturage
        if ($this->utilisateur_id === auth()->id()) {
            return true;
        }

        // Participant → peut annuler si il a une réservation
        return $this->reservations()
            ->where('utilisateur_id', auth()->id())
            ->exists();
    }

}
