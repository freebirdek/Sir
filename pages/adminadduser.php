 <?php
 // Tutaj dodaj kod do sprawdzenia uprawnień użytkownika, np. czy ma dostęp do tej strony
if (!checkUserAccess(98)) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: login.html");
    exit();
}

// Obsługa formularza
if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (validateEmail($_POST["email"]) == -1) $errors['email'] = 'Zły email.';
        elseif ((validateEmail($_POST["email"]) > 0)) $errors['email'] = 'Taki email już istnieje.';
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
    
        $user["salt"] = randomString(4,0,0,1);
        $user["qr"] = randomString(150);
        $password=randomString(10,1,1,0); 
        $user["password"] =sha1($password.$user["salt"]);
        $user["created_by"] = $user["update_by"] = $_SESSION['user_id'];
        
        if (empty($_POST["roles"])) $errors['roles'] = 'Wybierz chociaż jedną rolę.';
        else $user["roles"] = json_encode($_POST["roles"]);
        

    // Walidacja pól formularza (możesz dodać więcej warunków)
    if (!empty($errors)) {
        
        $error = "Wszystkie pola oznaczone gwiazdką są wymagane!";
        
    } else {
        // Dodanie użytkownika do bazy danych
        //`id`, `login`, `email`, `first_name`, `last_name`, `phone`, `password`, `salt`, `date_of_birth`, `roles`, `access`, `details`, `qr`, `created_by`, `created_date`, `update_by`, `update_date`
        
       // Złożenie danych w tablicę asocjacyjną
        if (insert('users', $user)) {
                
                $new_user_email_content_swap=array('first_name'=>$user["first_name"], 'site_name'=>$site_name, 'site_url'=>$site_url, 'user_login'=>$user["login"], 'user_password'=>$password, 'admin_email'=>$admin_email);
                $new_user_email_subject_swap=array('site_name'=>$site_name);

                sendEmail($user["email"], getOptions('new_user_email_subject'), getOptions('new_user_email_content',$new_user_email_content_swap));
            
                header("Location: adminusers.html");
                exit();
        } else {
            $error = "Wystąpił problem podczas dodawania użytkownika!";
        }
    }
}
?>

<h1>Dodawanie nowego użytkownika</h1>
    
<?php include ('forms/userForm.php'); ?>
