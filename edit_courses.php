<?php
$db = require_once 'db_config.php';

// Controlla se è stato inviato un form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Aggiorna il record del corso nel database
    $stmt = $db->prepare("UPDATE courses SET title = :title, description = :description, teacher_id = :teacher_id WHERE id = :id");
    $stmt->bindParam(':title', $_POST['title']);
    $stmt->bindParam(':description', $_POST['description']);
    $stmt->bindParam(':teacher_id', $_POST['teacher_id']);
    $stmt->bindParam(':id', $_POST['course_id']);
    $stmt->execute();

    // Reindirizza indietro alla pagina indice
    header("Location: index.php");
}

// Elimina un corso
if (isset($_GET['delete_course'])) {
    $stmt = $db->prepare("DELETE FROM enrollments WHERE course_id = :id");
    $stmt->bindParam(':id', $_GET['delete_course']);
    $stmt->execute();
    $stmt = $db->prepare("DELETE FROM courses WHERE id = :id");
    $stmt->bindParam(':id', $_GET['delete_course']);
    $stmt->execute();

    // Reindirizza indietro alla pagina indice
    header("Location: index.php");
}

// Ottieni tutti i corsi disponibili
$courses = $db->query("SELECT * FROM courses")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" type="text/css" href="style.css"></head>
<body>
<h2>Modifica Corsi</h2>
<table>
    <tr><th>Titolo</th><th>Descrizione</th><th>ID Insegnante</th><th>Azioni</th></tr>
    <?php foreach ($courses as $course) { ?>
        <tr>
            <td><?php echo $course['title']; ?></td>
            <td><?php echo $course['description']; ?></td>
            <td><?php echo $course['teacher_id']; ?></td>
            <td>
                <!-- Modifica questo link per utilizzare la tua pagina di conferma per eliminare corso -->
                <a class="btn btn-danger" href="delete_course.php?id=<?php echo $course['id']; ?>">Elimina</a>
            </td>
        </tr>
    <?php } ?>
</table>
<a href="index.php" class="btn btn-secondary">Annulla</a>
</body>
</html>
