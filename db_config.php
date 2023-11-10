<?php

$host = "localhost";
$user = "admin";
$password = "";
$database = "school_management";

try {
    $db = new PDO("mysql:host=$host;charset=utf8", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Creami il DB se non esiste
    create_database($db, $database);

    // Aggiungi dei dati DUMMY se il DB è appena stato creato
    add_dummy_data($db);

    return $db;
} catch (PDOException $e) {
    print "Errore di connessione al database!: " . $e->getMessage();
}

/**
 * Function to create database and tables
 *
 * @param PDO $db The PDO instance
 * @param string $database The name of the database
 */
function create_database($db, $database)
{
    try {
        $db->exec("CREATE DATABASE IF NOT EXISTS $database");
        $db->exec("USE $database");

        $db->exec("CREATE TABLE IF NOT EXISTS students (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(30) NOT NULL,
            email VARCHAR(50),
            birthdate DATE NOT NULL
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS courses (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(50) NOT NULL,
            description TEXT,
            teacher_id INT(6) NOT NULL
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS enrollments (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            student_id INT(6) UNSIGNED,
            course_id INT(6) UNSIGNED,
            enrollment_date DATE NOT NULL,
            FOREIGN KEY (student_id) REFERENCES students(id),
            FOREIGN KEY (course_id) REFERENCES courses(id)
        )");

        add_dummy_data($db);

    } catch (PDOException $e) {
        echo 'Errore durante la creazione del database e delle tabelle: ' . $e->getMessage();
    }
}

/**
 * Function to add dummy data to the tables
 *
 * @param PDO $db The PDO instance
 */
function add_dummy_data($db)
{
    try {
        $studentCount = $db->query("SELECT COUNT(*) FROM students")->fetchColumn();
        $courseCount = $db->query("SELECT COUNT(*) FROM courses")->fetchColumn();
        $enrollmentCount = $db->query("SELECT COUNT(*) FROM enrollments")->fetchColumn();

        if ($studentCount != 0 || $courseCount != 0 || $enrollmentCount != 0) {
            return;
        }

        // Aggiunta di dati per students
        $studentData = [
            ['Studente 1', 'studente1@edu.ti.ch', '2000-01-01'],
            ['Studente 2', 'studente2@edu.ti.ch', '2001-02-02'],
            ['Studente 3', 'studente3@edu.ti.ch', '2002-03-03'],
            ['Studente 4', 'studente4@edu.ti.ch', '2003-04-04'],
            ['Studente 5', 'studente5@edu.ti.ch', '2004-05-05'],
            ['Studente 6', 'studente6@edu.ti.ch', '2005-06-06'],
            ['Studente 7', 'studente7@edu.ti.ch', '2006-07-07'],
            ['Studente 8', 'studente8@edu.ti.ch', '2007-08-08'],
            ['Studente 9', 'studente9@edu.ti.ch', '2008-09-09'],
            ['Studente 10', 'studente10@edu.ti.ch', '2009-10-10'],
            ['Studente 11', 'studente11@edu.ti.ch', '2010-11-11'],
            ['Studente 12', 'studente12@edu.ti.ch', '2011-12-12']
        ];

        $stmt = $db->prepare("INSERT INTO students (name, email, birthdate) VALUES (?, ?, ?)");

        foreach ($studentData as $student) {
            $stmt->execute($student);
        }

        // Aggiunta di dati per courses
        $courseData = [
            ['Modulo 151', 'Collegare banche dati a un’applicazione web ', 1],
            ['Modulo 150', 'Adattare una applicazione di commercio elettronico', 2],
            ['Modulo 183', 'Implementare la sicurezza delle applicazioni', 3]
        ];

        $stmt = $db->prepare("INSERT INTO courses (title, description, teacher_id) VALUES (?, ?, ?)");

        foreach ($courseData as $course) {
            $stmt->execute($course);
        }

        // Aggiunta di dati per enrollments
        $enrollmentData = [
            [1, 1, '2022-01-01'],
            [2, 1, '2022-01-02'],
            [3, 1, '2022-01-03'],
            [4, 2, '2022-02-01'],
            [5, 2, '2022-02-02'],
            [9, 2, '2022-02-02'],
            [7, 1, '2022-02-02'],
            [8, 1, '2022-02-02'],
            [10, 3, '2022-02-02'],
            [1, 3, '2022-03-01'],
            [3, 3, '2022-03-02'],
            [5, 3, '2022-03-03']
        ];

        $stmt = $db->prepare("INSERT INTO enrollments (student_id, course_id, enrollment_date) VALUES (?, ?, ?)");

        foreach ($enrollmentData as $enrollment) {
            $stmt->execute($enrollment);
        }

    } catch (PDOException $e) {
        echo 'Errore durante l\'inserimento dei dati: ' . $e->getMessage();
    }
}
