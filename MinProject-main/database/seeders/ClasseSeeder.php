<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classe;

class ClasseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            [
                'nom' => 'Informatique',
                'niveau' => 'Licence 3'
            ],
            [
                'nom' => 'Mathématiques',
                'niveau' => 'Master 1'
            ],
            [
                'nom' => 'Physique',
                'niveau' => 'Licence 2'
            ]
        ];

        foreach ($classes as $classe) {
            Classe::create($classe);
        }
    }
}
