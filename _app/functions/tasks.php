<?php

function encryptText($text) {
    $secret = defined("ENCRYPT_SECRET") ? ENCRYPT_SECRET : "something" ;
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("aes-256-cbc"));
    return base64_encode($iv . openssl_encrypt($text, 'aes-256-cbc', $secret, 0, $iv));
}

function decryptText($text) {
    $secret = defined("ENCRYPT_SECRET") ? ENCRYPT_SECRET : "something" ;
    $text = base64_decode($text);
    $iv_size = openssl_cipher_iv_length("aes-256-cbc");
    $iv = substr($text, 0, $iv_size);
    return openssl_decrypt(substr($text, $iv_size), 'aes-256-cbc', $secret, 0, $iv);
}

function add_task($user_id) {
    $task_text = isset($_POST["text"]) ? trim($_POST["text"]) : "";

    if ("" == $task_text) {
        if (!is_ajax()) add_message("You forgot write something.");
        return false;
    }

    $db = DB::getInstance();

    $db->query(
        "INSERT INTO tasks (task_uid, task_text, task_encrypted) VALUES (:user_id, :task_text, :task_encrypted)",
        array("user_id" => $user_id, "task_text" => encryptText($task_text), "task_encrypted" => true)
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
        array("user_id" => $user_id, "task_id" => $task_id, "task_text" => encryptText($task_text))
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

    add_message("Task has been deleted.");
    return true;
}

function get_tasks($user_id) {

    $tasks = DB::getInstance()->queryAll(
        "SELECT * FROM tasks WHERE task_uid = :user_id ORDER BY task_id DESC",
        array("user_id" => $user_id)
    );

    foreach ($tasks as $key => $task) {
        if ($task['task_encrypted']) {
            $tasks[$key]['task_text'] = decryptText($task['task_text']);
        }
    }

    return $tasks;
}

function get_task($user_id, $task_id) {

    $task = DB::getInstance()->queryRow(
        "SELECT * FROM tasks WHERE task_uid = :user_id AND task_id = :task_id",
        array("user_id" => $user_id, "task_id" => $task_id)
    );

    if ($task['task_encrypted']) {
        $task['task_text'] = decryptText($task['task_text']);
    }

    return $task;
}