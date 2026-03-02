<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Etudiant;
use App\Models\Classe;

class EtudiantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $etudiants = [
            [
                'nom' => 'Diop',
                'prenom' => 'Amadou',
                'email' => 'amadou.diop@email.com',
                'age' => 22,
                'classe_id' => 1
            ],
            [
                'nom' => 'Ndiaye',
                'prenom' => 'Fatou',
                'email' => 'fatou.ndiaye@email.com',
                'age' => 21,
                'classe_id' => 1
            ],
            [
                'nom' => 'Sall',
                'prenom' => 'Moussa',
                'email' => 'moussa.sall@email.com',
                'age' => 23,
                'classe_id' => 2
            ],
            [
                'nom' => 'Ba',
                'prenom' => 'Aïssatou',
                'email' => 'aissatou.ba@email.com',
                'age' => 20,
                'classe_id' => 2
            ],
            [
                'nom' => 'Fall',
                'prenom' => 'Ibrahima',
                'email' => 'ibrahima.fall@email.com',
                'age' => 24,
                'classe_id' => 3
            ],
            [
                'nom' => 'Sy',
                'prenom' => 'Mariama',
                'email' => 'mariama.sy@email.com',
                'age' => 19,
                'classe_id' => 3
            ],
            [
                'nom' => 'Kane',
                'prenom' => 'Ousmane',
                'email' => 'ousmane.kane@email.com',
                'age' => 22,
                'classe_id' => 1
            ],
            [
                'nom' => 'Thiam',
                'prenom' => 'Aminata',
                'email' => 'aminata.thiam@email.com',
                'age' => 21,
                'classe_id' => 2
            ],
            [
                'nom' => 'Mbaye',
                'prenom' => 'Cheikh',
                'email' => 'cheikh.mbaye@email.com',
                'age' => 23,
                'classe_id' => 3
            ],
            [
                'nom' => 'Cisse',
                'prenom' => 'Khady',
                'email' => 'khady.cisse@email.com',
                'age' => 20,
                'classe_id' => 1
            ]
        ];

        foreach ($etudiants as $etudiant) {
            Etudiant::create($etudiant);
        }
    }
}
