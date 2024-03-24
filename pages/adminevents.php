<?php

if (!checkUserAccess(97)) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: login.html");
    exit();
}

$sql="UPDATE ".PREFIX."event SET active = 0 WHERE start_date_time < CURRENT_DATE()";
executeQuery($sql);

// Pobierz wszystkich członków z bazy danych, posortowanych według nazwiska
$sql = "SELECT * FROM " . PREFIX . "event ORDER BY active DESC, start_date_time DESC";
$events = fetchAll($sql);



?>
    <h1>Lista wydarzeń</h1>
    <p>Jeżeli chcesz sprawdzić obecność na dane wydarzenie to najpierw je zamknij</p>
    
    <p><a href="adminaddevent.html" class="btn btn-primary">
    <i class="bi bi-plus"></i> Dodaj nowe wydarzenie
    </a></p>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">Typ</th>
                <th style="width: 15%;">Nazwa wydarzenia</th>
                <th>Opis</th>
                <th style="width: 14%;">Początek</th>
                <th style="width: 14%;">Koniec</th>
                <th style="width: 10%;">Akcja</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $event): ?>
                <?php
                // Określenie koloru tła wiersza na podstawie pola "Active"
                switch ($event['active']) {
                    case 1:
                        $background_color = '#AAFF7F'; // Żółty
                        break;
                    case 0:
                        $background_color = '#ff6a6c'; // Jasno czerwony
                        break;
                    // Dodać inne przypadki, jeśli wymagane
                }
                ?>
                <tr style="background-color: <?php echo $background_color; ?>">
                    <td><?php echo $event['type']; ?></td>
                    <td><?php echo $event['name']; ?></td>
                    <td><?php echo $event['details']; ?></td>
                    <td><?php echo showDateInEvent($event['start_date_time'],"'<strong>'EEEE'</strong>', d MMMM y '<br>godzina' HH:mm", 0); ?></br>
                    <br><strong><?php echo showDateInEvent($event['start_date_time'],"", 1); ?></strong></td>
                    <td><?php echo showDateInEvent($event['end_date_time'],"'<strong>'EEEE'</strong>', d MMMM y '<br>godzina' HH:mm", 0); ?></td>
                    <td>
                            <a href="/admineditevent/<?php echo $event['id']; ?>.html" title="Edycja wydarzenia"><i class="bi-pencil-fill" style="font-size: 16px;"></i></a>
                            | <a href="/eventuserlist/<?php echo $event['id']; ?>.html" title="Zaproszeni"><i class="bi-card-list" style="font-size: 16px;"></i>
                            | <a href="/adminonoffevent/<?php echo $event['id']; ?>.html" title="Włącz/wyłącz wydarzenie"><i class="bi-toggles" style="font-size: 16px;"></i>
                            <?php if ($event["active"] == 0 && $event['close']==0) : ?>
                            | <a href="/eventattendlist/<?php echo $event['id']; ?>.html" title="Lista obecności"><i class="bi-card-checklist" style="font-size: 16px;"></i>
                            <?php endif;?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
