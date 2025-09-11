<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
     protected $fillable = [
        'utilisateur_id',
        'covoiturage_id',
        'note',
        'commentaire',
        'status',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function covoiturage()
    {
        return $this->belongsTo(Covoiturage::class);
    }
}
