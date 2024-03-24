<?php 
if (!checkUserAccess(2)) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: login.html");
    exit();
}

$sql = "SELECT * FROM ".PREFIX."finance WHERE user_id =".$_SESSION["user_id"]." ORDER BY payment_date DESC";
$payments = fetchAll($sql); 

?>

<h1>Moje finanse</h1>
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h4 class="text-center">Historia płatności</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Data wpłaty</th>
                        <th>Kwota</th>
                        <th>Typ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $pay) : ?>
                        <tr>
                            <td><?= date('d.m.Y', strtotime($pay['payment_date'])); ?></td>
                            <td><?= $pay['amount'] ?></td>
                            <td><?= $pay['type'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

