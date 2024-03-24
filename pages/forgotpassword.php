 <?php
// Sprawdź, czy formularz został przesłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobierz adres e-mail z formularza
    $email = $_POST["email"];
    $error=$success="";
    // Sprawdź poprawność adresu e-mail
    if (validateEmail($email)=="-1") {
        $error .='Podano nieprawidłowy adres e-mail.';
    } else {
        // Generuj nowe hasło
        $newPassword = randomString(8);
        $newsalt = randomString(4,1,1,0);
        
        // Zaktualizuj hasło w bazie danych
        $hashedPassword = sha1($newPassword.$newsalt);
        
         $sql = "SELECT * FROM " . PREFIX . "users WHERE email = '$email'";
         $user = fetchOne($sql);
        
        if (!empty($user)) {
            
        $sqlUpdate = "UPDATE ".PREFIX."users SET password = '$hashedPassword', salt='$newsalt' WHERE email = '$email'";
        executeQuery($sqlUpdate);
        
        // Wyślij nowe hasło e-mailem
        $subject = getOptions('forgot_password_email_subject',array("site_name"=>$site_name));
        
        $options=array("password"=>$newPassword);
        $msg = getOptions('forgot_password_email_content',$options);
        
            if (sendEmail($email, $subject, $msg, $attachments = array())) {
                $success .='Nowe hasło zostało wysłane na Twój adres e-mail.';
            } else {
               $error .='Wystąpił błąd podczas wysyłania nowego hasła.';
            }
            
        } else $success .='Nowe hasło zostało wysłane na Twój adres e-mail.';
    }
}
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mt-5 text-center">Odzyskiwanie hasła</h2>
            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger mt-4" role="alert">
                    <?= $error ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($success)) : ?>
                <div class="alert alert-success mt-4" role="alert">
                    <?= $success ?>
                </div>
            <?php endif; ?>
            <form class="mt-4" method="post" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">Podaj adres e-mail:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary btn-sm d-block mx-auto">Resetuj hasło</button>
            </form>
        </div>
    </div>
</div>




