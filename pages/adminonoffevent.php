 <?php
 // Tutaj dodaj kod do sprawdzenia uprawnień użytkownika, np. czy ma dostęp do tej strony
if (!checkUserAccess(97)) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: login.html");
    exit();
} 



if($_SERVER["REQUEST_METHOD"] == "GET") {
    // Sprawdź, czy przekazano ID użytkownika w adresie URL
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $eventId = $_GET['id'];
        
               // Przykładowe zapytanie SQL (pamiętaj o zabezpieczeniu przed atakami SQL Injection)
    $sql = "SELECT * FROM ".PREFIX."event WHERE id = ".$eventId;
    $event = fetchOne($sql); 
        
    $sql = "UPDATE ".PREFIX."event SET active = CASE WHEN active = 0 THEN 1 ELSE 0 END, update_by='".$_SESSION["user_id"]."', update_date=NOW() WHERE id = ".$event["id"];
    // Wykonaj zapytanie do bazy danych
    executeQuery($sql);
    header("Location: /adminevents.html");
    exit();
    
    }       
}   

