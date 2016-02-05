<?php

if (is_post() && is_ajax() && $id = add_task(get_user_id())) {
    $message = array(
        'status' => 'success',
        'id'     => $id
    );
} else {
    $message = array(
        'status' => 'error'
    );
}

header('Content-Type: application/json');
echo json_encode($message);