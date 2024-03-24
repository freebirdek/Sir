 <?php
 // Tutaj dodaj kod do sprawdzenia uprawnień użytkownika, np. czy ma dostęp do tej strony
if (!checkUserAccess(99)) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: login.html");
    exit();
}

    $sql = "SELECT * FROM ".PREFIX."options";
    $options = fetchAll($sql);  
    



?>
<div class="container mt-5">
    <h2 class="mb-4">Edycja opcji</h2>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nazwa</th>
                <th scope="col">Wartość</th>
                <th scope="col">Opis</th>
                <th scope="col">Akcja</th>
            </tr>
        </thead>
        <tbody>
            <!-- Tutaj generujesz wiersze tabeli na podstawie danych z bazy danych -->
            <?php foreach ($options as $option): ?>
                <tr>
                    <td><?= $option['id'] ?></td>
                    <td><?= $option['name'] ?></td>
                    <td><?= $option['value'] ?></td>
                    <td><?= $option['description'] ?></td>
                    <td>
                        <!-- Przycisk edycji -->
                        <a href="admineditoption/<?= $option['id'] ?>.html" class="btn btn-primary btn-sm">Edytuj</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
