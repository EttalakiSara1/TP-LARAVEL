<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Enseignant;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEtudiants = Etudiant::count();
        $totalClasses = Classe::count();
        $totalMatieres = Matiere::count();
        $totalEnseignants = Enseignant::count();

        $moyenneGenerale = Etudiant::all()->avg(fn($e) => $e->moyennePonderee());

        $meilleureClasse = Classe::with('etudiants.matieres')
            ->get()
            ->sortByDesc(function ($classe) {
                return $classe->etudiants->avg(fn($e) => $e->moyennePonderee());
            })
            ->first();

        return view('dashboard', compact(
            'totalEtudiants',
            'totalClasses',
            'totalMatieres',
            'totalEnseignants',
            'moyenneGenerale',
            'meilleureClasse'
        ));
    }
}
