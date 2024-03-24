<div class="top-header">
    <div class="logo">
        <a href="/"><img src="<?=$site_url?>/images/logo.png" alt="Logo"></a>
    </div>
<div class="top-menu">
<nav>
    <ul>
        <li><a href="/">Strona główna</a></li>
        <!-- Rozwijane menu "Administracja" -->
        <?php if(isset($_SESSION['user_id'])): ?>
            <?php if(checkUserAccess(97)): ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Administracja</a>
                    <ul class="dropdown-menu">
                        <li><a href="/adminusers.html">Członkowie</a></li>
                        <li><a href="/adminevents.html">Wydarzenia</a></li>
                        <li><a href="/adminstats.html">Statystyki</a></li>
                    <?php if(checkUserAccess(99)): ?>
                        <li><a href="/adminsettings.html">Ustawienia</a></li>
                    <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>
        <?php endif; ?>
        
        <!-- Wylogowanie -->
        <?php if(isset($_SESSION['user_id'])): ?>
            <li class="dropdown">
            <a href="#" class="dropdown-toggle">Zalogowany jako: <?= getUserInfo($_SESSION['user_id'])["first_name"].' '.getUserInfo($_SESSION['user_id'])["last_name"] ?></a>
                    <ul class="dropdown-menu">
                        <li><a href="/details.html">Profil</a></li> 
                        <li><a href="/evaluation.html">Oceny</a></li> 
                        <li><a href="/finance.html">Finanse</a></li>
                        <li><a href="/calendar.html">Kalendarz</a></li>
                        <li><a href="/messages.html">Wiadomości</a></li>
                        <li><a href="/logout.html">Wyloguj</a></li>
                    </ul>    
        <?php else: ?>
            <li><a href="/login.html">Zaloguj</a></li>
        <?php endif; ?>
    </ul>
</nav>

</div>

</div>
 
