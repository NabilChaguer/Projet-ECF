<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Covoiturage extends Model
{
    protected $fillable = [
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
    ];

    public function voiture()
    {
        return $this->belongsTo(Voiture::class, 'voiture_id');
    }

    public function avis()
    {
        return $this->hasMany(Avis::class, 'covoiturage_id');
    }
}
