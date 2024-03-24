 <?php
 // Tutaj dodaj kod do sprawdzenia uprawnień użytkownika, np. czy ma dostęp do tej strony
if (!checkUserAccess(97)) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: login.html");
    exit();
}

// Obsługa formularza
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Pobranie danych z $_POST i zastosowanie funkcji validateInput
        if (!checkString($_POST["name"], 10, 100,array(),1,1,1,0)) $errors['name'] = 'Twoja nazwa jest niepoprawna.';
        $event["name"] = $_POST["name"] ; 
        
        if (isset($_POST["details"])) $event["details"] = escapeString($_POST["details"]);
        else $event["details"] = "";
    
        if (validateAndCompareDates($_POST["start_date_time"],$_POST["end_date_time"] )==1) $errors['date'] = 'Twoje daty są nieprawidłowe.';
        elseif (validateAndCompareDates($_POST["start_date_time"], $_POST["end_date_time"] )==2) $errors['date'] = 'Data rozpoczęcia zawsze musi być wcześniejsza niż zakończenia.';
        elseif (validateAndCompareDates($_POST["start_date_time"], $_POST["end_date_time"] )==3) $errors['date'] = 'Twoja data jest w przeszłości.';
        
        if ($_POST["type"]!=0) $event["type"] = $_POST["type"]; else $errors['type'] = 'Wybierz z listy';
        
        $event["start_date_time"]=$_POST["start_date_time"] ;
        $event["end_date_time"]=$_POST["end_date_time"] ;
        
        $event["created_by"] = $event["update_by"] = $_SESSION['user_id'];
        
        if (empty($_POST["invite"])) $errors['invite'] = 'Zaproś kogoś!';
        else $event["invite"] = json_encode($_POST["invite"]);
        
        $event["active"] = 1 ; 
    

    // Walidacja pól formularza (możesz dodać więcej warunków)
    if (!empty($errors)) {
        
        $error = "Wszystkie pola oznaczone gwiazdką są wymagane!";
        
    } else {
        // Dodanie użytkownika do bazy danych
        //`id`, `login`, `email`, `first_name`, `last_name`, `phone`, `password`, `salt`, `date_of_birth`, `roles`, `access`, `details`, `qr`, `created_by`, `created_date`, `update_by`, `update_date`
        
       // Złożenie danych w tablicę asocjacyjną
        if (insert('event', $event)) {
                header("Location: adminevents.html");
                exit();
        } else {
            $error = "Wystąpił problem podczas dodawania wydarzenia!";
        }
    }
}
?>

<h1>Dodawanie nowego wydarzenia</h1>
    
<?php include ('forms/eventForm.php'); ?>
<script>
            // Pobierz referencję do pierwszego checkboxa
        var mainCheckbox = document.getElementById("mainCheckbox");

        // Pobierz referencje do wszystkich pozostałych checkboxów
        var toggleCheckboxes = document.querySelectorAll(".toggleCheckbox");

        // Dodaj nasłuchiwacz zdarzeń, który będzie wykonywał funkcję, gdy stan pierwszego checkboxa zmieni się
             mainCheckbox.addEventListener("change", function() {
            // Dla każdego checkboxa, ustaw jego stan na taki sam, jak pierwszy checkbox
            toggleCheckboxes.forEach(function(checkbox) {
                checkbox.checked = mainCheckbox.checked;
            });
        });
</script>
