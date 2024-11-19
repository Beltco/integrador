<!-- resources/views/usuarios/create.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Usuario</title>
</head>
<body>
    <form action="#" method="POST">
        @csrf
<div>
        <label for="nuevo">Invitar al usuario </label>
        <select name="nuevo" id="nuevo" required>
            <option value="0">-- Selecciona un usuario --</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <label for="invitado">a todos los tableros donde está invitado</label>
        <select name="invitado" id="invitado" required>
            <option value="0">-- Selecciona un usuario --</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
</div>
        <br>
        <div style="text-align:right;">
        <label for="board">o solamente a este board </label>
        <input type="text" name="board" id="board" value="0">
        </div>
        <br>
        <button id="submitBtn" type="submit">Invitar</button>
    </form>

</body>
</html>
