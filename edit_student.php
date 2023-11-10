<?php
$db = require_once 'db_config.php';

// Controlla se è stato inviato un form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Aggiorna il record dello studente nel database
    $stmt = $db->prepare("UPDATE students SET name = :name, email = :email, birthdate = :birthdate WHERE id = :id");
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':birthdate', $_POST['birthdate']);
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();

    // Rimuovi tutte le iscrizioni dello studente
    $stmt = $db->prepare("DELETE FROM enrollments WHERE student_id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();

    // Aggiungi nuove iscrizioni
    if (isset($_POST['courses'])) {
        foreach ($_POST['courses'] as $course_id) {
            $stmt = $db->prepare("INSERT INTO enrollments (student_id, course_id, enrollment_date) VALUES (:student_id, :course_id, CURRENT_DATE)");
            $stmt->bindParam(':student_id', $_GET['id']);
            $stmt->bindParam(':course_id', $course_id);
            $stmt->execute();
        }
    }

    // Reindirizza indietro alla pagina indice
    header("Location: index.php");
}

// Ottieni i dati dello studente corrente
$stmt = $db->prepare("SELECT * FROM students WHERE id = :id");
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();
$user = $stmt->fetch();

// Ottieni tutti i corsi disponibili
$courses = $db->query("SELECT * FROM courses")->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head><link rel="stylesheet" type="text/css" href="style.css"></head>
<body>
<h2>Modifica Studente</h2>
<form method="post">
    Nome: <input type="text" name="name" value="<?php echo $user['name']; ?>"><br>
    Email: <input type="text" name="email" value="<?php echo $user['email']; ?>"><br>
    Data di nascita: <input type="date" name="birthdate" value="<?php echo $user['birthdate']; ?>"><br>

    <p>Corsi dello studente:</p>
    <?php
    // Ottieni i corsi dello studente
    $enrollments = $db->prepare("SELECT * FROM enrollments WHERE student_id = :student_id");
    $enrollments->bindParam(':student_id', $_GET['id']);
    $enrollments->execute();
    $enrolled_courses = $enrollments->fetchAll(PDO::FETCH_COLUMN, 2);

    // Mostra i corsi disponibili e seleziona quelli a cui lo studente è iscritto
    foreach ($courses as $course) {
        echo '<input type="checkbox" name="courses[]" value="' . $course['id'] . '"';
        if (in_array($course['id'], $enrolled_courses)) {
            echo ' checked';
        }
        echo '> ' . $course['title'] . '<br>';
    }
    ?>

    <input type="submit" value="Salva">
    <a href="index.php" class="btn btn-secondary">Annulla</a>
</form>
</body>
</html>
