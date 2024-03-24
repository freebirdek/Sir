<?php
ini_set('session.save_path', '/tmp');
session_start();

include 'config.php';

include 'header.php'; 
?>
<body>
<?php include 'top.php';?>
<?php
// Sprawdź, czy zmienna $_GET['page'] została przekazana
if(isset($_GET['page'])) {
    // Pobierz nazwę pliku z parametru $_GET['page']
    $page_name = $_GET['page'];
    
    // Ustal ścieżkę do pliku w folderze pages
    $file_path = 'pages/' . $page_name . '.php';

    // Sprawdź, czy plik o podanej ścieżce istnieje
    if(file_exists($file_path)) {
        include $file_path; // Jeśli plik istnieje, załaduj go
    } else {
        include 'pages/404.php'; // Jeśli plik nie istnieje, załaduj stronę błędu 404
    }
} else {
    // Jeśli zmienna $_GET['page'] nie została przekazana, wyświetl domyślną stronę (można to dostosować do swoich potrzeb)
    include 'pages/home.php';
}
?>

<?php include 'footer.php'; ?>
</body>
</html>
