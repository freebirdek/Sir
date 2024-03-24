 <?php
 // Tutaj dodaj kod do sprawdzenia uprawnień użytkownika, np. czy ma dostęp do tej strony
if (!checkUserAccess(97)) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: login.html");
    exit();
}


if($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST") {
    // Sprawdź, czy przekazano ID użytkownika w adresie URL
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $eventId = $_GET['id'];
        
               // Przykładowe zapytanie SQL (pamiętaj o zabezpieczeniu przed atakami SQL Injection)
    $sql = "SELECT * FROM ".PREFIX."event WHERE id = $eventId";
    $event = fetchOne($sql); 
        
    }       
} 
if (!empty($event["invite"])) {
$invitedUsers=json_decode($event["invite"]);
} 

if (!empty($event["interest"])) {
$interesteddUsers=json_decode($event["interest"]);
$notinterested = array_diff($invitedUsers, $interesteddUsers);
} else $notinterested=json_decode($event["invite"]);

if (!empty($event["attend"])) {
$attendUsers=json_decode($event["attend"]);
} 


?>
<h1><?=$event["name"]?></h1>
<h5><a href="/adminevents.html">wróć do listy</a></h5>
<div class="container">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Niezainteresowani</h2>
                    <ul class="list-group">
                     <?php if (!empty($notinterested)) { 
                         foreach ($notinterested as $userId): 
                            $user = getUserInfo($userId);
                        ?>
                            <li class="list-group-item"><?= $user["first_name"]; ?> <?= $user["last_name"]; ?></li>
                        <?php endforeach; 
                     } else echo "<li>Lista jest pusta</li>";
                        ?>           
                    </ul>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Zainteresowani</h2>
                    <ul class="list-group">
                      <?php if (!empty($interesteddUsers)) { 
                         foreach ($interesteddUsers as $userId): 
                            $user = getUserInfo($userId);
                        ?>
                            <li class="list-group-item"><?= $user["first_name"]; ?> <?= $user["last_name"]; ?></li>
                        <?php endforeach; 
                     } else echo "<li>Lista jest pusta</li>";
                        ?>                         
                    </ul>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Wzięli udział</h2>
                    <ul class="list-group">
                      <?php if (!empty($interesteddUsers)) { 
                         foreach ($interesteddUsers as $userId): 
                            $user = getUserInfo($userId);
                        ?>
                            <li class="list-group-item"><?= $user["first_name"]; ?> <?= $user["last_name"]; ?></li>
                        <?php endforeach; 
                     } else echo "<li>Lista jest pusta</li>";
                        ?>                         
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
