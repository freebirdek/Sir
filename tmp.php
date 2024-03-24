<?php
// Tutaj dodaj kod do sprawdzenia uprawnień użytkownika, np. czy ma dostęp do tej strony
if (!checkUserAccess(98)) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: login.html");
    exit();
}


//`id`, `login`, `email`, `first_name`, `last_name`, `phone`, `password`, `salt`, `date_of_birth`, `roles`, `active`, `details`, `qr`, `created_by`, `created_date`, `update_by`, `update_date`

if($_SERVER["REQUEST_METHOD"] == "GET") {
    // Sprawdź, czy przekazano ID użytkownika w adresie URL
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $userId = $_GET['id'];
        
        // Tutaj możesz wykonać zapytanie SQL, aby pobrać dane użytkownika o podanym ID
        // Możesz także użyć funkcji do pobrania danych z bazy danych zdefiniowanej wcześniej
        
        // Przykładowe zapytanie SQL (pamiętaj o zabezpieczeniu przed atakami SQL Injection)
         $sql = "SELECT * FROM ".PREFIX."users WHERE id = $userId";
         $user = fetchOne($sql);
        
         // Przykładowe zapytanie SQL (pamiętaj o zabezpieczeniu przed atakami SQL Injection)
         $sql = "SELECT * FROM ".PREFIX."roles";
         $roles = fetchAll($sql);
       
         
        // Jeśli użytkownik o podanym ID istnieje, możesz wyświetlić formularz edycji
        if($user) {
?>

    <h1>Edycja danych użytkownika</h1>
    <form action="<?=$_GET['id']?>.html" method="post">
         <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
        
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default">Login *</span>
            <input type="text" class="form-control" name="login" aria-describedby="inputGroup-sizing-default" value="<?php echo $user['login']; ?>">
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default">Email *</span>
            <input type="text" class="form-control" name="email" aria-describedby="inputGroup-sizing-default" value="<?php echo $user['email']; ?>">
        </div>        
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default">Imię *</span>
            <input type="text" class="form-control" name="first_name" aria-describedby="inputGroup-sizing-default" value="<?php echo $user['first_name']; ?>">
        </div> 
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default">Nazwisko *</span>
            <input type="text" class="form-control" name="last_name" aria-describedby="inputGroup-sizing-default" value="<?php echo $user['last_name']; ?>">
        </div> 
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default">Numer telefonu *</span>
            <input type="text" class="form-control" name="phone" aria-describedby="inputGroup-sizing-default" value="<?php echo $user['phone']; ?>">
        </div>                
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default">Data urodzenia *</span>
            <input type="date" class="form-control" name="date_of_birth" aria-describedby="inputGroup-sizing-default" value="<?php echo $user['date_of_birth']; ?>">
        </div>         
        
       <h4>Rola w stowarzyszeniu</h4>
        <div class="form-check">
         <?= generujRoleCheckbox(json_decode($user['roles'])); ?>
        </div>
        <h4>Dostęp</h4>
        <div class="input-group mb-3">
            <select class="form-select input-group-text" aria-label="Default select example" name="access">
                <option selected>Wybierz opcję</option>
                <?= ActiveSelectForm($user['access']);?>
            </select>
      </div>  
        
        <h4>Notatka</h4>
        <div class="form-floating">
                <textarea class="form-control" placeholder="Zostaw notatkę" id="floatingTextarea2"></textarea>
        </div>


   <div class="mt-5">
        <input type="submit" value="Edytuj użytkownika"  class="btn btn-primary">
  </div>
    </form>
<?php
        } else {
            // Jeśli użytkownik o podanym ID nie istnieje, możesz wyświetlić komunikat o błędzie
            echo "Użytkownik o podanym ID nie istnieje.";
        }
    } else {
        // Jeśli nie przekazano ID użytkownika w adresie URL, możesz przekierować użytkownika lub wyświetlić komunikat
        echo "Brak ID użytkownika w adresie URL.";
    }
} elseif($_SERVER["REQUEST_METHOD"] == "POST") {
    // Przetwarzanie formularza edycji danych użytkownika po przesłaniu danych POST

    // Sprawdź, czy przekazano ID użytkownika
    if(isset($_POST['user_id']) && is_numeric($_POST['user_id'])) {
        // Pobierz dane przesłane z formularza
        $user_id = $_POST['user_id'];
        // Pobierz pozostałe dane z formularza i przetwórz je

        // Tutaj możesz wykonać zapytanie SQL, aby zaktualizować dane użytkownika o podanym ID w bazie danych
        // Pamiętaj o zabezpieczeniu przed atakami SQL Injection
//`id`, `login`, `email`, `first_name`, `last_name`, `phone`, `password`, `salt`, `date_of_birth`, `roles`, `active`, `details`, `qr`, `created_by`, `created_date`, `update_by`, `update_date`
        
        $update_sql="";
        
        if (isset($_POST['login'])) $update_sql .="login= '".validateInput($_POST["login"])."',";
        if (isset($_POST['email'])) $update_sql .="email= '".validateInput($_POST["email"])."',";
        if (isset($_POST['first_name'])) $update_sql .="first_name= '".validateInput($_POST["first_name"])."',";
        if (isset($_POST['last_name'])) $update_sql .="last_name= '".validateInput($_POST["last_name"])."',";
        if (isset($_POST['phone'])) $update_sql .="phone= '".validateInput($_POST["phone"])."',";
        if (isset($_POST['date_of_birth'])) $update_sql .="date_of_birth= '".validateInput($_POST["date_of_birth"])."',";
        if (isset($_POST['roles'])) $update_sql .="roles= '".json_encode($_POST['roles'])."',";
        if (isset($_POST['access'])) $update_sql .="access= '".validateInput($_POST['access'])."',";
        if (isset($_POST['details'])) $update_sql .="details= '".validateInput($_POST["details"])."',";
      
        
        
       // echo "SQL : ".$update_sql;
        if (!empty($update_sql)) {
                      $update_sql .=" update_by= '".$_SESSION["user_id"]."',update_date= NOW()";
       
                    // Przykładowe zapytanie SQL
                    $sql = "UPDATE ".PREFIX."users SET ".$update_sql." WHERE id = $user_id";
                    executeQuery($sql);

        // Po zaktualizowaniu danych przekieruj użytkownika na stronę wyświetlającą informacje o sukcesie
        header("Location: /adminusers.html");
        exit(); 
            
        }
    } else {
        // Jeśli nie przekazano ID użytkownika, wyświetl komunikat błędu
        echo "Błąd: Brak ID użytkownika.";
    }
}
?>
