<?php if (!empty($registration_success)): ?>
    <div class="alert alert-success text-center" role="alert">Реєстрація пройшла успішно. Введіть логін та пароль, щоб увійти.</div>
<?php endif; ?>
<?php if (!empty($errors)): ?>
    <?php foreach($errors as $error): ?>
        <div class="alert alert-danger text-center" role="alert">
            <?= $error; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<div class="col-sm-6 col-md-4 col-xl-2 m-auto">
    <form method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" id="email" value="<?php if (!empty($email)) echo $email; ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" name="password" class="form-control" id="password" required>
        </div>
        <div class="text-center">
        <button type="submit" class="btn btn-primary m-auto">Увійти</button>
        </div>
    </form>
    <div class="text-center mt-2">
        <a href="/register">Зареєструватися</a>
    </div>
</div>
