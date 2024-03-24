<?php

if (!checkUserAccess(97)) {
    // Przekieruj użytkownika na inną stronę, jeśli nie ma wymaganej roli
    header("Location: login.html");
    exit();
}




?>


<h1>Statystyki</h1>

 <button class="accordion">Statystyki członków stowarzyszenia</button>
<div class="panel">
  <p>Lorem ipsum...</p>
</div>

<button class="accordion">Statystyki wydarzeń</button>
<div class="panel">
  <p>Lorem ipsum...</p>
</div>

<button class="accordion">Statystyki płatności</button>
<div class="panel">
  <p>Lorem ipsum...</p>
</div> 

