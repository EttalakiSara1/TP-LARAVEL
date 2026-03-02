<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use Illuminate\Http\Request;

class EtudiantController extends Controller
{
    // GET ALL
    public function index()
    {
        return response()->json(
            Etudiant::with('classe')->get(),
            200
        );
    }

    // GET ONE
    public function show($id)
    {
        $etudiant = Etudiant::with('classe')->find($id);

        if (!$etudiant) {
            return response()->json([
                'message' => 'Etudiant non trouvé'
            ], 404);
        }

        return response()->json($etudiant, 200);
    }

    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:etudiants',
            'age' => 'required|integer',
            'classe_id' => 'required|exists:classes,id'
        ]);

        $etudiant = Etudiant::create($request->all());

        return response()->json([
            'message' => 'Etudiant créé avec succès',
            'data' => $etudiant
        ], 201);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $etudiant = Etudiant::find($id);

        if (!$etudiant) {
            return response()->json([
                'message' => 'Etudiant non trouvé'
            ], 404);
        }

        $etudiant->update($request->all());

        return response()->json([
            'message' => 'Etudiant modifié avec succès',
            'data' => $etudiant
        ], 200);
    }

    // DELETE
    public function destroy($id)
    {
        $etudiant = Etudiant::find($id);

        if (!$etudiant) {
            return response()->json([
                'message' => 'Etudiant non trouvé'
            ], 404);
        }

        $etudiant->delete();

        return response()->json([
            'message' => 'Etudiant supprimé'
        ], 200);
    }
}
