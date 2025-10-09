<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voiture extends Model
{
    protected $fillable = [
        'modele',
        'immatriculation',
        'energie',
        'couleur',
        'date_premiere_immatriculation',
        'utilisateur_id',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }
    public function covoiturages()
    {
        return $this->hasMany(Covoiturage::class, 'voiture_id');
    }
}
