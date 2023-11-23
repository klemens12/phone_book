<div class="card my-5" >
    <div class="card-body">
        <h1 class="text-center"><?= $translates['register_header'] ?></h1>
    </div>
</div>

<form action="/register" method="post" id="register-form">
    <div class="form-group mb-2">
        <label for="email" class="mb-2"><?= $translates['login'] ?></label>
        <input type="text" name="login" id="login" value="<?=$prev_login?>" class="form-control" required>
    </div>
    <div class="form-group mb-2">
        <label for="email" class="mb-2"><?= $translates['email'] ?></label>
        <input type="email" name="email" id="email" value="<?=$prev_email?>" class="form-control" required>
    </div>
    <div class="form-group mb-3">
        <label for="password" class="mb-2"><?= $translates['password'] ?></label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <div class="form-group">
        <input type="submit" value="<?= $translates['register_btn'] ?>" class="btn btn-primary">
    </div>
    <input type="hidden" name="csrf" value="<?=$csrf?>"/>
</form>