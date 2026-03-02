<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des étudiants</title>

    <style>
body {
    font-family: 'Segoe UI', Tahoma, sans-serif;
    background-color: #f4f6f9;
    margin: 0;
    padding: 30px;
    color: #333;
}

h1 {
    margin-bottom: 20px;
    color: #2c3e50;
}

h2 {
    margin-top: 40px;
    color: #34495e;
}

/* Buttons */
.btn {
    padding: 6px 12px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    text-decoration: none;
    display: inline-block;
    transition: 0.3s ease;
}

.btn-primary {
    background-color: #3498db;
    color: white;
}

.btn-primary:hover {
    background-color: #2980b9;
}

.btn-success {
    background-color: #2ecc71;
    color: white;
}

.btn-success:hover {
    background-color: #27ae60;
}

.btn-danger {
    background-color: #e74c3c;
    color: white;
}

.btn-danger:hover {
    background-color: #c0392b;
}

.btn-gray {
    background-color: #bdc3c7;
    color: #2c3e50;
}

.btn-gray:hover {
    background-color: #95a5a6;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    border-radius: 10px;
    overflow: hidden;
}

th {
    background-color: #34495e;
    color: white;
    padding: 12px;
    text-align: left;
}

td {
    padding: 10px;
    border-bottom: 1px solid #eee;
}

tr:hover {
    background-color: #f2f8ff;
}

/* Forms */
input, select {
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

form {
    display: inline;
}

/* Cards */
.card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

/* Pagination */

.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    gap: 5px;
}

.pagination li {
    display: inline-block;
}

.pagination li a,
.pagination li span {
    padding: 4px 8px;
    font-size: 13px;
    border-radius: 5px;
    border: 1px solid #ccc;
    color: #2c3e50;
    text-decoration: none;
}

.pagination li a:hover {
    background-color: #3498db;
    color: white;
    border-color: #2980b9;
}

.pagination .active span {
    background-color: #3498db;
    color: white;
    border-color: #2980b9;
}

.matiere-block {
    background: #f9fbfd;
    padding: 6px;
    border-radius: 6px;
    margin-bottom: 6px;
}
</style>
</head>

<body>

<h1>Liste des étudiants</h1>

<a href="{{ route('etudiants.create') }}" class="btn btn-success">
    ➕ Ajouter un étudiant
</a>

<br><br>

<!-- Recherche + Filtre Classe -->
<form method="GET" action="{{ route('etudiants.index') }}">
    <input type="text" name="search"
           placeholder="Rechercher par nom"
           value="{{ request('search') }}">

    <select name="classe_id">
        <option value="">Toutes les classes</option>
        @foreach($classes as $classe)
            <option value="{{ $classe->id }}"
                {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                {{ $classe->nom }}
            </option>
        @endforeach
    </select>

    <button type="submit" class="btn">Rechercher</button>
    <a href="{{ route('etudiants.index') }}" class="btn">Réinitialiser</a>
</form>

<br>

<!-- Tableau -->
<table>
<thead>
<tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Prénom</th>
    <th>Email</th>
    <th>Âge</th>
    <th>Classe</th>
    <th>Matières (Note)</th>
    <th>Moyenne</th>
    <th>Relevé</th>
    <th>Actions</th>
</tr>
</thead>

<tbody>
@foreach($etudiants as $etudiant)
<tr>
    <td>{{ $etudiant->id }}</td>
    <td>{{ $etudiant->nom }}</td>
    <td>{{ $etudiant->prenom }}</td>
    <td>{{ $etudiant->email }}</td>
    <td>{{ $etudiant->age }}</td>
    <td>{{ $etudiant->classe->nom ?? '-' }}</td>

    <!-- Matières -->
    <td>
        @foreach($etudiant->matieres as $matiere)
            <div class="matiere-block">
                {{ $matiere->nom }}
                ({{ $matiere->pivot->note ?? '-' }})

                <!-- Update note -->
                <form action="{{ route('etudiants.updateNote', [$etudiant->id, $matiere->id]) }}" method="POST">
                    @csrf
                    <input type="number"
                           name="note"
                           value="{{ $matiere->pivot->note ?? '' }}"
                           step="0.1" min="0" max="20">
                    <button class="btn">✔</button>
                </form>

                <!-- Detach -->
                <form action="{{ route('etudiants.detach', [$etudiant->id, $matiere->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">x</button>
                </form>
            </div>
        @endforeach

        <!-- Ajouter matière -->
        <form action="{{ route('etudiants.attach', $etudiant->id) }}" method="POST">
            @csrf
            <select name="matiere_id" required>
                <option value="">-- Ajouter matière --</option>
                @foreach($matieres as $matiere)
                    @if(!$etudiant->matieres->contains($matiere->id))
                        <option value="{{ $matiere->id }}">
                            {{ $matiere->nom }}
                        </option>
                    @endif
                @endforeach
            </select>

            Note:
            <input type="number" name="note" step="0.1" min="0" max="20">
            <button class="btn btn-success">Ajouter</button>
        </form>
    </td>

    <!-- Moyenne -->
    <td>
        {{ number_format($etudiant->moyennePonderee(), 2) }}
    </td>

    <!-- Relevé PDF -->
    <td>
        <a href="{{ route('etudiant.releve', $etudiant->id) }}"
           class="btn"
           target="_blank">
            📄 PDF
        </a>
    </td>

    <!-- Actions -->
    <td>
        <a href="{{ route('etudiants.edit', $etudiant->id) }}" class="btn">
            Modifier
        </a>

        <form action="{{ route('etudiants.destroy', $etudiant->id) }}"
              method="POST"
              onsubmit="return confirm('Supprimer ?');">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Supprimer</button>
        </form>
    </td>
</tr>
@endforeach
</tbody>
</table>

<!-- Pagination -->
<div class="pagination-container" style="margin-top: 20px;">
    {{ $etudiants->links() }}
</div>

<!-- Statistiques -->
<h2>Nombre d'étudiants par matière</h2>

<table>
<thead>
<tr>
    <th>Matière</th>
    <th>Enseignants</th>
    <th>Nombre d'étudiants</th>
</tr>
</thead>
<tbody>
@foreach($matieres as $matiere)
<tr>
    <td>{{ $matiere->nom }}</td>
    <td>
        @foreach($matiere->enseignants as $enseignant)
            {{ $enseignant->nom }} <br>
        @endforeach
    </td>
    <td>
        {{ $matiere->etudiants_count ?? $matiere->etudiants->count() }}
    </td>
</tr>
@endforeach
</tbody>
</table>

</body>
</html>
