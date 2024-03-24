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
        $userId = $_GET['id'];
        
               // Przykładowe zapytanie SQL (pamiętaj o zabezpieczeniu przed atakami SQL Injection)
    $sql = "SELECT * FROM ".PREFIX."users WHERE id = $userId";
    $user = fetchOne($sql); 
        
    }       
}        
        

// Obsługa formularza
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Pobranie danych z $_POST i zastosowanie funkcji validateInput
    if ($_POST["login"]!=$user["login"])
        if (validateLogin($_POST["login"]) == -1) $errors['login'] = 'Zły login. Login może zawierać tylko litery i cyfry i mieć od 5 do 20 znaków';
        elseif ((validateLogin($_POST["login"]) > 0)) $errors['login'] = 'Taki login już istnieje.';
        $user["login"] = $_POST["login"] ;
     
    if ($_POST["email"]!=$user["email"])
        if (validateEmail($_POST["email"]) == -1) $errors['email'] = 'Zły email.';
        elseif ((validateLogin($_POST["email"]) > 0)) $errors['email'] = 'Taki email już istnieje.';
        $user["email"] = $_POST["email"] ;                
        
        if (!checkString($_POST["first_name"], 2, 20, array(" ") ,1, 0, 0)) $errors['first_name'] = 'Twoje imię zostało źle wpisane.';
        $user["first_name"] = $_POST["first_name"] ; 
                
        if (!checkString($_POST["last_name"], 2, 60, array("-"," "), 1, 0, 0)) $errors['last_name'] = 'Twoje nazwisko zostało źle wpisane.';
        $user["last_name"] = $_POST["last_name"] ;                 
        
        if (!checkString($_POST["phone"], 9, 9, 0, 0, 1, 0)) $errors['phone'] = 'Telefon może zawierać tylko 9 cyfr.';
        $user["phone"] = $_POST["phone"] ;
        
        if (!validateDateOfBirth($_POST["date_of_birth"])) $errors['date_of_birth'] = 'Wiek poniżej '.MIN_AGE.' lat';
        $user["date_of_birth"] = $_POST["date_of_birth"] ;  
        
        if ($_POST["access"]==0)  $errors['access'] = 'Wybierz z listy.';
        $user["access"] = $_POST["access"] ;
        
        if (isset($_POST["details"])) $user["details"] = escapeString($_POST["details"]);
        else $user["details"] = "";
    
        $user["created_by"] = $user["update_by"] = $_SESSION['user_id'];
        
      if ($_POST["roles"]!=$user["roles"])  
        if (empty($_POST["roles"])) $errors['roles'] = 'Wybierz chociaż jedną rolę.';
        else $user["roles"] = json_encode($_POST["roles"]);

    // Walidacja pól formularza (możesz dodać więcej warunków)
    if (!empty($errors)) {
        
        $error = "Wszystkie pola oznaczone gwiazdką są wymagane!";
        
    } else {

        if(isset($_POST['user_id']) && is_numeric($_POST['user_id'])) {
        // Pobierz dane przesłane z formularza
        $user_id = $_POST['user_id'];
        // Pobierz pozostałe dane z formularza i przetwórz je

        // Tutaj możesz wykonać zapytanie SQL, aby zaktualizować dane użytkownika o podanym ID w bazie danych
        // Pamiętaj o zabezpieczeniu przed atakami SQL Injection
//`id`, `login`, `email`, `first_name`, `last_name`, `phone`, `password`, `salt`, `date_of_birth`, `roles`, `active`, `details`, `qr`, `created_by`, `created_date`, `update_by`, `update_date`
        
        $update_sql="";
        
        if (isset($_POST['login'])) $update_sql .="login= '".$_POST["login"]."',";
        if (isset($_POST['email'])) $update_sql .="email= '".$_POST["email"]."',";
        if (isset($_POST['first_name'])) $update_sql .="first_name= '".$_POST["first_name"]."',";
        if (isset($_POST['last_name'])) $update_sql .="last_name= '".$_POST["last_name"]."',";
        if (isset($_POST['phone'])) $update_sql .="phone= '".$_POST["phone"]."',";
        if (isset($_POST['date_of_birth'])) $update_sql .="date_of_birth= '".$_POST["date_of_birth"]."',";
        if (isset($_POST['roles'])) $update_sql .="roles= '".json_encode($_POST['roles'])."',";
        if (isset($_POST['access'])) $update_sql .="access= '".$_POST['access']."',";
        if (isset($_POST['details'])) $update_sql .="details= '".$_POST["details"]."',";
      
        
        
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
    }
    }
}
?>

<h1>Edytuj użytkownika</h1>
    
<?php include ('forms/userForm.php'); ?>
