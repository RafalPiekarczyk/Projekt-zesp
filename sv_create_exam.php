<?php
session_start();
// Połączenie z bazą danych
include 'dbconfig.php';
$conn = new mysqli($server, $user, $pass, $base);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

$seed = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789');
shuffle($seed);
$exam_code = "";
foreach (array_rand($seed, 6) as $k) $exam_code .= $seed[$k];

$creationTime = date("Y-m-d;H:i:s",time());

$response = "{}";

// Przygotowanie i wykonanie zapytania SQL
$sql = "INSERT INTO exams (exam_enter_code, questions, answers, creation_time) VALUES (?, ?, ?, ?)";

if ($stmt = $conn->prepare($sql)) {
    // Zabezpiecz dane przed SQL Injection
    $stmt->bind_param("ssss", $exam_code, $response, $response, $creationTime);
    
    // Wykonaj zapytanie
    if ($stmt->execute()) {
        $response = "<div class='alert alert-success show fade' id='notify-alert' role='alert'> Pomyślnie utworzono egzamin. </div>";
        $_SESSION["examID"]=$exam_code;
    } else {
        $response = "<div class='alert alert-danger show fade' id='notify-alert' role='alert'> Błąd podczas tworzenia egzamin: " . $stmt->error . "</div>"."#err";
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