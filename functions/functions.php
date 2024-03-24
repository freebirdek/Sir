<?php

//------------------------------------------
//--- Funkcje związane z dostępem do strony
//------------------------------------------

// Funkcja sprawdzająca, czy użytkownik ma wymaganą rolę do dostępu do strony
function checkUserAccess($requiredAccessLevel) {
    // Sprawdź, czy użytkownik jest zalogowany
    if (!isset($_SESSION['user_id'])) {
        // Użytkownik nie jest zalogowany
        return false;
    }
    
    // Pobierz poziom dostępu użytkownika z sesji
    $userAccessLevel = $_SESSION['access'];
    
    // Sprawdź dostępność strony w oparciu o role użytkownika
    switch ($requiredAccessLevel) {
        case 1:
            // Członek zawieszony - nie ma dostępu do strony
            return false;
        case 2:
            // Członek aktywny - ma dostęp tylko do swoich danych
            return $userAccessLevel >= 1; // Użytkownik ma co najmniej poziom dostępu 1
        case 3:
            // Członek z pewnymi edycjami na stronie
            return $userAccessLevel >= 2; // Użytkownik ma co najmniej poziom dostępu 2
        case 97:
            // Moderator
            return $userAccessLevel >= 97; // Użytkownik ma co najmniej poziom dostępu 97
        case 98:
            // Super moderator
            return $userAccessLevel >= 98; // Użytkownik ma co najmniej poziom dostępu 98
        case 99:
            // Administrator
            return $userAccessLevel >= 99; // Użytkownik ma co najmniej poziom dostępu 99
        default:
            // Nieznany poziom dostępu
            return false;
    }
}

// Funkcja sprawdzająca dostęp do stron związanych z finansami
function sprawdzDostepDoFinansow() {
    // Pobierz nazwę bieżącej strony
    $currentPage = basename($_SERVER['PHP_SELF']);
    
    // Lista stron związanych z finansami
    $stronyFinansowe = array('adminfinance.php');
    
    // Sprawdź, czy bieżąca strona jest stroną związaną z finansami
    return in_array($currentPage, $stronyFinansowe);
}

//------------------------------------------
//------------------------------------------
//------------------------------------------



// Funkcja zapisująca logowanie
function logLoginAttempt($userId, $ip, $userAgent, $correct) {
    global $conn, $prefix;

    // Ustaw bieżącą datę i czas
    $dateTime = date('Y-m-d H:i:s');

    // Przygotuj zapytanie SQL do wstawienia danych do tabeli log_data
    $sql = "INSERT INTO ".PREFIX."log_data (user_id, ip, user_agent, date_time, correct) 
            VALUES ('$userId', '$ip', '$userAgent', '$dateTime', '$correct')";

    // Wykonaj zapytanie SQL
    executeQuery($sql);
}

function randomString($length, $smallletters = true, $capitals = true, $digits = true) {
    $characters = '';
    $characters .= ($smallletters) ? 'abcdefghijklmnopqrstuvwxyz' : '';
    $characters .= ($capitals) ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' : '';
    $characters .= ($digits) ? '0123456789' : '';
    $result = '';
    $charLength = strlen($characters);
    for ($i = 0; $i < $length; $i++) {
        $result .= $characters[rand(0, $charLength - 1)];
    }
    return $result;
}

// Funkcja generująca listę rozwijaną na podstawie danych z tabeli sir_active
function AccessSelectForm($selected=1) {
    // Wykonaj zapytanie SQL, aby pobrać dane z tabeli sir_access
    $sql = "SELECT * FROM " . PREFIX . "access";
    $accesss = fetchAll($sql);
    
    $return ="";
    // Generowanie opcji w rozwijanej liście na podstawie danych z tabeli sir_active
    foreach($accesss as $access) {
        $value = $access['id'];
        $label = $access['name'];

        // Sprawdzenie, czy opcja ma być wybrana (selected)
        $isSelected = ($value == $selected) ? 'selected' : '';

        // Dodanie opcji do rozwijanej listy
        $return .= '<option value="'.$value.'" '.$isSelected.'>'.$label.'</option>';
    }
return $return;
}

function generujRoleCheckbox($selectedRoles = array()) {
    // Pobierz role z bazy danych
    $sql = "SELECT * FROM " . PREFIX . "roles";
    $roles = fetchAll($sql);

    // Jeśli nie ma żadnych ról, zwróć pusty ciąg
    if (!$roles) {
        return '';
    }
    //print_r($roles);
    // Rozpocznij generowanie checkboxów
    $checkboxes = $checked = '';
    foreach ($roles as $role) {
        
        if (!empty($selectedRoles)) $checked = in_array($role['id'], $selectedRoles) ? 'checked' : '';
        $checkboxes .= '<input type="checkbox" name="roles[]" value="' . $role['id'] . '" class="form-check-input" id="flexCheckDefault" '.$checked.'> 
                    <label class="form-check-label" for="flexCheckDefault">'. $role['name'] .'</label><br>';
        
    }

    return $checkboxes;
}


function generateRoleString($userRoles = array()) {
    $rolesArray = array(); // Tablica na nazwy wybranych ról
    
    foreach ($userRoles as $role) {
       // $safeRole = escapeString($role); // Użyj funkcji escapeString z database.php
        $sql = "SELECT * FROM " . PREFIX . "roles WHERE id = '$role'"; // Zapytanie pobierające nazwy ról
        $result = fetchOne($sql);

        $rolesArray[] = $result["name"]; // Dodaj nazwę roli do tablicy
        
    }

    // Wygeneruj ciąg ról oddzielonych przecinkami
    $roleString = implode(', ', $rolesArray);

    return $roleString;
}

function escapeString($string) {
    global $conn; // Zakładam, że zmienna $conn zawiera połączenie z bazą danych

    // Sprawdź, czy połączenie z bazą danych zostało ustanowione
    if (isset($conn)) {
        // Zastosuj odpowiednią funkcję do unikania ataków SQL Injection
        return mysqli_real_escape_string($conn, $string);
    } else {
        // Obsługa błędu, gdy brak połączenia z bazą danych
        echo "Błąd: Brak połączenia z bazą danych.";
        // Zwróć oryginalny ciąg znaków
        return $string;
    }
}


// Sprawdzenie poprawności loginu
function validateLogin($login) {
    $login = escapeString($login);
    // Login może zawierać tylko litery, cyfry i podkreślnik
    if (!preg_match('/^[a-zA-Z0-9_]{5,20}$/', $login)) {
        return -1;
        exit;
    }
    // Sprawdzenie, czy login jest unikatowy w bazie danych
    $sql = "SELECT id FROM " . PREFIX . "users WHERE login = '$login'";
    $result = executeQuery($sql);
    return mysqli_num_rows($result); // Jeśli brak wyników, login jest unikatowy
}

// Sprawdzenie poprawności adresu email
function validateEmail($email) {
    $email = escapeString($email);
    // Sprawdzenie składni adresu email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return -1;
        exit;
    }
    // Sprawdzenie, czy email jest unikatowy w bazie danych
    $sql = "SELECT * FROM " . PREFIX . "users WHERE email = '$email'";
    $result = executeQuery($sql);
    return mysqli_num_rows($result); // Jeśli brak wyników, email jest unikatowy
}

// Sprawdzenie poprawności daty urodzenia (czy osoba ma przynajmniej 15 lat)
function validateDateOfBirth($dateOfBirth) {
    // Sprawdzenie poprawności formatu daty
    $dateObj = DateTime::createFromFormat('Y-m-d', $dateOfBirth);
    if (!$dateObj || $dateObj->format('Y-m-d') !== $dateOfBirth) {
        return false; // Nieprawidłowy format daty
    }

    // Obliczenie różnicy wieku
    $today = new DateTime();
    $diff = $today->diff($dateObj);

    // Sprawdzenie, czy osoba ma wystarczający wiek
    if ($diff->y < MIN_AGE) {
        return false; // Osoba jest za młoda
    }

    return true; // Data urodzenia jest prawidłowa i osoba jest wystarczająco stara
}

function checkString($string, $minLength, $maxLength, $excluded = array(), $letters = true, $digits = true, $specials = true, $strict = false) {
    // Sprawdź, czy podany ciąg ma odpowiednią długość
    $length = strlen($string);
    if ($length < $minLength || $length > $maxLength) {
        return false;
    }

    // Sprawdź, czy ciąg zawiera co najmniej jedną literę, cyfrę lub znak specjalny
    $containsLetter = $letters && preg_match('/[a-zA-Z]/', $string);
    $containsDigit = $digits && preg_match('/\d/', $string);
    $containsSpecial = $specials && ($excluded ? preg_match('/[' . preg_quote(implode('', $excluded), '/') . ']/', $string) : preg_match('/\W/', $string));

    // Sprawdź, czy wymagane są wszystkie kategorie
    if ($strict) {
        return $containsLetter && $containsDigit && $containsSpecial;
    } else {
        // Wymaga co najmniej jednego spełnionego warunku
        return $containsLetter || $containsDigit || $containsSpecial;
    }
}


//-----------------------------------------------------------------------
//-------- FUNKCJE WYSYŁAJĄCE EMAIL -------------------------------------
//-----------------------------------------------------------------------
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendEmail($to, $subject, $msg, $attachments = array()) {
    
    $mail = new PHPMailer(true);

    try {
        // Ustawienia serwera SMTP
        $mail->isSMTP();
        $mail->Host = SMTP_HOST; // Tutaj podaj adres hosta SMTP
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME; // Tutaj podaj nazwę użytkownika SMTP
        $mail->Password = SMTP_PASSWORD; // Tutaj podaj hasło SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Możliwe wartości: 'tls', 'ssl'
        $mail->Port = SMTP_PORT; // Numer portu SMTP

        // Adresy email
        $mail->setFrom('test@sir-check.pl', 'SIR');
        $mail->addAddress($to);

        // Treść wiadomości
        $mail->isHTML(true); // Ustawienie formatu wiadomości jako HTML
        // Ustawienie kodowania znaków
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body = $msg;

        // Załączniki
        foreach ($attachments as $attachment) {
            $mail->addAttachment($attachment);
        }

        // Wysłanie wiadomości
        $mail->send();
        return true;
     } catch (Exception $e) {
        return $mail->ErrorInfo; // Zwróć komunikat błędu w przypadku niepowodzenia
    }
}


//---------------------------------------------------

function showDateInEvent($date, $format, $days = false) {
    // Konwertuj datę na obiekt DateTime
    $dateTime = new DateTime($date);
    
    // Sprawdź różnicę między datą dzisiejszą a datą wydarzenia, jeśli trzeba
    if ($days) {
        $today = new DateTime();
        $interval = $today->diff($dateTime);
        $daysLeft = $interval->days;
        
        // Ustaw wiadomość o dniach pozostałych do wydarzenia, jeśli jest więcej niż 0
        if ($daysLeft > 0) {
            $daysMessage = $daysLeft." dni";
        } elseif ($daysLeft == 1) {
            $daysMessage = 'Jutro';
        } elseif ($daysLeft == 0) {
            $daysMessage = 'Dziś';
        }else {
            $daysMessage = 'ttt';
        }
     
        // Zwróć wiadomość o dniach
        return $daysMessage;
    } else {
        // Utwórz obiekt datefmt
        $fmt = datefmt_create('pl_PL', IntlDateFormatter::FULL, IntlDateFormatter::FULL, 'Europe/Warsaw', IntlDateFormatter::GREGORIAN, $format);
        
        // Sformatuj datę
        $formattedDate = datefmt_format($fmt, $dateTime);
        
        return $formattedDate;
    }
}

function validateAndCompareDates($date1, $date2) {
    
    
    // Sprawdź, czy data1 znajduje się w przyszłości w stosunku do dzisiejszej daty

        $today = new DateTime();
        $date1Obj = new DateTime($date1);
        if ($date1Obj < $today) {
            return 3;
            exit;
        }
    
    
    // Sprawdź, czy daty są w formacie poprawnym dla funkcji strtotime
    if (!strtotime($date1) || !strtotime($date2)) {
        return 1;exit;
    }

    // Sprawdź, czy data numer 1 jest wcześniejsza niż data numer 2
    if (strtotime($date1) >= strtotime($date2)) {
        return 2;exit;
    }


    // Wszystkie warunki są spełnione - daty są poprawne i data numer 1 jest wcześniejsza
    return 0;
}

function generujUserCheckbox($dbField,$selectedUsers = array()) {
    // Pobierz role z bazy danych
    $sql = "SELECT * FROM " . PREFIX . "users ORDER BY last_name, first_name";
    $users = fetchAll($sql);

    // Jeśli nie ma żadnych ról, zwróć pusty ciąg
    if (!$users) {
        return '';
    }
    //print_r($roles);
    // Rozpocznij generowanie checkboxów
    $checkboxes = $checked = '';
    foreach ($users as $user) {

        if (!empty($selectedUsers)) $checked = in_array($user['id'], $selectedUsers) ? 'checked' : '';
        $checkboxes .= '<input type="checkbox" name="'.$dbField.'[]" value="' . $user['id'] . '" class="form-check-input toggleCheckbox" id="flexCheckDefault" '.$checked.'> 
                    <label class="form-check-label" for="flexCheckDefault">'. $user['last_name'] .' '. $user['first_name'] .'</label><br>';
        
    }

    return $checkboxes;
}

function setJson($json, $set = array(), $add = true) {
    // Decode JSON string to associative array
    $data = json_decode($json, true);

    // If $add is true, add or update key-value pairs
    if ($add) {
        foreach ($set as $key => $value) {
            $data[] = $value;
        }
    } else {
        // If $add is false, remove specified keys
        foreach ($set as $key) {
            if (($index = array_search($key, $data)) !== false) {
                unset($data[$index]);
            }
        }
        // Reindex array after removal
        $data = array_values($data);
    }

    // Encode array back to JSON
    $newJson = json_encode($data);

    return $newJson;
}


function checkJson($json, $val) {
    // Decode JSON string to associative array
     if ($json === null) {
        return false;
    }

    $string = json_decode($json, true);

    // Check if the user ID is present in the interests array
    return in_array($val, $string);
}

function getUserInfo($userId) {
    // Tutaj wykonaj zapytanie SQL, aby pobrać informacje o użytkowniku na podstawie jego ID
    // Poniżej znajduje się przykładowe zapytanie SQL, które należy dostosować do swojej bazy danych
    $sql = "SELECT * FROM " . PREFIX . "users WHERE id = $userId";
    // Zwróć dane użytkownika
    return fetchOne($sql);
}

function getEventInfo($eventId) {
    // Tutaj wykonaj zapytanie SQL, aby pobrać informacje o użytkowniku na podstawie jego ID
    // Poniżej znajduje się przykładowe zapytanie SQL, które należy dostosować do swojej bazy danych
    $sql = "SELECT * FROM " . PREFIX . "event WHERE id = $eventId";
    // Zwróć dane użytkownika
    return fetchOne($sql);
}


function countUnreadMessages($userId) {
  $read_status='["1","0"]';
  $sql = "SELECT * FROM " . PREFIX . "chat WHERE (sender_id = ".$userId." OR receiver_id = ".$userId.") AND read_status='".$read_status."'";
  //echo "SQL: ".$sql;
    // Zwróć dane użytkownika
    $result=executeQuery($sql);  
    
    return mysqli_num_rows($result);
}

function readThread($thread_id) {
    $sql = "SELECT * FROM ".PREFIX."chat WHERE thread_id=".$thread_id." ORDER BY timestamp DESC";
    return fetchOne($sql);  
}

function readAllThreads($thread_id) {
    $sql = "SELECT * FROM ".PREFIX."chat WHERE thread_id=".$thread_id." ORDER BY timestamp DESC";
    return fetchAll($sql);  
}   

function generateThreadId() {
    // Utwórz unikalny thread_id
    $threadId = randomString(30,0,0,1);

    // Sprawdź, czy thread_id jest unikalne
    $query = "SELECT COUNT(*) as count FROM ".PREFIX."chat WHERE thread_id = '$threadId'";
    $result = executeQuery($query);
    $row = mysqli_fetch_assoc($result);
    $count = $row['count'];

    // Jeśli thread_id już istnieje, wygeneruj nowy
    if ($count > 0) {
        return generateThreadId();
    }

    return $threadId;
}

function getOptions($option, $swap = array()) {
    // Przyłącz plik z konfiguracją bazy danych lub utwórz połączenie z bazą danych

    // Wykonaj zapytanie SQL, aby pobrać dane z tabeli sir_options dla określonej opcji
    $sql = "SELECT value FROM sir_options WHERE name = '$option'";
    $result = executeQuery($sql); // Zakładając, że masz funkcję executeQuery() do wykonywania zapytań SQL

    // Przygotuj zmienną na wartość opcji
    $optionValue = '';

    // Sprawdź, czy wynik zapytania nie jest pusty
    if ($result && mysqli_num_rows($result) > 0) {
        // Pobierz wartość opcji z wyniku zapytania
        $row = mysqli_fetch_assoc($result);
        $optionValue = $row['value'];

        // Sprawdź, czy przekazano mapowanie zmiennych, jeśli tak, zastosuj je
        if (!empty($swap)) {
            foreach ($swap as $key => $value) {
                $optionValue = str_replace("[[$key]]", $value, $optionValue);
            }
        }
    }

    // Zwróć wartość opcji
    return $optionValue;
}


?>
