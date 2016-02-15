<?php

if (!is_logged_in()) {
    redirect("login");
}

if (is_post()) {
    if (add_task(get_user_id())) {
        redirect();
    }
}

$tasks = get_tasks(get_user_id());

include_header(array("title" => "your todo list", "show_logout" => true)) ?>
    <div class="row">

        <div class="col-md-12">
            <form id="js-form-add" method="post">
                <div class="form-group">
                        <textarea class="form-control" name="text" id="js-text" rows="4"
                                  placeholder="is there something to do?"><?= (isset($_POST["text"]) ? plain($_POST["text"]) : "") ?></textarea>
                </div>
                <div class="form-group js-hide">
                    <button class="btn btn-sm btn-danger" type="submit">add new task</button>
                </div>
            </form>
        </div>

        <div class="col-md-12">
            <ul id="js-list" class="list-group">
                <?php foreach ($tasks as $task): ?>
                    <li id="item-<?= $task["task_id"] ?>" class="list-group-item item-in-the-list">
                        <?= plain($task["task_text"]) ?>
                        <div class="controls pull-right">
                            <a href="<?= url() ?>/edit/<?= $task["task_id"] ?>" class="text-muted edit-link">edit</a>
                            <a href="<?= url() ?>/delete/<?= $task["task_id"] ?>"
                               class="delete-link text-muted glyphicon glyphicon-remove"></a>
                        </div>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>

    </div>
<?php include_footer(); ?>