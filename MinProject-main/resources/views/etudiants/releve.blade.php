<h1>Relevé de notes</h1>
<p>Nom: {{ $etudiant->nom }} {{ $etudiant->prenom }}</p>
<p>Classe: {{ $etudiant->classe->nom ?? '-' }}</p>

<table border="1" cellspacing="0" cellpadding="5">
    <tr>
        <th>Matière</th>
        <th>Note</th>
    </tr>
    @foreach($etudiant->matieres as $matiere)
    <tr>
        <td>{{ $matiere->nom }}</td>
        <td>{{ $matiere->pivot->note ?? '-' }}</td>
    </tr>
    @endforeach
</table>

<p>Moyenne: {{ $moyenne }}</p>
<p>Mention: {{ $mention }}</p>
