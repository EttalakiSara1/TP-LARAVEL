<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Classe;


class ClassementController extends Controller
{
     public function index()
    {
        $etudiants = Etudiant::all()
            ->sortByDesc(fn($e)=> $e->moyennePonderee())
            ->values();

        return view('classement', compact('etudiants'));
    }
}
