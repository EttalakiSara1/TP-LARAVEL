<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'age',
        'classe_id'
    ];
    public function matieres()
    {
        return $this->belongsToMany(Matiere::class)
                    ->withPivot('note')
                    ->withTimestamps();
    }

     //  Calcul dynamique de la moyenne
    public function moyenne()
    {
        return $this->matieres()->avg('note');
    }

    // Optionnel : moyenne pondérée
    public function moyennePonderee()
    {
        $totalNote = 0;
        $totalCoef = 0;

        foreach ($this->matieres as $matiere) {
            if ($matiere->pivot->note !== null) {
                $totalNote += $matiere->pivot->note * $matiere->coefficient;
                $totalCoef += $matiere->coefficient;
            }
        }

        return $totalCoef > 0 ? round($totalNote / $totalCoef, 2) : 0;
    }

    // Scope admis
    public function scopeAdmis($query)
    {
        return $query->whereHas('matieres', function($q){
            $q->where('note','>=',10);
        });
    }

    // Relation Classe
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }
    
}

