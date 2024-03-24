<?php

// Funkcja do wykonania zapytań SQL (SELECT, INSERT, UPDATE, DELETE)
function executeQuery($sql) {
    global $conn;
    $result = $conn->query($sql);
    return $result;
}

// Funkcja do pobierania wyników zapytania SELECT w postaci tablicy asocjacyjnej
function fetchAll($sql) {
    $result = executeQuery($sql);
    $rows = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    return $rows;
}

// Funkcja do pobierania pojedynczego wiersza wyników zapytania SELECT w postaci tablicy asocjacyjnej
function fetchOne($sql) {
    $result = executeQuery($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;
    }
    return null;
}

// Funkcja do wstawiania danych do bazy danych (INSERT)
function insert($table, $data,$showerror=false) {
    global $conn;
    $columns = implode(", ", array_keys($data));
    $values = "'" . implode("', '", array_values($data)) . "'";
    $sql = "INSERT INTO ".PREFIX."$table ($columns) VALUES ($values)";
    
    if ($showerror) echo $sql;
    
    return executeQuery($sql);
}

// Funkcja do aktualizacji danych w bazie danych (UPDATE)
function update($table, $data, $condition) {
    global $conn;
    $set = '';
    foreach ($data as $key => $value) {
        $set .= "$key = '$value', ";
    }
    $set = rtrim($set, ', ');
    $sql = "UPDATE ".PREFIX."$table SET $set WHERE $condition";
    return executeQuery($sql);
}

// Funkcja do usuwania danych z bazy danych (DELETE)
function delete($table, $condition) {
    global $conn;
    $sql = "DELETE FROM ".PREFIX."$table WHERE $condition";
    return executeQuery($sql);
}

?>
