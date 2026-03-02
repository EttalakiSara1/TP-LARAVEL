<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    protected $fillable = [
        'nom',
        'email',
        'specialite'
    ];


        public function matieres()
    {
        return $this->belongsToMany(Matiere::class);
    }
}
