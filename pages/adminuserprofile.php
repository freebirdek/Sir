<?php 
if (!checkUserAccess(97)) {
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
    $userInfo = fetchOne($sql); 
        
    }       
}

// Konwertuj JSON z ról na tablicę PHP
$userRoles = json_decode($userInfo['roles'], true);

// Pobierz ostatnie 5 udanych logowań użytkownika
$sqlSuccessfulLogins = "SELECT * FROM " . PREFIX . "log_data WHERE user_id = ".$userId." AND correct = 1 ORDER BY date_time DESC LIMIT 5";
$successfulLogins = fetchAll($sqlSuccessfulLogins);

// Pobierz ostatnie 5 nieudanych logowań użytkownika
$sqlFailedLogins = "SELECT * FROM " . PREFIX . "log_data WHERE user_id = ".$userId." AND correct = 0 ORDER BY date_time DESC LIMIT 5";
$failedLogins = fetchAll($sqlFailedLogins);

// Pobierz wszystkie wpłaty użytkownika
$sqlPayments = "SELECT * FROM ".PREFIX."finance WHERE user_id =".$userId." ORDER BY payment_date DESC";
$payments = fetchAll($sqlPayments); 


?>
<div class="container">
    <h2>Dane <?=$userInfo['first_name'] . ' ' . $userInfo['last_name']; ?></h2>
    <table class="table table-bordered">
        <tr class="bg-white">
            <td><strong>QR KOD</strong></td>
            <td><img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=<?=$userInfo['qr']?>&choe=UTF-8" title="Link to Google.com" /></td>
        </tr>
        <tr class="bg-light">
            <td><strong>Imię i nazwisko:</strong></td>
            <td><?=$userInfo['first_name'] . ' ' . $userInfo['last_name']; ?></td>
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
                <?= !empty($successfulLogins) ? date("d F Y H:i:s", strtotime($successfulLogins[0]['date_time'])) : "" ?>
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
                <?= !empty($failedLogins) ? date("d F Y H:i:s", strtotime($failedLogins[0]['date_time'])) : "" ?>
                <button class="btn btn-sm btn-info" onclick="toggleLogins('failedLoginsList')">Pokaż wszystkie</button>
                <ul id="failedLoginsList" class="list-group" style="display:none;">
                    <?php foreach ($failedLogins as $login): ?>
                        <li class="list-group-item"><?php echo date("d F Y H:i:s", strtotime($login['date_time'])); ?></li>
                    <?php endforeach; ?>
                </ul>
            </td>
        </tr>
        <tr class="bg-light">
            <td><strong>Informacja o ostatniej edycji profilu:</strong></td>
            <td><?php echo date("d F Y H:i:s", strtotime($userInfo['update_date'])); ?></td>
        </tr>
        <tr class="bg-white">
            <td><strong>Wpłaty na stowarzyszenie: </strong></td>
            <td>
                <button class="btn btn-sm btn-info" onclick="toggleLogins('financeIncome')">Pokaż wszystkie</button>
                <ul id="financeIncome" class="list-group" style="display:none;">
                    <?php foreach ($payments as $pay): 
                    //`id`, `user_id`, `amount`, `payment_date`, `type`, `created_at`, `update_at`, `created_by`, `update_by`?>
                        <li class="list-group-item"><?= !empty($pay) ? date("d.m.Y", strtotime($pay['payment_date']))." - <strong>".$pay['amount']." PLN</strong> (".$pay['type'].")" : "" ?></li>
                    <?php endforeach; ?>
                </ul>
            </td>
        </tr>
        <tr class="bg-light">
            <td><strong>Udział w wydarzeniach: </strong></td>
            <td>
            <button class="btn btn-sm btn-info" onclick="toggleLogins('eventStats')">Pokaż wszystkie</button>
            <ul id="eventStats" class="list-group" style="display:none;">
                <li class="list-group-item">Wziął udział w <?= showEventAttend($userId).'/'.showAllEvent($type = 0) ?> wydarzeniach (<?= showPercent(showEventAttend($userId), showAllEvent($type = 0)) ?>)</li>
                <li class="list-group-item">Wziął udział w <?= showEventAttend($userId,'Akcja').'/'.showAllEvent('Akcja') ?> akcjach (<?= showPercent(showEventAttend($userId,'Akcja'), showAllEvent('Akcja')) ?>)</li>
                <li class="list-group-item">Wziął udział w <?= showEventAttend($userId,'Szkolenie').'/'.showAllEvent('Szkolenie') ?> szkoleniach (<?= showPercent(showEventAttend($userId,'Szkolenie'), showAllEvent('Szkolenie')) ?>)</li>
                <li class="list-group-item">Wziął udział w <?= showEventAttend($userId,'Psy').'/'.showAllEvent('Psy') ?> pracy z psami (<?= showPercent(showEventAttend($userId,'Psy'), showAllEvent('Psy')) ?>)</li>
                <li class="list-group-item">Wziął udział w <?= showEventAttend($userId,'Inne').'/'.showAllEvent('Inne') ?> innych wydarzeniach (<?= showPercent(showEventAttend($userId,'Inne'), showAllEvent('Inne')) ?>)</li>
            </ul>
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





 
