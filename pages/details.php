<?php 
if (!checkUserAccess(2)) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: login.html");
    exit();
}

// Pobierz identyfikator użytkownika z sesji
$userId = $_SESSION['user_id'];

// Pobierz informacje o użytkowniku z bazy danych
$sql = "SELECT * FROM " . PREFIX . "users WHERE id = $userId";
$userInfo = fetchOne($sql);

$error="";
// Sprawdź, czy formularz został przesłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobierz dane z formularza
    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];
    $salt=$userInfo["salt"];
    $newSalt=randomString(4, 1, 1, 1);
    
    // Waliduj nowe hasło
    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        $error .= "Proszę wypełnić wszystkie pola. <br>";
    } elseif (!checkString($new_password, 8, 50, '')) {
        $error .= "Nowe hasło musi mieć co najmniej 8 znaków, zawierać co najmniej jedną cyfrę i jedną wielką literę.<br>";
    } elseif ($new_password !== $confirm_password) {
        $error.= "Nowe hasło i potwierdzenie hasła nie pasują do siebie.<br>";
    } else {
        // Pobierz aktualne hasło użytkownika z bazy danych
        $user_id = $_SESSION['user_id'];
        $current_password = $userInfo["password"]; // Załóżmy, że taka funkcja istnieje

        // Sprawdź, czy stare hasło jest poprawne
        if ($current_password===sha1($old_password.$salt)) {
            // Zmiana hasła
            
            $hashed_password = sha1($new_password.$newSalt);
            
            $update_sql="password ='".$hashed_password."',salt='".$newSalt."',";
            $update_sql .="update_by= '".$_SESSION["user_id"]."',update_date= NOW()";
            
            
            $sql = "UPDATE ".PREFIX."users SET ".$update_sql." WHERE id = $user_id";
                   
            if ( executeQuery($sql)) { // Załóżmy, że taka funkcja istnieje
                $success = "Hasło zostało pomyślnie zmienione.";
            } else {
                $error.= "Wystąpił problem podczas zmiany hasła. Spróbuj ponownie później.<br>";
            }
        } else {
            $error.= "Stare hasło jest niepoprawne.<br>";
        }
    }
}


// Konwertuj JSON z ról na tablicę PHP
$userRoles = json_decode($userInfo['roles'], true);

// Pobierz ostatnie 5 udanych logowań użytkownika
$sqlSuccessfulLogins = "SELECT * FROM " . PREFIX . "log_data WHERE user_id = $userId AND correct = 1 ORDER BY date_time DESC LIMIT 5";
$successfulLogins = fetchAll($sqlSuccessfulLogins);

// Pobierz ostatnie 5 nieudanych logowań użytkownika
$sqlFailedLogins = "SELECT * FROM " . PREFIX . "log_data WHERE user_id = $userId AND correct = 0 ORDER BY date_time DESC LIMIT 5";
$failedLogins = fetchAll($sqlFailedLogins);
?>
<div class="container">
    <h2>Moje Dane</h2>
    <table class="table table-bordered">
        <tr class="bg-white">
            <td><strong>QR KOD</strong></td>
            <td><img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=<?=$userInfo['qr']?>&choe=UTF-8" title="Kod QR dla <?=$userInfo['first_name'] . ' ' . $userInfo['last_name']?>" /></td>
        </tr>
        <tr class="bg-light">
            <td><strong>Imię i nazwisko:</strong></td>
            <td><?php echo $userInfo['first_name'] . ' ' . $userInfo['last_name']; ?></td>
        </tr>
        <tr class="bg-white">
            <td><strong>Adres email:</strong></td>
            <td><?php echo $userInfo['email']; ?></td>
        </tr>
        <tr class="bg-light">
            <td><strong>Numer telefonu:</strong></td>
            <td><?php echo $userInfo['phone']; ?></td>
        </tr>
        <tr class="bg-white">
            <td><strong>Role w stowarzyszeniu:</strong></td>
            <td><?= generateRoleString($userRoles); ?></td>
        </tr>
        <tr class="bg-light">
            <td><strong>Ostatnie udane logowanie:</strong></td>
            <td>
                <?php echo date("d F Y H:i:s", strtotime($successfulLogins[0]['date_time'])); ?>
                <button class="btn btn-sm btn-info" onclick="toggleLogins('successfulLoginsList')">Pokaż wszystkie</button>
                <ul id="successfulLoginsList" class="list-group" style="display:none;">
                    <?php foreach ($successfulLogins as $login): ?>
                        <li class="list-group-item"><?php echo date("d F Y H:i:s", strtotime($login['date_time'])); ?></li>
                    <?php endforeach; ?>
                </ul>
            </td>
        </tr>
        <tr class="bg-white">
            <td><strong>Ostatnie nieudane logowanie:</strong></td>
            <td>
                <?php if (!empty($failedLogins)) { echo date("d F Y H:i:s", strtotime($failedLogins[0]['date_time'])); ?>
                <button class="btn btn-sm btn-info" onclick="toggleLogins('failedLoginsList')">Pokaż wszystkie</button>
                <ul id="failedLoginsList" class="list-group" style="display:none;">
                    <?php 
                    foreach ($failedLogins as $login): ?>
                        <li class="list-group-item"><?php echo date("d F Y H:i:s", strtotime($login['date_time'])); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php } else { echo "Brak"; }?> 
            </td>
        </tr>
        <tr class="bg-light">
            <td><strong>Informacja o ostatniej edycji profilu:</strong></td>
            <td><?php echo date("d F Y H:i:s", strtotime($userInfo['update_date'])); ?></td>
        </tr>
        <tr class="bg-white" >
            <td><strong>Zmień hasło:</strong></td>
            <td>
            <?php if(isset($success)) { echo '<span class="text-success">' . $success . '</span>'; } ?>
            <?php if(isset($error)) { echo '<span class="text-danger">' . $error . '</span>'; } ?>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="old_password" class="form-label">Stare hasło:</label>
                    <input type="password" class="form-control" id="old_password" name="old_password">
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">Nowe hasło:</label>
                    <input type="password" class="form-control" id="new_password" name="new_password">
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Powtórz nowe hasło:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                </div>
                <button type="submit" class="btn btn-primary">Zmień hasło</button>
            </form>
</div>

            </td>
        </tr>
    </table>
</div>

<script>
    function toggleLogins(listId) {
        var list = document.getElementById(listId);
        if (list.style.display === "none") {
            list.style.display = "block";
        } else {
            list.style.display = "none";
        }
    }
</script>





