 <?php
 // Tutaj dodaj kod do sprawdzenia uprawnień użytkownika, np. czy ma dostęp do tej strony
if (!checkUserAccess(98)) {
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
    
        if (empty($_POST["attend"])) $errors['attend'] = 'Zaznacz kogoś!';
        else $event["attend"] = json_encode($_POST["attend"]);
    

        
        
        
    // Walidacja pól formularza (możesz dodać więcej warunków)
    if (!empty($errors)) {
        
        echo "Zaznacz kogoś";
        
    } else {
        if(isset($_POST['event_id']) && is_numeric($_POST['event_id'])) {
        // Pobierz dane przesłane z formularza
        $event_id = $_POST['event_id'];
        // Pobierz pozostałe dane z formularza i przetwórz je

        // Tutaj możesz wykonać zapytanie SQL, aby zaktualizować dane wydarzenia o podanym ID w bazie danych
        // Pamiętaj o zabezpieczeniu przed atakami SQL Injection
 //`id`, `name`, `active`, `start_date_time`, `end_date_time`, `details`, `invite`, `interest`, `attend`, `created_by`, `created_date`, `update_by`, `update_date`        
        $update_sql="";
        
        if (isset($_POST['finishCheck'])) $update_sql .= "close= '1',";

        if (isset($_POST['attend'])) $update_sql .= "attend= '".json_encode($_POST['attend'])."',";


       // echo "SQL : ".$update_sql;
        if (!empty($update_sql) || !in_array('-1', $_POST['attend'])) {
            echo "TEST2";
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
<h1><?=$event["name"]?></h1>
<h5><a href="/adminevents.html">wróć do listy</a></h5>
<div class="container">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Zaproszeni</h2>
            <form action="" method="post">
            <input type="hidden" name="event_id" value="<?= (isset($event['id'])) ? $event['id'] : ''; ?>">
                <?= generujUserCheckbox('attend',(isset($event['attend'])) ? json_decode($event['attend']): ''); ?>
                <input type="checkbox" id="finishCheck" name="finishCheck">
        <label for="finishCheck">Czy chcesz zakończyć sprawdzanie obecności?</label>

         <input type="submit" value="Wyślij dane"  class="btn btn-primary">
    </form>
        </div>
    </div>
</div>

