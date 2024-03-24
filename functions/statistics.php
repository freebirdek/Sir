<?php

//`id`, `name`, `active`, `start_date_time`, `end_date_time`, `details`, `type`, `invite`, `interest`, `attend`, `close`, `created_by`, `created_date`, `update_by`, `update_date`

function showPercent($liczba, $podstawa) {
    // Sprawdź czy podstawa jest równa zero
    if ($podstawa == 0) {
        return "Podstawa nie może być równa zero.";
    }

    // Oblicz procent i zaokrągl do dwóch miejsc po przecinku
    $procent = ($liczba / $podstawa) * 100;
    $procent = round($procent, 2);

    // Zwróć wynik
    return $procent . "%";
}


// liczy ilość wydarzeń na których był obecny dany użytkownik
function showAllEvent($type = 0) {
    $sql = "SELECT COUNT(*) as total FROM sir_event WHERE ";
    
    if ($type === 0) {
        $sql .= "1=1";
    } else {
        $sql .= "type = '$type'";
    }

    $result = executeQuery($sql);
    $row = mysqli_fetch_assoc($result);
    $total = $row['total'];

    return $total;
}


// liczy ilość wydarzeń na których był obecny dany użytkownik
function showEventAttend($user, $type = 0) {
    $sql = "SELECT COUNT(*) as total FROM sir_event WHERE ";
    
    if ($type === 0) {
        $sql .= "1=1";
    } else {
        $sql .= "type = '$type'";
    }

    // Dodaj warunek sprawdzający udział użytkownika w wydarzeniu
    $sql .= " AND attend LIKE '%$user%'";

    $result = executeQuery($sql);
    $row = mysqli_fetch_assoc($result);
    $total = $row['total'];

    return $total;
}




?>
