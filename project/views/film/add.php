<div>
    <a href="/">Повернутися до списку фільмів</a>
</div>
<h1 class="text-center my-3">Додати новий фільм</h1>
<?php if (!empty($success)): ?>
    <div class="alert alert-success text-center" role="alert">
        <?= $success; ?>
    </div>
<?php endif; ?>
<?php if (!empty($errors)): ?>
    <?php foreach($errors as $error): ?>
        <div class="alert alert-danger text-center" role="alert">
            <?= $error; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<div class="col-lg-6 m-auto">
    <form method="POST">
        <div class="form-group">
            <label for="title">Назва</label>
            <input type="text" name="title" class="form-control" id="title" value="<?php if (!empty($title)) echo $title; ?>" required>
        </div>
        <div class="form-group">
            <label for="year">Рік випуску</label>
            <input type="number" name="year" class="form-control" id="year" min="1900" max="2100" step="1" value="<?php if (!empty($year)) echo $year; ?>" required>
        </div>
        <div class="form-group">
            <label for="actors">Список акторів</label>
            <textarea class="form-control" name="actors" id="actors" placeholder="Введіть імена та прізвища акторів через кому" required><?php if (!empty($actors)) echo $actors; ?></textarea>
        </div>
        <div class="form-group">
            <label for="formats">Доступні формати</label>
            <select multiple class="form-control" id="formats" name="formats[]" required>
                <option <?php if (!empty($formats) && in_array('Blu-ray', $formats)) echo 'selected'; ?>>Blu-ray</option>
                <option <?php if (!empty($formats) && in_array('DVD', $formats)) echo 'selected'; ?>>DVD</option>
                <option <?php if (!empty($formats) && in_array('VHS', $formats)) echo 'selected'; ?>>VHS</option>
            </select>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary m-auto">Додати</button>
        </div>
    </form>
</div>
