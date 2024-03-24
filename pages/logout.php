<?php
// Zniszcz sesję
session_destroy();

// Przekieruj użytkownika na stronę logowania lub inną stronę
header("Location: /");
exit();
?>
 
