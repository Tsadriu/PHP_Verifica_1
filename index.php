<?php
$db = require_once 'db_config.php';
$results_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;

// Se richiesto, filtra per nome studente
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

$students = $db->prepare("SELECT * FROM students WHERE name LIKE :filter LIMIT :limit OFFSET :offset");
$students->bindValue(':filter', '%' . $filter . '%', PDO::PARAM_STR );
$students->bindValue(':limit', (int) $results_per_page, PDO::PARAM_INT);
$students->bindValue(':offset', (int) $start_from, PDO::PARAM_INT);
$students->execute();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <title>School Management</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
    <h1>Elenco Studenti</h1>
    <form class="search-form">
        <input type="text" name="filter" placeholder="Nome studente" value="<?php echo $filter;?>" >
        <input type="submit" value="Filtro">
    </form>
    <div class="action-bar">
        <a class="btn new-entry-link btn-primary" href="new_entry.php">Inserisci nuovo studente o corso</a>
        <a class="btn btn-primary" href="edit_courses.php">Modifica Corsi</a> <!-- Aggiunto il pulsante Modifica Corsi -->
    </div>

    <table>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Data di nascita</th>
        </tr>
        <?php while ($student = $students->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                <td><?php echo $student['name']; ?></td>
                <td><?php echo $student['email']; ?></td>
                <td><?php echo $student['birthdate']; ?></td>
                <td><a class="btn" href="edit_student.php?id=<?php echo $student['id']; ?>">Modifica</a></td>
                <td>
                    <a class="btn btn-danger" href="delete_student.php?id=<?php echo $student['id']; ?>">Elimina</a>
                </td>
            </tr>
        <?php } ?>
    </table>
    <?php
    // Calcola il numero di pagine
    $students = $db->prepare("SELECT COUNT(*) as count FROM students WHERE name LIKE :filter");
    $students->bindValue(':filter', '%' . $filter . '%', PDO::PARAM_STR );
    $students->execute();
    $row = $students->fetch(PDO::FETCH_ASSOC);
    $total_pages = ceil($row["count"] / $results_per_page); // calcola il numero di pagine
    for ($i=1; $i<=$total_pages; $i++) {  // stampa link per tutte le pagine
        echo "<a href='index.php?filter=".$filter."&page=".$i."'";
        if ($i==$page)  echo " class='curPage'";
        echo ">".$i."</a> ";
    }
    ?>
</div>
</body>
</html>
