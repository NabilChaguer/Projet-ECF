<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{
    use HasFactory;

    protected $table = 'parametres';
    protected $primaryKey = 'parametre_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['propriete', 'valeur', 'configuration_id'];

    /**
     * Relation : un paramètre appartient à une configuration
     */
    public function configuration()
    {
        return $this->belongsTo(Configuration::class, 'configuration_id', 'id_configuration');
    }
}