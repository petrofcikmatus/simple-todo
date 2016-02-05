<?php

if (!is_logged_in()) {
    redirect("login");
}

if (is_post()) {
    if (delete_task(get_user_id(), segment(2))) {
        redirect();
    }
}

$task = get_task(get_user_id(), segment(2));

if (!$task) {
    show_404();
}

include_header(array("title" => "delete"));

?>
    <div class="row">
        <div class="col-md-12">
            <form id="js-form-delete" method="post">
                <div class="form-group">
                    <textarea disabled class="form-control" id="js-text" rows="4" title=""><?= plain($task["task_text"]) ?></textarea>
                </div>
                <div class="form-group">
                    <input name="id" type="hidden" value="<?= plain($task["task_id"]) ?>">
                    <button class="btn btn-sm btn-danger" type="submit">delete task</button>
                    <a href="<?= url() ?>" class="btn btn-sm btn-default">back</a>
                </div>
            </form>
        </div>
    </div>
<?php include_footer(); ?>