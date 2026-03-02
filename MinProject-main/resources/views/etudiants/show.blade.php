<h1>Détails de {{ $etudiant->nom }} {{ $etudiant->prenom }}</h1>

<p>Email: {{ $etudiant->email }}</p>
<p>Âge: {{ $etudiant->age }}</p>
<p>Classe: {{ $etudiant->classe->nom ?? '' }}</p>

<h2>Matières:</h2>
<ul>
    @foreach($etudiant->matieres as $matiere)
        <li>{{ $matiere->nom }} (Note: {{ $matiere->pivot->note ?? '-' }})</li>
    @endforeach
</ul>

<a href="{{ route('etudiants.index') }}">Retour à la liste</a>
