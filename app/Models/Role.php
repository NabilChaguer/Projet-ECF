<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'libelle',
    ];

    public function utilisateurs()
    {
        return $this->belongsToMany(Utilisateur::class, 'role_utilisateur', 'role_id', 'utilisateur_id');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_utilisateur', 'utilisateur_id', 'role_id');
    }
}
