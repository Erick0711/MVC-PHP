<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Contacto</title>
</head>
<body>

    <h1>Detalle de contacto</h1>
    <p>Numero: <?= $contact['numero'] ?></p>
    <p>Direccion: <?= $contact['direccion'] ?></p>
    <a href="/contacts/<?= $contact['id'] ?>/edit">Editar</a>
</body>
</html>