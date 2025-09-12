<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $table = 'configurations';
    protected $primaryKey = 'id_configuration';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [];

    /**
     * Relation : une configuration peut avoir plusieurs paramètres
     */
    public function parametres()
    {
        return $this->belongsTo(Configuration::class, 'configuration_id', 'id_configuration');
    }
}
