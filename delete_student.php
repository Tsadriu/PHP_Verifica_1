<?php
$db = require_once 'db_config.php';

// Controlla se l'utente ha confermato l'eliminazione
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Elimina prima gli studenti che fanno parte di questa materia
    $stmt = $db->prepare("DELETE FROM enrollments WHERE student_id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();

    // Elimina il record dal database
    $stmt = $db->prepare("DELETE FROM students WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();

    // Reindirizza alla pagina indice
    header("Location: index.php");
} else {
    // Chiedi conferma all'utente
    ?>
    <!DOCTYPE html>
    <html lang="it">
    <head>
        <title>Conferma Eliminazione</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
    <div class="container">
        <h2>Conferma Eliminazione</h2>
        <p>Sei sicuro di voler eliminare questo studente?</p>
        <form method='post'>
            <input class="btn btn-danger" type='submit' value='Conferma'>
            <a href="index.php" class="btn btn-secondary">Annulla</a>
        </form>
    </div>
    </body>
    </html>
    <?php
}
?>
