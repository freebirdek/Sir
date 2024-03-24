<form action="" method="post">
         <?php if (isset($user['id'])): ?>
         
         <input type="hidden" name="user_id" value="<?= (isset($user['id'])) ? $user['id'] : ''; ?>">
        
        <?php endif; ?>
        
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default">Email *</span>
            <input type="text" class="form-control" name="email" aria-describedby="inputGroup-sizing-default" value="<?= (isset($user['email'])) ? $user['email'] : ''; ?>">
            <?php if(isset($errors['email'])) { echo '<span class="text-danger">' . $errors['email'] . '</span>'; } ?>
        </div>        
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default">Imię *</span>
            <input type="text" class="form-control" name="first_name" aria-describedby="inputGroup-sizing-default" value="<?= (isset($user['first_name'])) ? $user['first_name'] : ''; ?>">
            <?php if(isset($errors['first_name'])) { echo '<span class="text-danger">' . $errors['first_name'] . '</span>'; } ?>
        </div> 
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default">Nazwisko *</span>
            <input type="text" class="form-control" name="last_name" aria-describedby="inputGroup-sizing-default" value="<?= (isset($user['last_name'])) ? $user['last_name'] : ''; ?>">
            <?php if(isset($errors['last_name'])) { echo '<span class="text-danger">' . $errors['last_name'] . '</span>'; } ?>
        </div> 
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default">Numer telefonu *</span>
            <input type="text" class="form-control" name="phone" aria-describedby="inputGroup-sizing-default" value="<?= (isset($user['phone'])) ? $user['phone'] : ''; ?>">
            <?php if(isset($errors['phone'])) { echo '<span class="text-danger">' . $errors['phone'] . '</span>'; } ?>
        </div>                
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default">Data urodzenia *</span>
            <input type="date" class="form-control" name="date_of_birth" aria-describedby="inputGroup-sizing-default" value="<?= (isset($user['date_of_birth'])) ? $user['date_of_birth'] : ''; ?>">
            <?php if(isset($errors['date_of_birth'])) { echo '<span class="text-danger">' . $errors['date_of_birth'] . '</span>'; } ?>
        </div>         
        
       <h4>Rola w stowarzyszeniu</h4>
        <div class="form-check">
        
         <?= generujRoleCheckbox((isset($user['roles'])) ? json_decode($user['roles']): ''); ?>
         
         <?php if(isset($errors['roles'])) { echo '<span class="text-danger">' . $errors['roles'] . '</span>'; } ?>
        </div>
        <h4>Dostęp</h4>
        <div class="input-group mb-3">
            <select class="form-select input-group-text" aria-label="Default select example" name="access">
                <option value="0">Wybierz opcję</option>
                
                <?= AccessSelectForm((isset($user['access'])) ? $user['access'] : '');?>
                
            </select>
            <?php if(isset($errors['access'])) { echo '<span class="text-danger">' . $errors['access'] . '</span>'; } ?>
      </div>  
        
        <h4>Notatka</h4>
        <div class="form-floating">
                <textarea class="form-control" placeholder="Zostaw notatkę" id="floatingTextarea2"></textarea>
        </div>        

        <!-- Tutaj dalsza część formularza -->

   <div class="mt-5">
        <input type="submit" value="Zapisz"  class="btn btn-primary">
  </div>
</form>
