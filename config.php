<?php
// Dane do logowania się do bazy danych
$servername = "mysql8";
$username = "00912292_sir"; // Wprowadź nazwę użytkownika
$password = "O5W7pC_K"; // Wprowadź hasło
$dbname = "00912292_sir"; // Wprowadź nazwę bazy danych


// Ustawienia PHP
error_reporting(E_ALL); // Raportowanie wszystkich błędów PHP
ini_set('display_errors', 1); // Wyświetlanie błędów PHP na ekranie

// Połącz się z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

// pliki do załadowania
include ('functions/database.php');
include ('functions/functions.php');
include ('functions/qr-code.php');
include ('functions/statistics.php');
// Sprawdź połączenie
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Prefix danych
define("PREFIX",getOptions('prefix')); // Prefix dla nazw tabel

// Adres URL strony
$site_url = getOptions('site_url'); // Adres URL Twojej strony
$site_name = getOptions('site_name'); // Nazwa Twojej strony
$admin_email = getOptions('admin_email'); // Email administratora


date_default_timezone_set(getOptions('local_zone')); // Domyślna strefa czasowa
setlocale(LC_TIME, getOptions('locale'));


define("MIN_AGE",getOptions('minimum_age')); // Prefix dla nazw tabel

// Ustawienia serwera SMTP
define('SMTP_HOST', getOptions('SMTP_HOST'));
define('SMTP_PORT', getOptions('SMTP_PORT'));
define('SMTP_USERNAME', getOptions('SMTP_USERNAME'));
define('SMTP_PASSWORD', getOptions('SMTP_PASSWORD'));
define('SMTP_ENCRYPTION', getOptions('SMTP_ENCRYPTION'));



include ('PHPMailer/src/PHPMailer.php');
include ('PHPMailer/src/SMTP.php');
include ('PHPMailer/src/Exception.php');
?>

