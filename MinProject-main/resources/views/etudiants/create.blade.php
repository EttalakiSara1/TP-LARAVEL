<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un étudiant</title>
</head>
<body>
    <h1>Créer un nouvel étudiant</h1>
    <form action="{{ route('etudiants.store') }}" method="POST">
        @csrf
        <label for="nom">Nom:</label>
        <input type="text" name="nom" id="nom" required><br><br>

        <label for="prenom">Prénom:</label>
        <input type="text" name="prenom" id="prenom" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br><br>

        <label for="age">Âge:</label>
        <input type="number" name="age" id="age" required><br><br>

        <label for="classe_id">Classe:</label>
        <select name="classe_id" id="classe_id" required>
            @foreach($classes as $classe)
                <option value="{{ $classe->id }}">{{ $classe->nom }} - {{ $classe->niveau }}</option>
            @endforeach
        </select><br><br>

        <button type="submit">Ajouter</button>
    </form>
</body>
</html>