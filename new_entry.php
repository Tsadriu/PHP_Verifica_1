<?php
// Includi il file di configurazione del database
$db = require_once 'db_config.php';

// Controlla se il form è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = trim($_POST['student_name']);
    $student_email = trim($_POST['student_email']);
    $student_birthdate = trim($_POST['student_birthdate']);
    $course_title = trim($_POST['course_title']);
    $course_description = trim($_POST['course_description']);
    $course_teacher_id = trim($_POST['course_teacher_id']);

    // Controlla se sono stati forniti dati dello studente e inserisci
    if (!empty($student_name) && !empty($student_email) && !empty($student_birthdate)) {
        $student_name = $db->quote($student_name);
        $student_email = $db->quote($student_email);
        $student_birthdate = $db->quote($student_birthdate);

        $sql = "INSERT INTO students (name, email, birthdate) VALUES ($student_name, $student_email, $student_birthdate)";
        $db->exec($sql);
    }

    // Controlla se sono stati forniti dati del corso e inserisci
    if (!empty($course_title) && !empty($course_description) && !empty($course_teacher_id)) {
        $course_title = $db->quote($course_title);
        $course_description = $db->quote($course_description);
        $course_teacher_id = $db->quote($course_teacher_id);

        $sql = "INSERT INTO courses (title, description, teacher_id) VALUES ($course_title, $course_description, $course_teacher_id)";
        $db->exec($sql);
    }

    // Reindirizza alla pagina indice
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <title>Nuova Entry</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
    <form class="entry-form" method="post">
        <h2>Nuovo Studente</h2>
        Nome: <input type="text" name="student_name" required><br>
        Email: <input type="email" name="student_email" required><br>
        Data di nascita: <input type="date" name="student_birthdate" required><br>
        <input class="btn" type="submit" value="Invia">
    </form>
    <form class="entry-form" method="post">
        <h2>Nuovo Corso</h2>
        Titolo: <input type="text" name="course_title" required><br>
        Descrizione: <textarea name="course_description" required></textarea><br>
        ID Insegnante: <input type="number" name="course_teacher_id" required><br>

        <input class="btn" type="submit" value="Invia">
    </form>
    <form class="entry-form">
        <a href="index.php" class="btn btn-secondary">Annulla</a>
    </form>
</div>
</body>
</html>
