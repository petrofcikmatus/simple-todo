<?php

if (is_logged_in()){
    redirect();
}

if (is_post()) {
    if (do_login()) {
        redirect();
    }
}

include_header(array("title" => "login"));

?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <form id="login-form" method="post">
            <div class="form-group">
                <label for="inputEmail" class="sr-only">email address</label>
                <input type="email" name="email" id="inputEmail" class="form-control" placeholder="email address"
                       value="<?= (isset($_POST["email"]) ? plain($_POST["email"]) : "") ?>" required autofocus>
            </div>
            <div class="form-group">
                <label for="inputPassword" class="sr-only">password</label>
                <input type="password" name="password" id="inputPassword" class="form-control"
                       placeholder="password"
                       required>
            </div>
            <div class="form-group">
                <button class="btn btn-sm btn-primary" type="submit">login</button>
                <a href="<?= url() ?>/registration" class="btn btn-sm btn-default">registration</a>
            </div>
        </form>
    </div>
</div>
<?php include_footer(); ?>
