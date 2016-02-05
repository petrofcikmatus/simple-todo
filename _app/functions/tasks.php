<?php

function add_task($user_id) {
    $task_text = isset($_POST["text"]) ? trim($_POST["text"]) : "";

    if ("" == $task_text) {
        if (!is_ajax()) add_message("You forgot write something.");
        return false;
    }

    $db = DB::getInstance();

    $db->query(
        "INSERT INTO tasks (task_uid, task_text) VALUES (:user_id, :task_text)",
        array("user_id" => $user_id, "task_text" => $task_text)
    );

    if (!is_ajax()) add_message("New task has been added.");
    return $db->getLastId("tasks");
}

function edit_task($user_id, $task_id) {
    $task_text = isset($_POST["text"]) ? trim($_POST["text"]) : "";

    if ("" == $task_text) {
        add_message("You forgot write something.");
        return false;
    }

    DB::getInstance()->query(
        "UPDATE tasks SET task_text = :task_text WHERE task_uid = :user_id AND task_id = :task_id",
        array("user_id" => $user_id, "task_id" => $task_id, "task_text" => $task_text)
    );

    add_message("Task has been edited.");
    return true;
}

function delete_task($user_id, $task_id) {

    $result = DB::getInstance()->query(
        "DELETE FROM tasks WHERE task_uid = :user_id AND task_id = :task_id",
        array("user_id" => $user_id, "task_id" => $task_id)
    );
    if (!$result) {
        add_message("Somewhere is some wild error.");
        return false;
    }

    add_message("Task has been edited.");
    return true;
}

function get_tasks($user_id) {
    return DB::getInstance()->queryAll(
        "SELECT * FROM tasks WHERE task_uid = :user_id ORDER BY task_id DESC",
        array("user_id" => $user_id)
    );
}

function get_task($user_id, $task_id){
    return DB::getInstance()->queryRow(
        "SELECT * FROM tasks WHERE task_uid = :user_id AND task_id = :task_id",
        array("user_id" => $user_id, "task_id" => $task_id)
    );
}