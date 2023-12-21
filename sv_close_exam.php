<?php
session_start();
// Połączenie z bazą danych
include 'dbconfig.php';
$conn = new mysqli($server, $user, $pass, $base);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// Pobranie danych z formularza
$examID = $_SESSION["examID"];

$response = "";

// Przygotowanie i wykonanie zapytania SQL
$sql = "DELETE FROM exams WHERE exam_enter_code = '$examID'";

if ($stmt = $conn->prepare($sql)) {

    // Wykonaj zapytanie
    if ($stmt->execute()) {
        $response = "<div class='alert alert-success show fade' id='notify-alert' role='alert'> Pomyślnie zakończono egzamin. </div>";
        unset($_SESSION['examID']);
    } else {
        $response = "<div class='alert alert-danger show fade' id='notify-alert' role='alert'> Błąd podczas usuwania egzaminu: " . $stmt->error . "</div>"."#err";
    }
    
    // Zamknij zapytanie
    $stmt->close();
} else {
    $response = "<div class='alert alert-danger show fade' id='notify-alert' role='alert'> Błąd przygotowywania zapytania: " . $conn->error. "</div>"."#err";
}

// Zamknij połączenie z bazą danych
$conn->close();

// Zwróć odpowiedź
echo $response;
?>