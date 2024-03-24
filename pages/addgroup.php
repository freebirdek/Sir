  <?php
 // Tutaj dodaj kod do sprawdzenia uprawnień użytkownika, np. czy ma dostęp do tej strony
if (!checkUserAccess(2)) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: login.html");
    exit();
}


    
if($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST") {
    // Sprawdź, czy przekazano ID użytkownika w adresie URL
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $userId = $_GET['id'];
        
               // Przykładowe zapytanie SQL (pamiętaj o zabezpieczeniu przed atakami SQL Injection)
    $sql = "SELECT * FROM ".PREFIX."users";
    $users = fetchAll($sql); 
        
    $sql = "SELECT * FROM ".PREFIX."groups WHERE user_id=".$userId;
    $groups = fetchAll($sql); 
    
    }       
}

// Obsługa formularza
if ($_SERVER["REQUEST_METHOD"] == "POST") {



        
        
       // echo "SQL : ".$update_sql;
        if (!empty($update_sql)) {
                      $update_sql .=" update_by= '".$_SESSION["user_id"]."',update_at= NOW()";
       
                    // Przykładowe zapytanie SQL
                    $sql = "UPDATE ".PREFIX."options SET ".$update_sql." WHERE id = $option_id";
                    executeQuery($sql);

        // Po zaktualizowaniu danych przekieruj użytkownika na stronę wyświetlającą informacje o sukcesie
        header("Location: /adminsettings.html");
        exit();
        }
        
}    

//`id`, `name`, `value`, `description`, `created_at`, `update_at`, `created_by`, `update_by`
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Utwórz grupę</h2>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Nazwa:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= (isset($group['name'])) ? $group['name'] : ''; ?>" required>
                </div>
                 <div class="mb-3">
                    <label for="name" class="form-label">Użytkownicy:</label>
                    <?php foreach($users as $user) :
                        if ($user['id'] != $_SESSION["user_id"]) :?>
                            <br><input type="checkbox" name="groupuser_id" value="<?= $user['id'] ?>"> <?= $user['first_name'] ?> <?= $user['last_name'] ?>
                    <?php 
                        endif;
                    endforeach ?>        
                </select>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Zapisz</button>
                </div>
            </form>
        </div>
    </div>
</div>

 
