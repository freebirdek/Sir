<?php 
if (!checkUserAccess(2)) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: login.html");
    exit();
}
 //`id`, `sender_id`, `receiver_id`, `title`, `message`, `read_status`, `thread_id`, `timestamp`


// Zapytanie SQL
    $sql = "SELECT DISTINCT thread_id FROM ".PREFIX."chat WHERE sender_id = ".$_SESSION['user_id']." OR receiver_id = ".$_SESSION['user_id']." ORDER BY timestamp DESC";
    $threads= fetchAll($sql); 
    
    $sql = "SELECT * FROM ".PREFIX."users ORDER BY last_name, first_name";
    $users = fetchAll($sql); 
    
?> 
<h2>Centrum wiadomości</h2>
<div class="container">
    <!-- Tutaj umieść zawartość dla zakładki Wiadomości -->
    <h3>Wiadomości</h3>

<a href="/newmessage/<?=$_SESSION['user_id']?>.html" class="btn btn-success">+ Nowa wiadomość</a>

<p><?=$error?></p>

    <hr class="my-4">
    <table>
        <tbody>
            <?php $i = 0;
            
                foreach($threads as $thread) :
                
                $msg = readThread($thread["thread_id"]); 

                $read_status=is_array($msg["read_status"]) ? $msg["read_status"] : json_decode($msg["read_status"], true);
            
                $read_status_sender=is_array($read_status) ? $read_status[0] : 0;
                $read_status_reciver=is_array($read_status) ? $read_status[1] : 0;
                
                if ($msg["sender_id"] == $_SESSION['user_id']) {
                    $display = getUserInfo($msg["receiver_id"])["first_name"] . " " . getUserInfo($msg["receiver_id"])["last_name"]; 
                } else {
                    $display = getUserInfo($msg["sender_id"])["first_name"] . " " . getUserInfo($msg["sender_id"])["last_name"];
                }
                
                $bg_color = ($i % 2 == 0) ? '#f2f2f2' : ''; // Dla co drugiego wiersza
                
                $i++; // Zwiększ licznik
                ?>
                <tr onclick="window.location='/messages_thread/<?= $thread["thread_id"] ?>.html';" style="cursor: pointer;" onmouseover="this.style.backgroundColor='#e6e6e6';" onmouseout="this.style.backgroundColor='<?= $bg_color ?>';">
                    <td style="width: 20%;"><?= $display ?></td>
                    <td style="width: 40%;"><?= $msg["subject"] ?></td>
                    <td style="text-align: center; width: 10%;"><?= count(readAllThreads($thread["thread_id"])) ?><br>wiadomość/ci</td>
                    <td style="width: 15%;"><?= date('d M Y H:i', strtotime($msg["timestamp"])) ?></td>
                    <td style="width: 5%;"><?php if ($read_status_sender==1 && $read_status_reciver==1) echo '<i class="bi bi-check-all" style="font-size: 32px"></i>'; else echo '<i class="bi bi-check" style="font-size: 32px"></i>';?></td>
                </tr>
            <?php endforeach; ?>  
        </tbody>
    </table>

</div>

