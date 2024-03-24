<form action="" method="post">
    <?php if (isset($event['id'])): ?>
        <input type="hidden" name="event_id" value="<?= (isset($event['id'])) ? $event['id'] : ''; ?>">
    <?php endif; ?>
    
    <h4>Typ wydarzenia</h4>
    <div class="input-group mb-3">
        <select  class="form-select input-group-text" aria-label="Default select example" id="type" name="type">
            <option value="0">Wybierz opcję</option>
            <option value="Akcja" <?= (isset($event['type']) && $event['type']=='Akcja') ? 'selected' : ''; ?>>Akcja poszukiwawcza</option>
            <option value="Szkolenie" <?= (isset($event['type']) && $event['type']=='Szkolenie') ? 'selected' : ''; ?>>Szkolenie</option>
            <option value="Zebranie" <?= (isset($event['type']) && $event['type']=='Zebranie') ? 'selected' : ''; ?>>Zebranie</option>
            <option value="Psy" <?= (isset($event['type']) && $event['type']=='Psy') ? 'selected' : ''; ?>>Ćwiczenia z psami</option>
            <option value="Inne" <?= (isset($event['type']) && $event['type']=='Inne') ? 'selected' : ''; ?>>Inne</option>   
        </select>
        <?php if(isset($errors['type'])) { echo '<span class="text-danger">' . $errors['type'] . '</span>'; } ?>
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text" id="inputGroup-sizing-default">Nazwa *</span>
        <input type="text" class="form-control" name="name" id="name" value="<?= (isset($event['name'])) ? $event['name'] : ''; ?>">
        <?php if(isset($errors['name'])) { echo '<span class="text-danger">' . $errors['name'] . '</span>'; } ?>
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text" id="inputGroup-sizing-default">Data i czas rozpoczęcia *</label>
        <input type="datetime-local" class="form-control" name="start_date_time" id="start_date_time" value="<?= (isset($event['start_date_time'])) ? $event['start_date_time'] : ''; ?>">
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text" id="inputGroup-sizing-default">Data i czas zakończenia *</label>
        <input type="datetime-local" class="form-control" name="end_date_time" id="end_date_time" value="<?= (isset($event['end_date_time'])) ? $event['end_date_time'] : ''; ?>">
        <?php if(isset($errors['date'])) { echo '<span class="text-danger">' . $errors['date'] . '</span>'; } ?>
    </div>

    <h4>Opis</h4>
    <div class="input-group mb-3">
        <textarea class="form-control" name="details" id="details"><?= (isset($event['details'])) ? $event['details'] : ''; ?></textarea>
        <?php if(isset($errors['details'])) { echo '<span class="text-danger">' . $errors['details'] . '</span>'; } ?>
    </div>

    <h4>Zaproszenie</h4>
    <div class="form-check">
        <input type="checkbox" name="users[]" id="mainCheckbox" class="form-check-input"> 
        <label class="form-check-label" for="mainCheckbox"><strong>Zaznacz wszystko / Odznacz wszystko</strong></label><br><br>

        <?= generujUserCheckbox("invite",(isset($event['invite'])) ? json_decode($event['invite']): ''); ?>

        <?php if(isset($errors['invite'])) { echo '<span class="text-danger">' . $errors['invite'] . '</span>'; } ?>
    </div>
    <div class="mt-5">
        <input type="submit" value="Zapisz" class="btn btn-primary">
    </div>
</form>

