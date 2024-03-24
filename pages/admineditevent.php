 <?php
 // Tutaj dodaj kod do sprawdzenia uprawnień użytkownika, np. czy ma dostęp do tej strony
if (!checkUserAccess(97)) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: login.html");
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST") {
    // Sprawdź, czy przekazano ID użytkownika w adresie URL
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $eventId = $_GET['id'];
        
               // Przykładowe zapytanie SQL (pamiętaj o zabezpieczeniu przed atakami SQL Injection)
    $sql = "SELECT * FROM ".PREFIX."event WHERE id = $eventId";
    $event = fetchOne($sql); 
        
    }       
}    

// Obsługa formularza
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Pobranie danych z $_POST i zastosowanie funkcji validateInput
        if (!checkString($_POST["name"], 10, 100,array(),1,1,1,0)) $errors['name'] = 'Twoja nazwa jest niepoprawna.';
        $event["name"] = $_POST["name"] ; 
        
        if (isset($_POST["details"])) $event["details"] = escapeString($_POST["details"]);
        else $event["details"] = "";
    
        if (validateAndCompareDates($_POST["start_date_time"],$_POST["end_date_time"] )==1) $errors['date'] = 'Twoje daty są nieprawidłowe.';
        elseif (validateAndCompareDates($_POST["start_date_time"], $_POST["end_date_time"] )==2) $errors['date'] = 'Data rozpoczęcia zawsze musi być wcześniejsza niż zakończenia.';
        elseif (validateAndCompareDates($_POST["start_date_time"], $_POST["end_date_time"] )==3) $errors['date'] = 'Twoja data jest w przeszłości.';
        
        if ($_POST["type"]!=0) $event["type"] = $_POST["type"]; else $errors['type'] = 'Wybierz z listy';
        
        $event["start_date_time"]=$_POST["start_date_time"] ;
        $event["end_date_time"]=$_POST["end_date_time"] ;
        
        $event["created_by"] = $event["update_by"] = $_SESSION['user_id'];
        
        if (empty($_POST["invite"])) $errors['invite'] = 'Zaproś kogoś!';
        else $event["invite"] = json_encode($_POST["invite"]);
        
        $event["active"] = 1 ; 
    

    // Walidacja pól formularza (możesz dodać więcej warunków)
    if (!empty($errors)) {
        
        $error = "Wszystkie pola oznaczone gwiazdką są wymagane!";
        
    } else {
        if(isset($_POST['event_id']) && is_numeric($_POST['event_id'])) {
        // Pobierz dane przesłane z formularza
        $event_id = $_POST['event_id'];
        // Pobierz pozostałe dane z formularza i przetwórz je

        // Tutaj możesz wykonać zapytanie SQL, aby zaktualizować dane wydarzenia o podanym ID w bazie danych
        // Pamiętaj o zabezpieczeniu przed atakami SQL Injection
 //`id`, `name`, `active`, `start_date_time`, `end_date_time`, `details`, `invite`, `interest`, `attend`, `created_by`, `created_date`, `update_by`, `update_date`        
        $update_sql="";
        
        if (isset($_POST['name'])) $update_sql .="name= '".$_POST["name"]."',";
        if (isset($_POST['active'])) $update_sql .="active= '".$_POST["active"]."',";
         if (isset($_POST['details'])) $update_sql .="details= '".$_POST["details"]."',";
        if (isset($_POST['start_date_time'])) $update_sql .="start_date_time= '".$_POST["start_date_time"]."',";
        if (isset($_POST['end_date_time'])) $update_sql .="end_date_time= '".$_POST["end_date_time"]."',";
        if (isset($_POST['invite'])) $update_sql .="invite= '".json_encode($_POST['invite'])."',";
        if (isset($_POST['type'])) $update_sql .="type= '".$_POST["type"]."',";
        
        
       // echo "SQL : ".$update_sql;
        if (!empty($update_sql)) {
                      $update_sql .=" update_by= '".$_SESSION["user_id"]."',update_date= NOW()";
       
                    // Przykładowe zapytanie SQL
                    $sql = "UPDATE ".PREFIX."event SET ".$update_sql." WHERE id = $event_id";
                    executeQuery($sql);

        // Po zaktualizowaniu danych przekieruj użytkownika na stronę wyświetlającą informacje o sukcesie
        header("Location: /adminevents.html");
        exit(); 
        
        }
    }
    }
}
?>

<h1>Edycja wydarzenia</h1>
    
<?php include ('forms/eventForm.php'); ?>
<script>
            // Pobierz referencję do pierwszego checkboxa
        var mainCheckbox = document.getElementById("mainCheckbox");

        // Pobierz referencje do wszystkich pozostałych checkboxów
        var toggleCheckboxes = document.querySelectorAll(".toggleCheckbox");

        // Dodaj nasłuchiwacz zdarzeń, który będzie wykonywał funkcję, gdy stan pierwszego checkboxa zmieni się
             mainCheckbox.addEventListener("change", function() {
            // Dla każdego checkboxa, ustaw jego stan na taki sam, jak pierwszy checkbox
            toggleCheckboxes.forEach(function(checkbox) {
                checkbox.checked = mainCheckbox.checked;
            });
        });
</script>
