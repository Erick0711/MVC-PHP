<h1>Edit CONTACTO</h1>
<form action="/contacts/<?=$contact['id']?>" method="POST" autocomplete="off">
    <input type="text" name="numero" placeholder="Numero" value="<?= $contact['numero']?>"><br>
    <textarea type="text" name="direccion" placeholder="Numero"><?= $contact['direccion']?></textarea>
    <input type="submit" value="Actualizar">
</form>