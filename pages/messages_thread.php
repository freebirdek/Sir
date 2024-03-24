
<?php 
if (!checkUserAccess(2)) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: login.html");
    exit();
}
 //`id`, `sender_id`, `receiver_id`, `subject`, `message`, `read_status`, `thread_id`, `timestamp`
 
 if($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST") {
    // Sprawdź, czy przekazano ID użytkownika w adresie URL
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $thread_id = $_GET['id'];
    }  
     $threads=readAllThreads($thread_id);
     $thread_status=readThread($thread_id);
     
     if ($thread_status["receiver_id"]==$_SESSION['user_id']) $read_status='["1","1"]'; 
     elseif ($thread_status["receiver_id"]==$thread_status["sender_id"]) $read_status='["1","1"]';
     elseif ($thread_status["sender_id"]==$_SESSION['user_id']) $read_status='["1","0"]';
     
     //edytuj jako odczytany wątek
    $sql = "UPDATE ".PREFIX."chat SET read_status='".$read_status."' WHERE thread_id = ".$thread_id." AND read_status NOT LIKE '%[\"1\",\"1\"]%'";

    // Wykonaj zapytanie do bazy danych
    executeQuery($sql);
}

// Obsługa formularza
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Pobranie danych z $_POST i zastosowanie funkcji validateInput 
        if (!isset($_POST["message"])) $error .= 'Wpisz wiadomość!';
        $msg["message"] = escapeString($_POST["message"]) ;
    
       if ($_POST["sender_id"]==$_SESSION['user_id']) {
            $msg["sender_id"]=$_POST["sender_id"];
            $msg["receiver_id"]=$_POST["receiver_id"];
       }
        else {
            $msg["sender_id"]=$_POST["receiver_id"];
           $msg["receiver_id"]=$_POST["sender_id"];
            
        }
        $msq["read_status"]='["1","0"]';
        $msg["subject"]=$_POST["subject"];
        $msg["thread_id"]=$_POST["thread_id"];

    // Walidacja pól formularza (możesz dodać więcej warunków)
    if (empty($error)) {
       // Złożenie danych w tablicę asocjacyjną
        if (insert('chat', $msg)) {
            
            sendEmail(getUserInfo($msg["receiver_id"])["email"], getOptions('reply_message_email_subject', array("site_name"=>$site_name)), getOptions('reply_message_email_content', array("site_name"=>$site_name,"site_url"=>$site_url )));
            
                header("Location: /messages.html");
               exit();
        } else {
            $error = "Wystąpił problem podczas wysyłania wiadomości!";
        }
    }
}    


 
?>

<div class="container">
<a href="/messages.html"><< wróć do listy wiadomości</a>
    <h3><?= readThread($thread_id)["subject"] ?></h3>
        <div class="container">
    <!-- Tutaj umieść zawartość dla zakładki Wiadomości -->
    <div id="sendMessageForm" style="display: none;" class="form-container">
    <h3>Odpowiedź</h3>
    <form action="" method="post">
        <div class="form-group">
            <label for="message">Wiadomość:</label>
            <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
        </div>
        <input type="hidden" name="subject" value="<?= readThread($thread_id)["subject"] ?>">
        <input type="hidden" name="receiver_id" value="<?= readThread($thread_id)["receiver_id"] ?>">
        <input type="hidden" name="sender_id" value="<?= readThread($thread_id)["sender_id"] ?>">
        <input type="hidden" name="thread_id" value="<?= readThread($thread_id)["thread_id"] ?>">
        <button type="submit" class="btn btn-primary">Wyślij</button>
    </form>
    </div>
    <hr class="my-4">
<button type="button" class="btn btn-success" onclick="toggleForm('sendMessageForm')">Odpowiedź</button>

        <hr class="my-4">
    <table>
        <tbody>
            <?php foreach($threads as $thread) :
                
                $display=getUserInfo($thread["sender_id"])["first_name"]." ".getUserInfo($thread["sender_id"])["last_name"];
                
                if ($thread["read_status"]==0) $read_status_style = 'font-weight: bold;'; else $read_status_style="";
                 ?>
                <tr>
                    <td style="width: 20%; <?=$read_status_style?>"><?= $display ?></td>
                    <td style="width: 65%; <?=$read_status_style?>"><?= $thread["message"] ?></td>
                    <td style="width: 15%;<?=$read_status_style?>"><?= date('d M Y H:i',strtotime($thread["timestamp"])) ?></td>
                </tr>
            <?php endforeach; ?>  
        </tbody>
    </table>
</div>
