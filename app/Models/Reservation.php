<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';
    protected $fillable = [
        'utilisateur_id',
        'covoiturage_id',
        'statut'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    public function covoiturage()
    {
        return $this->belongsTo(Covoiturage::class, 'covoiturage_id');
    }
}

