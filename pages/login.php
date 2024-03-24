<?php
if (isset($_SESSION['user_id'])) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: home.html");
    exit();
}

// Obsługa logowania
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {

    // Zabezpiecz dane wejściowe przed atakami SQL Injection
    $login = escapeString($_POST['login']);
    $password = escapeString($_POST['password']);
    
    // Pobierz użytkownika z bazy danych
    $sql = "SELECT * FROM ".PREFIX."users WHERE email = '$login'";
    //echo "SQL: ".$sql;
    $user = fetchOne($sql);

    if ($user) {
        // Wygeneruj skrót SHA-1 z hasła i soli użytkownika
        $hashed_password = sha1($password . $user['salt']);
        $ip = $_SERVER['REMOTE_ADDR']; // Adres IP użytkownika
        $userAgent = $_SERVER['HTTP_USER_AGENT']; // Agent użytkownika

        // Sprawdź, czy podane hasło jest zgodne z hasłem w bazie danych
        if ($hashed_password === $user['password']) {
            // Zaloguj użytkownika
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['access'] = $user['access'];
            logLoginAttempt($user['id'], $ip, $userAgent, '1');
            // Przekieruj na stronę główną lub inną po zalogowaniu
            header("Location: /");
            exit();
        } else {
            logLoginAttempt($user['id'], $ip, $userAgent, '0');
            $error = "Nieprawidłowy login lub hasło.";
        }
    } else {
        //logLoginAttempt($userId, $ip, $userAgent, '0');
        $error = "Nieprawidłowy login lub hasło.";
    }
}

?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mt-5 mb-4 text-center">Zaloguj się</h2>
            <?php if(isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="login" class="form-label">Email:</label>
                    <input type="text" class="form-control" id="login" name="login" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Hasło:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary d-block mx-auto">Zaloguj się</button>
            </form>
            <p class="mt-3">Jeżeli nie masz jeszcze konta to skontaktuj się bezpośrednio ze stowarzyszeniem Szukamy i Ratujemy.</p>
            <p>Nie pamiętasz hasła? <a href="forgotpassword.html">Odzyskaj je tutaj</a>.</p>
        </div>
    </div>
</div>

