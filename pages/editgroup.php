  <?php
 // Tutaj dodaj kod do sprawdzenia uprawnień użytkownika, np. czy ma dostęp do tej strony
if (!checkUserAccess(99)) {
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

        $update_sql="";
        
        if (isset($_POST['name'])) $update_sql .="name= '".$_POST["name"]."',";
        if (isset($_POST['value'])) $update_sql .="value= '".$_POST["value"]."',";
         if (isset($_POST['description'])) $update_sql .="description= '".$_POST["description"]."',";

        
        
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
                    <input type="text" class="form-control" id="name" name="name" value="<?= (isset($option['name'])) ? $option['name'] : ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="value" class="form-label">Wartość:</label>
                    <textarea class="form-control" id="value" name="value"><?= (isset($option['value'])) ? $option['value'] : ''; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Opis:</label>
                    <textarea class="form-control" id="description" name="description"><?= (isset($option['description'])) ? $option['description'] : ''; ?></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                </div>
            </form>
        </div>
    </div>
</div>

 
