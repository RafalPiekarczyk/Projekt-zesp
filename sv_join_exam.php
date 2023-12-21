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
$examID = $_POST['examCode'];

$zapytanie = "SELECT * FROM exams WHERE exams.exam_enter_code='$examID'";
$result = $conn->query($zapytanie) or die ('bledne zapytanie');

$response = "";

if(mysqli_num_rows($result)>0){

	while($wiersz = $result->fetch_assoc())
	{
        $response = "<div class='alert alert-success' role='alert'> Dołączono do egzaminu </div>";
        $_SESSION["examID"]=$examID;
	};
}else{
	$response = "<div class='alert alert-danger' role='alert'> Błąd podczas dołączania do egzaminu. Egzamin nie istnieje lub kod jest niepoprawny. </div>"."#err";
}

// Zamknij połączenie z bazą danych
$conn->close();

// Zwróć odpowiedź
echo $response;
?>