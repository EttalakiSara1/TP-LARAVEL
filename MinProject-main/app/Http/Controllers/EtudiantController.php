<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Classe;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // import PDF
use App\Models\Enseignant;
class EtudiantController extends Controller
{
 public function index(Request $request)
{
    $query = Etudiant::with(['classe','matieres']);

    // Afficher uniquement les supprimés si demandé
    if ($request->has('trashed')) {
        $query->onlyTrashed();
    }

    //  Recherche par nom
    if ($request->search) {
        $query->where('nom', 'like', '%' . $request->search . '%');
    }

    // Filtre par classe
    if ($request->classe_id) {
        $query->where('classe_id', $request->classe_id);
    }

    $allowedSorts = ['nom', 'prenom', 'email', 'age'];

    $sort = $request->sort ?? 'nom';
    $direction = $request->direction == 'desc' ? 'desc' : 'asc';

    if (in_array($sort, $allowedSorts)) {
        $query->orderBy($sort, $direction);
    }

    $etudiants = $query->paginate(5)->withQueryString();
    $classes = Classe::all();

    $matieres = \App\Models\Matiere::all();

    return view('etudiants.index', compact('etudiants','classes','matieres'));
}


    public function create()
    {
        $classes = Classe::all();
        return view('etudiants.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:etudiants',
            'age' => 'required|integer|min:1',
            'classe_id' => 'required|exists:classes,id'
        ]);

        Etudiant::create($request->all());

        return redirect()->route('etudiants.index');
    }

    public function edit($id)
    {
        $etudiant = Etudiant::findOrFail($id);
        $classes = Classe::all();

        return view('etudiants.edit', compact('etudiant','classes'));
    }

    public function update(Request $request, $id)
    {
        $etudiant = Etudiant::findOrFail($id);

        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:etudiants,email,' . $id,
            'age' => 'required|integer|min:1',
            'classe_id' => 'required|exists:classes,id'
        ]);

        $etudiant->update($request->all());

        return redirect()->route('etudiants.index');
    }

        public function show($id)
    {
        $etudiant = Etudiant::with(['classe','matieres'])->findOrFail($id);
        return view('etudiants.show', compact('etudiant'));
    }

    public function destroy($id)
    {
        Etudiant::findOrFail($id)->delete(); // soft delete
        return redirect()->route('etudiants.index');
    }

    // Nouvelle fonction : restaurer étudiant supprimé
    public function restore($id)
    {
        Etudiant::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('etudiants.index');
    }

    // Nouvelle fonction : supprimer définitivement
    public function forceDelete($id)
    {
        Etudiant::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('etudiants.index');
    }

        public function attachMatieres(Request $request, $id)
        {
            $etudiant = Etudiant::findOrFail($id);

            if (!$request->matiere_id) {
                return redirect()->back();
            }

            $etudiant->matieres()->syncWithoutDetaching([
                $request->matiere_id => [
                    'note' => $request->note
                ]
            ]);

            return redirect()->back();
        }
    public function detachMatiere($etudiantId, $matiereId)
    {
        $etudiant = Etudiant::findOrFail($etudiantId);

        $etudiant->matieres()->detach($matiereId);

        return redirect()->back();
    }
    public function syncMatieres(Request $request, $id)
    {
        $etudiant = Etudiant::findOrFail($id);

        $request->validate([
            'matieres' => 'array',
            'matieres.*' => 'exists:matieres,id'
        ]);

        $etudiant->matieres()->sync($request->matieres ?? []);

        return redirect()->back();
    }
    public function updateNote(Request $request, $etudiantId, $matiereId)
{
    $request->validate([
        'note' => 'nullable|numeric|min:0|max:20'
    ]);

    $etudiant = Etudiant::findOrFail($etudiantId);
    $etudiant->matieres()->updateExistingPivot($matiereId, [
        'note' => $request->note
    ]);

    return redirect()->back();
}

public function statsMatieres()
{
    $matieres = Matiere::with(['enseignants','etudiants'])->withCount('etudiants')->get();

    return view('stats.matieres', compact('matieres'));
}



public function classement()
{
    $etudiants = Etudiant::all()->sortByDesc(fn($e) => $e->moyennePonderee());

    return view('classement', compact('etudiants'));
}


public function releve($id)
{
    $etudiant = Etudiant::with(['classe','matieres'])->findOrFail($id);

    $moyenne = $etudiant->moyennePonderee();

    $mention = $moyenne >= 10 ? 'Admis' : 'Ajourné';
    if ($moyenne == max(Etudiant::all()->pluck(fn($e) => $e->moyennePonderee()))) {
        $mention = 'Major';
    }

    $pdf = Pdf::loadView('etudiants.releve', compact('etudiant','moyenne','mention'));
    return $pdf->download("releve_{$etudiant->nom}.pdf");
}
}
