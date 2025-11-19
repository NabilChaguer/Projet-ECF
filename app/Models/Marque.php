<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marque extends Model
{
    protected $table = 'marques';
    protected $primaryKey = 'marque_id';
    public $timestamps = true;

    protected $fillable = [
        'libelle',
    ];
}
