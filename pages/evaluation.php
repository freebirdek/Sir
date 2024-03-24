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

$sql = "SELECT * FROM " . PREFIX . "evaluation WHERE user_id = $userId";
$grades = fetchAll($sql);

$sql = "SELECT SUM(grade) AS oceny FROM " . PREFIX . "evaluation WHERE user_id = $userId";
$gradeSum = fetchOne($sql);

?>

<div class="container">
    <h2>Moje oceny (<?=($gradeSum["oceny"]>0)? "+".$gradeSum["oceny"]:$gradeSum["oceny"];?>)</h2>
    <table class="table table-bordered">
    <?php foreach($grades as $grade): ?>
        <tr>
            <td><?=$grade["grade"]?></td>
            <td><?=$grade["details"]?></td>
            <td>Otrzymana dnia: <?=$grade["created_at"]?></td>
        </tr>
    <?php endforeach; ?>
    </table>
</div>
