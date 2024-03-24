<?php

if (!checkUserAccess(97)) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: login.html");
    exit();
}

// Pobierz wszystkich członków z bazy danych, posortowanych według nazwiska
$sql = "SELECT * FROM " . PREFIX . "users ORDER BY last_name";
$members = fetchAll($sql);

?>
    <h1>Lista członków</h1>
    
    <p><a href="adminadduser.html" class="btn btn-primary">
    <i class="bi bi-plus"></i> Dodaj nowego użytkownika
    </a></p>
    <div class="container">
        <div class="row">
            <div class="col blocked">Członek zablokowany</div>
            <div class="col member">Członek zwykły</div>
            <div class="col moderator">Moderator</div>
            <div class="col super-moderator">Super Moderator</div>
            <div class="col administrator">Administrator</div>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 2%;">Numer</th>
                <th style="width: 15%;">Nazwisko</th>
                <th style="width: 10%;">Imię</th>
                <th>Email</th>
                <th>Numer Telefonu</th>
                <th style="width: 20%;">Role</th>
                <th style="width: 10%;">Akcja</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($members as $member): ?>
                <?php
                // Określenie koloru tła wiersza na podstawie pola "Active"
                $background_color = '#ccffcc'; // Domyślny kolor (jasno zielony)
                switch ($member['access']) {
                    case 1:
                        $background_color = '#FF7375'; // Żółty
                        break;
                    case 97:
                        $background_color = '#ffff99'; // Żółty
                        break;
                    case 98:
                        $background_color = '#ffcccc'; // Jasno czerwony
                        break;
                    case 99:
                        $background_color = '#99ccff'; // Jasno niebieski
                        break;
                    // Dodać inne przypadki, jeśli wymagane
                }
                ?>
                <tr style="background-color: <?php echo $background_color; ?>">
                    <td><?php echo $member['id']; ?></td>
                    <td><?php echo $member['last_name']; ?></td>
                    <td><?php echo $member['first_name']; ?></td>
                    <td><?php echo $member['email']; ?></td>
                    <td><?php echo $member['phone']; ?></td>
                    <td><?php echo generateRoleString(json_decode($member['roles'])); ?></td>
                    <td>
                    <a href="/adminuserprofile/<?php echo $member['id']; ?>.html" title="Profil"><i class="bi bi-person-badge" style="font-size: 16px;"></i></a> 
                    | <a href="/adminevaluation/<?php echo $member['id']; ?>.html" title="Wystaw ocenę"><i class="bi-award-fill" style="font-size: 16px;"></i></a>
                    <?php if(checkUserAccess(98)): ?>
                            | <a href="/adminedituser/<?php echo $member['id']; ?>.html" title="Edycja członka"><i class="bi-pencil-fill" style="font-size: 16px;"></i></a> 
                    <?php endif; ?>
                            | <a href="/adminsendpassword/<?php echo $member['id']; ?>.html" title="Wyślij nowe hasło"><i class="bi-window-dash" style="font-size: 16px;"></i></a> 
                            | <a href="/adminsendmsg/<?php echo $member['id']; ?>.html" title="Wyślij wiadomość"><i class="bi-chat-left-dots" style="font-size: 16px;"></i></a>
                    <?php if(checkUserAccess(98)): ?>
                            | <a href="/adminfinance/<?php echo $member['id']; ?>.html" title="Dodaj płatności"><i class="bi-cash-coin" style="font-size: 16px;"></i></a>
                    <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
