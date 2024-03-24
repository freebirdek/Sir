<?php

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
$error="";
// Obsługa formularza
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!is_numeric($_POST["amount"])) $error .="Podana kwota nie jest liczbą <br>";
    $payment["amount"]=$_POST["amount"];
    
    if (!strtotime($_POST["payment_date"])) $error .="Podana data jest nieprawidłowa <br>";
    $payment["payment_date"]=$_POST["payment_date"];
    
    if ($_POST["type"]=="") $error .="Wybierz z listy<br>";
    $payment["type"]=$_POST["type"];
    
    $payment["user_id"] = $userId;
    $payment["created_by"] = $payment["update_by"] = $_SESSION['user_id'];

    // Walidacja pól formularza (możesz dodać więcej warunków)
    if (!empty($error)) {
        
        echo $error;
        
    } else {
        //`id`, `user_id`, `amount`, `payment_date`, `type`, `created_at`, `updated_at`, `created_by`, `updated_by`
       // Złożenie danych w tablicę asocjacyjną
       
        
        if (insert('finance', $payment)) {
        
            echo "Dodano płatność";
            
        } else {
            
            echo "Wystąpił problem podczas dodawania płatności!";
            
        }
    }
}

$sql = "SELECT * FROM ".PREFIX."finance WHERE user_id =".$userId." ORDER BY payment_date DESC";
$payments = fetchAll($sql); 


?>
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3 bg-light p-4">
            <h2 class="text-center">Płatność dla <?= $user["first_name"] ?> <?= $user["last_name"] ?></h2>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="amount" class="form-label">Kwota wpłaty:</label>
                    <input type="text" class="form-control" id="amount" name="amount" required> PLN
                </div>

                <div class="mb-3">
                    <label for="payment_date" class="form-label">Data wpłaty:</label>
                    <input type="date" class="form-control" id="payment_date" name="payment_date" required>
                </div>

               <div class="input-group mb-3">
                    <label for="type" class="form-label">Typ płatności:</label>
                    <select class="form-select input-group-text" id="type" name="type">
                        <option value="Składka">Składka członkowska</option>
                        <option value="Darowizna">Darowizna</option>
                        <option value="Inne">Inne</option>
                    </select>
                </div>


                <input type="hidden" name="user_id" value="<?= $user_id ?>">
                <button type="submit" class="btn btn-primary d-block mx-auto">Dodaj płatność</button>
            </form>
        </div>
    </div>

    <hr class="my-4">

    <div class="row">
        <div class="col-md-6 offset-md-3 bg-light p-4">
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

