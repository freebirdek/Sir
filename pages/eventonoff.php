  <?php
 // Tutaj dodaj kod do sprawdzenia uprawnień użytkownika, np. czy ma dostęp do tej strony
if (!checkUserAccess(2)) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: login.html");
    exit();
} 

$user_id=$_SESSION['user_id'];

if($_SERVER["REQUEST_METHOD"] == "GET") {
    // Sprawdź, czy przekazano ID użytkownika w adresie URL
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $eventId = $_GET['id'];
        
               // Przykładowe zapytanie SQL (pamiętaj o zabezpieczeniu przed atakami SQL Injection)
    $sql = "SELECT * FROM ".PREFIX."event WHERE id = ".$eventId;
    $event = fetchOne($sql); 
        
    
    if (checkJson($event["invite"], $user_id)) {
        
            if (checkJson($event["interest"], $user_id)) { // jeżeli pozycja istnieje 
                
                $interest=setJson($event["interest"], ["$user_id"], false); // usuń pozycję
                
               // echo "USUWANIE: ".$interest;
                
                $sql = "UPDATE ".PREFIX."event SET interest='".$interest."', update_by='".$_SESSION["user_id"]."', update_date=NOW() WHERE id = ".$event["id"];
                
                // Wykonaj zapytanie do bazy danych
                executeQuery($sql);               
                
            } else {
                //jeżeli pozycja nie istnieje 
                
                 $interest=setJson($event["interest"], array($user_id), true);
                
                // echo "DODAWANIE: ".$interest;
                 
                $sql = "UPDATE ".PREFIX."event SET interest='".$interest."', update_by='".$_SESSION["user_id"]."', update_date=NOW() WHERE id = ".$event["id"];
                // Wykonaj zapytanie do bazy danych
                executeQuery($sql);               
               
                
                
            }
    }
    echo $sql;
    header("Location: /calendar.html");
    exit();
    
    }       
}   
