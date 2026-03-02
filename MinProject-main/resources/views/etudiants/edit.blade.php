<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un étudiant</title>
</head>
<body>
    <h1>Modifier un étudiant</h1>
    <form action="{{ route('etudiants.update', $etudiant->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="nom">Nom:</label>
        <input type="text" name="nom" id="nom" value="{{ $etudiant->nom }}" required><br><br>

        <label for="prenom">Prénom:</label>
        <input type="text" name="prenom" id="prenom" value="{{ $etudiant->prenom }}" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="{{ $etudiant->email }}" required><br><br>

        <label for="age">Âge:</label>
        <input type="number" name="age" id="age" value="{{ $etudiant->age }}" required><br><br>

        <label for="classe_id">Classe:</label>
        <select name="classe_id" id="classe_id" required>
            @foreach($classes as $classe)
                <option value="{{ $classe->id }}" {{ $etudiant->classe_id == $classe->id ? 'selected' : '' }}>
                    {{ $classe->nom }} - {{ $classe->niveau }}
                </option>
            @endforeach
        </select><br><br>

        <button type="submit">Mettre à jour</button>
    </form>
</body>
</html>