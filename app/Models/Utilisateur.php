<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use Notifiable;

    protected $table = 'utilisateurs';
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
        'credit',
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

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'utilisateur_id');
    }
}
