<?php 
if (!checkUserAccess(98)) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: login.html");
    exit();
}
 //`id`, `sender_id`, `receiver_id`, `title`, `message`, `read_status`, `thread_id`, `timestamp`
 
if($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST") {
    // Sprawdź, czy przekazano ID użytkownika w adresie URL
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $userId = $_GET['id'];
        
               // Przykładowe zapytanie SQL (pamiętaj o zabezpieczeniu przed atakami SQL Injection)
    $sql = "SELECT * FROM ".PREFIX."users WHERE id = $userId";
    $user = fetchOne($sql); 
        
    $msg["grade"]="0";
    }       
}

 $error="";  
// Obsługa formularza
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Pobranie danych z $_POST i zastosowanie funkcji validateInput
        if ($_POST["grade"]=="0") $error .= 'Wybierz ocenę.';
        $msg["grade"] = $_POST["grade"] ; 

        if (!isset($_POST["details"])) $error .= 'Wpisz wiadomość!';
        $msg["details"] = escapeString($_POST["details"]);
        $msg["user_id"] = $user['id'];

        $msg["created_by"]=$_SESSION["user_id"];
        
    // Walidacja pól formularza (możesz dodać więcej warunków)
    if (empty($error)) {
       // Złożenie danych w tablicę asocjacyjną
        if (insert('evaluation', $msg)) {
                header("Location: /adminusers.html");
                exit();
        } else {
            $error = "Wystąpił problem podczas wysyłania wiadomości!";
        }
    }
}    

    
?> 
<div class="container">
    <!-- Tutaj umieść zawartość dla zakładki Wiadomości -->
    <div id="sendMessageForm" class="form-container">
    <h3>Wystaw ocenę</h3>
    <form action="" method="post">
        <div class="form-group">
            <label for="user_id">Użytkownik</label>
            <select class="form-control" id="user_id" name="user_id" disabled>
                        <option value="<?= $user['id'] ?>"><?= $user['first_name'] ?> <?= $user['last_name'] ?></option>         
            </select>
        </div>
        <div class="form-group">
            <label for="grade">Ocena</label>
            <select class="form-control" id="grade" name="grade">
                        <option value="+5" <?= (isset($msg["grade"]) && $msg["grade"]=="+5")? "selected" : "" ?>>+5</option>
                        <option value="+4" <?= (isset($msg["grade"]) && $msg["grade"]=="+4")? "selected" : "" ?>>+4</option>
                        <option value="+3" <?= (isset($msg["grade"]) && $msg["grade"]=="+3")? "selected" : "" ?>>+3</option>
                        <option value="+2" <?= (isset($msg["grade"]) && $msg["grade"]=="+2")? "selected" : "" ?>>+2</option>
                        <option value="+1" <?= (isset($msg["grade"]) && $msg["grade"]=="+1")? "selected" : "" ?>>+1</option>
                        <option value="0" <?= (isset($msg["grade"]) && $msg["grade"]=="0")? "selected" : "" ?>>Wybierz ocenę</option>
                        <option value="-1" <?= (isset($msg["grade"]) && $msg["grade"]=="-1")? "selected" : "" ?>>-1</option>
                        <option value="-2" <?= (isset($msg["grade"]) && $msg["grade"]=="-2")? "selected" : "" ?>>-2</option>
                        <option value="-3" <?= (isset($msg["grade"]) && $msg["grade"]=="-3")? "selected" : "" ?>>-3</option>
                        <option value="-4" <?= (isset($msg["grade"]) && $msg["grade"]=="-4")? "selected" : "" ?>>-4</option>
                        <option value="-5" <?= (isset($msg["grade"]) && $msg["grade"]=="-5")? "selected" : "" ?>>-5</option>
            </select>
        </div>
        <div class="form-group">
            <label for="details">Wiadomość:</label>
            <textarea class="form-control" id="details" name="details" rows="3" required></textarea>
        </div>
        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
        <button type="submit" class="btn btn-primary">Wyślij</button>
    </form>
    </div>
    <hr class="my-4">

<p><?=$error?></p>

 
</div>


