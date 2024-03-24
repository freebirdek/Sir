<?php 
if (!checkUserAccess(2)) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: login.html");
    exit();
}
$user_id = $_SESSION['user_id']; // Pobranie ID użytkownika z sesji

// Zapytanie SQL do pobrania wydarzeń na podstawie ID użytkownika
$sql = "SELECT * FROM " . PREFIX . "event WHERE invite LIKE '%".$user_id."%' AND active = 1 ORDER BY start_date_time";

// Pobranie wyników zapytania
$events = fetchAll($sql);
// Grupowanie wydarzeń według miesięcy
$eventsByMonth = [];

foreach ($events as $event) {
    $month = date('F Y', strtotime($event['start_date_time']));
    $month = showDateInEvent($event['start_date_time'],"MMMM", 0);
    $eventsByMonth[$month][] = $event;
}
?>

<div class="container">
    <h2>Najbliższe wydarzenia</h2>

    <?php foreach ($eventsByMonth as $month => $events): ?>
        <h3><?php echo $month; ?></h3>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Typ</th>
                    <th>Nazwa</th>
                    <th>Data rozpoczęcia</th>
                    <th>Data zakończenia</th>
                    <th>Opis</th>
                    <th>Udział</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?php echo $event['type']; ?></td>
                        <td style="width: 25%;"><?php echo $event['name']; ?></td>
                        <td style="width: 12%;"><?php echo showDateInEvent($event['start_date_time'],"'<strong>'EEEE'</strong><br>' d MMMM y '<br>godzina' HH:mm", 0) ?></td>
                        <td style="width: 12%;"><?php echo showDateInEvent($event['end_date_time'],"'<strong>'EEEE'</strong><br>' d MMMM y '<br>godzina' HH:mm", 0) ?></td>
                        <td><?php echo $event['details']; ?></td>
                        <td style="width: 7%;">
                            <a href="/eventonoff/<?php echo $event['id']; ?>.html" title="Włącz/wyłącz wydarzenie">
                            
                            <?php if (!checkJson($event['interest'], $user_id)) { ?>
                                    <i class="bi bi-hand-thumbs-up" style="font-size: 24px; color: #00AA00"></i><i class="bi bi-hand-thumbs-down-fill" style="font-size: 24px; color: #AA0000"></i>
                            <?php } else { ?>
                                    <i class="bi bi-hand-thumbs-up-fill" style="font-size: 24px; color: #00AA00""></i><i class="bi bi-hand-thumbs-down" style="font-size: 24px; color: #AA0000"></i>
                            <?php } ?>  
                            
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</div>
