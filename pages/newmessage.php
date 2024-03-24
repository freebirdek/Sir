<?php 
if (!checkUserAccess(97)) {
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
    $sql = "SELECT * FROM ".PREFIX."users";
    $users = fetchAll($sql); 
        
    $sql = "SELECT * FROM ".PREFIX."groups";
    $groups = fetchAll($sql); 
    
    }       
}

 $error="";  
// Obsługa formularza
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Pobranie danych z $_POST i zastosowanie funkcji validateInput
        if (!isset($_POST["subject"])) $error .= 'Wpisz temat!';
        $msg["subject"] = escapeString($_POST["subject"]) ; 

        if (!isset($_POST["message"])) $error .= 'Wpisz wiadomość!';
        $msg["message"] = escapeString($_POST["message"]) ;
    
        if ($_POST["receiver_id"]==0) $error .= 'Wybierz adresata';
        else $msg["receiver_id"]=$_POST["receiver_id"];
        
        $msg["sender_id"]=$_SESSION['user_id'];
        $msg["thread_id"]=generateThreadId();

    // Walidacja pól formularza (możesz dodać więcej warunków)
    if (empty($error)) {
       // Złożenie danych w tablicę asocjacyjną
        if (insert('chat', $msg)) {
               // header("Location: /messages.html");
              //  exit();
        } else {
            $error = "Wystąpił problem podczas wysyłania wiadomości!";
        }
    }
}    

    
?> 
<div class="container">
    <!-- Tutaj umieść zawartość dla zakładki Wiadomości -->
    <div id="sendMessageForm" class="form-container">
    <h3>Nowa wiadomość </h3>
    <form action="" method="post">
        <div class="form-group row">
            <div class="col-md-6">
                <label for="receiver_id">Odbiorca:</label>
                <select class="form-control" id="receiver_id" name="receiver_id">
                <option value="0">Wybierz adresata:</option>
                    <?php foreach($users as $user) :
                        if ($user['id'] != $_SESSION["user_id"]) :?>
                            <option value="<?= $user['id'] ?>"><?= $user['first_name'] ?> <?= $user['last_name'] ?></option>   
                    <?php 
                        endif;
                    endforeach ?>        
                </select>
            </div>
            <div class="col-md-6">
                <label for="group_id">Grupy:</label> <a href="/addgroup/<?=$_SESSION["user_id"]?>.html">+ Dodaj grupę</a>
                <select class="form-control" id="group_id" name="group_id">
                <option value="0">Wybierz adresatów:</option>
                    <?php foreach($groups as $group) :?>
                            <option value="<?= $group['id'] ?>"><?= $group['group_name'] ?></option>   
                    <?php 
                        endforeach ?>        
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="subject">Temat:</label>
            <input type="text" class="form-control" id="subject" name="subject" required>
        </div>
        <div class="form-group">
            <label for="message">Wiadomość:</label>
            <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
        </div>
        <input type="hidden" name="receiver_id" value="<?= $user['id'] ?>">
        <button type="submit" class="btn btn-primary">Wyślij</button>
    </form>
    </div>
    <hr class="my-4">

<p><?=$error?></p>

 
</div>

 
