<div class="card my-5" >
    <div class="card-body">
        <h1 class="text-center"><?= $translates['login_header'] ?></h1>
    </div>
</div>

<form action="/login" method="post" id="login-form">
    <div class="form-group mb-2">
        <label for="email" class="mb-2"><?= $translates['email'] ?></label>
        <input type="email" name="email" id="email" class="form-control" required>
    </div>
    <div class="form-group mb-3">
        <label for="password" class="mb-2"><?= $translates['password'] ?></label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <div class="form-group">
        <input type="submit" name="login" value="<?= $translates['login_btn'] ?>" class="btn btn-primary">
    </div>
    <input type="hidden" name="csrf" value="<?=$csrf?>"/>
</form>