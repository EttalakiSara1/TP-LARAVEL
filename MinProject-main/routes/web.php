<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\EtudiantController;

// Page d'accueil
Route::get('/', fn() => view('welcome'));

// Auth routes (Breeze ou Laravel Auth)
require __DIR__.'/auth.php';

// Routes accessibles à tous les utilisateurs authentifiés
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --------------------------
// ADMIN ROUTES
// --------------------------
Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD
    Route::resource('etudiants', EtudiantController::class);
    Route::resource('enseignants', EnseignantController::class);

    // Matières liées aux étudiants
    Route::post('/etudiants/{etudiant}/attach', [EtudiantController::class,'attachMatieres'])->name('etudiants.attach');
    Route::delete('/etudiants/{etudiant}/detach/{matiere}', [EtudiantController::class,'detachMatiere'])->name('etudiants.detach');
    Route::post('/etudiants/{etudiant}/matiere/{matiere}/update', [EtudiantController::class,'updateNote'])->name('etudiants.updateNote');

    // Statistiques
    Route::get('/stats/matieres', [EtudiantController::class,'statsMatieres'])->name('stats.matieres');

    // Classement
    Route::get('/classement', [EtudiantController::class,'classement'])->name('classement');
});

// --------------------------
// ENSEIGNANT ROUTES
// --------------------------
Route::middleware(['auth','role:enseignant'])->group(function () {
    Route::get('/mes-matieres', [EnseignantController::class, 'mesMatieres'])->name('enseignant.matieres');
});

// --------------------------
// ETUDIANT ROUTES
// --------------------------
Route::middleware(['auth','role:etudiant'])->group(function () {
    Route::get('/mes-notes', [EtudiantController::class, 'mesNotes'])->name('etudiant.notes');
    Route::get('/etudiants/{id}/releve', [EtudiantController::class,'releve'])->name('etudiant.releve');
});


?>
