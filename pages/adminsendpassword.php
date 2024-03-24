<?php

if (!checkUserAccess(97)) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: login.html");
    exit();
}

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // Pobierz ID użytkownika z parametru GET
        $userId = $_GET['id'];

        // Sprawdź, czy przekazano poprawne ID użytkownika
        if (isset($userId) && is_numeric($userId)) {
            
            
               // Przykładowe zapytanie SQL (pamiętaj o zabezpieczeniu przed atakami SQL Injection)
            $sql = "SELECT * FROM ".PREFIX."users WHERE id = $userId";
            $user = fetchOne($sql); 

            // Sprawdź, czy użytkownik istnieje w bazie danych
            if ($user) {
                // Wyświetl formularz potwierdzenia wysłania hasła
                $fullName = $user['first_name'] . ' ' . $user['last_name'];

                ?>
                <p>Czy na pewno chcesz wysłać nowe hasło do użytkownika <?php echo $fullName; ?>?</p>
                <form action="" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                    <button type="submit" name="submit">Tak, wyślij</button>
                    <a href="/adminusers.html">Nie, wyjdź stąd</a>
                </form>
                <?php
            } else {
                echo '<p>Nie znaleziono użytkownika o podanym ID.</p>';
            }
        } else {
            echo '<p>Błędne ID użytkownika.</p>';
        }
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        if (isset($_POST['user_id']) && is_numeric($_POST['user_id'])) {
            
        $sql = "SELECT * FROM ".PREFIX."users WHERE id = ".$_POST['user_id'];
        $user = fetchOne($sql);
        
        
        $sql = "SELECT * FROM ".PREFIX."users WHERE id = ".$_SESSION["user_id"];
        $loggedUser = fetchOne($sql); 
            
        $newSalt=randomString(4);    
        $newPassword=randomString(10);
        
        
            // Tutaj umieść kod do wysłania hasła, np. wywołanie funkcji sendEmail()
            $subject = '[NOWE HASŁO] Administrator '.$site_name.' wysłał ci nowe hasło';
            $msg = '<h2>Hej '.$user['first_name'].'!</h2>';
            $msg.= '<p>Administrator '.$site_name.' wygenerował ci nowe hasło.</p>';
            $msg.= '<p>Twoje nowe hasło od tej chwili to:</p>';
            $msg.= '<h4>'.$newPassword.'</h4>';
            $msg.= '<p>Od tego momentu masz możliwość zalogowania się tylko z tym hasłem.</p>';
            $msg.= "<p style=\"color:#FF0000\">Zaloguj się na swoje konto i zmień je według własnego uznania.</p>";
            $msg.= '<p>Pozdrawiam serdecznie</p>';
            $msg.= '<p>'.$loggedUser['first_name'].' '.$loggedUser['last_name'].'</p>';

            
           $result = sendEmail($user["email"], $subject, $msg) ;
               
           if ($result === true) {
               
                $sql = "UPDATE ".PREFIX."users SET password='".sha1($newPassword.$newSalt)."', salt='".$newSalt."', update_by='".$loggedUser["id"]."', update_date=NOW() WHERE id = ".$user["id"];
                executeQuery($sql);
                
                echo 'Email został wysłany.  <a href="/adminusers.html">Kliknij tutaj</a>'; 
                
            } else {
                echo 'Wystąpił błąd podczas wysyłania emaila: ' . print_r($result);
            } 
            
        } else {
            echo '<p>Błędne dane przesłane z formularza.</p>';
        }
    }
    ?>

