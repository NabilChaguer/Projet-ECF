<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voiture extends Model
{
    protected $fillable = [
        'marque',
        'modele',
        'immatriculation',
        'energie',
        'couleur',
        'date_premiere_immatriculation',
        'utilisateur_id',
        'preferences',
        'places_disponibles',
    ];

    protected $casts = [
        'preferences' => 'array',
        'date_premiere_immatriculation' => 'date',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }
    public function covoiturages()
    {
        return $this->hasMany(Covoiturage::class, 'voiture_id');
    }

        public function getEcologiqueAttribute(): bool
    {
        if (!$this->energie) {
            return false;
        }

        $energie = strtolower($this->energie);

        $energie = str_replace(
            ['é','è','ê','É','È','Ê'],
            'e',
            $energie
        );

        return in_array($energie, ['hybride', 'electrique']);
    }
}
