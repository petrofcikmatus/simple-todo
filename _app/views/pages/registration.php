<?php

if (is_logged_in()){
    redirect();
}

if (is_post()) {
    if (do_register()) {
        redirect("login");
    }
}

include_header(array("title" => "registration"));

?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <form id="login-form" method="post">
            <div class="form-group">
                <label for="inputName" class="sr-only">name</label>
                <input type="text" name="name" id="inputName" class="form-control" placeholder="name"
                       value="<?= (isset($_POST["name"]) ? plain($_POST["name"]) : "") ?>" required autofocus>
            </div>
            <div class="form-group">
                <label for="inputEmail" class="sr-only">email address</label>
                <input type="email" name="email" id="inputEmail" class="form-control" placeholder="email address"
                       value="<?= (isset($_POST["email"]) ? plain($_POST["email"]) : "") ?>" required>
            </div>
            <div class="form-group">
                <label for="inputPassword" class="sr-only">password</label>
                <input type="password" name="password" id="inputPassword" class="form-control"
                       placeholder="password"
                       required>
            </div>
            <div class="form-group">
                <label for="inputPasswordConfirm" class="sr-only">password again</label>
                <input type="password" name="passwordConfirm" id="inputPasswordConfirm" class="form-control"
                       placeholder="password again"
                       required>
            </div>
            <div class="form-group">
                <button class="btn btn-sm btn-primary" type="submit">register</button>
                <a href="<?= url() ?>/login" class="btn btn-sm btn-default">login</a>
            </div>
        </form>
    </div>
</div>
<?php include_footer(); ?>
