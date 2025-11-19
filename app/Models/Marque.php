<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marque extends Model
{
    protected $table = 'marques'; // Nom exact de la table
    protected $primaryKey = 'marque_id'; // Clé primaire personnalisée
    public $timestamps = true;

    protected $fillable = [
        'libelle',
    ];
}
