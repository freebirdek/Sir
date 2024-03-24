<p>Copyright &copy; 2024 Roland Czaczyk</p>

<?php if (isset($_SESSION["user_id"])) : ?>

<div class="chat-button-container">
    <div class="chat-button" onclick="location.href='/messages.html';">
        <i class="bi bi-chat-square-dots"></i>
        <div class="chat-counter"><?=countUnreadMessages($_SESSION["user_id"])?></div>
    </div>
</div>

<?php endif; ?>

    <!-- Skrypty JavaScript Bootstrapa (opcjonalne) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>

             
    
function toggleForm(formName) {
    var formDiv = document.getElementById(formName);
    if (formDiv.style.display === 'none') {
        formDiv.style.display = 'block';
    } else {
        formDiv.style.display = 'none';
    }
}

var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    /* Toggle between adding and removing the "active" class,
    to highlight the button that controls the panel */
    this.classList.toggle("active");

    /* Toggle between hiding and showing the active panel */
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
} 


             
    </script>

