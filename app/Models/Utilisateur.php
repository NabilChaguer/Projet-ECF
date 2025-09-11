<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'telephone',
        'adresse',
        'date_naissance',
        'photo',
        'pseudo',
    ];

 
    public function voitures()
    {
        return $this->hasMany(Voiture::class, 'utilisateur_id');
    }

    public function covoiturages()
    {
        return $this->hasManyThrough(Covoiturage::class, Voiture::class, 'utilisateur_id', 'voiture_id');
    }

    public function avis()
    {
        return $this->hasMany(Avis::class, 'utilisateur_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_utilisateur', 'utilisateur_id', 'role_id');
    }
}
