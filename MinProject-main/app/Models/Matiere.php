<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    protected $fillable = [
        'nom',
        'coefficient'
    ];
    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class);
    }
    public function enseignants()
    {
        return $this->belongsToMany(Enseignant::class);
    }
}
