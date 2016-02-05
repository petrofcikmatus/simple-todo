<?php

// Povedzme že prihláseného užívateľa budeme poznať podľa záznamu v $_SESSION poli.
// Bude pod kľúčom "user" a bude mať tieto dostupné vlastnosti: id, name, email, is_admin (bool)

// pracujeme so session
if (!session_id()) @session_start();

// vráti true ak je užívateľ prihlásený
function is_logged_in() {
    return (isset($_SESSION["user"]) && is_array($_SESSION["user"]));
}

function get_user_id() {
    return (int)(isset($_SESSION["user"]["user_id"]) ? $_SESSION["user"]["user_id"] : 0);
}

function get_user_name() {
    return (isset($_SESSION["user"]["user_name"]) ? $_SESSION["user"]["user_name"] : "unknown man");
}

// vráti hash hesla, pretože do databázy sa nesmie heslo ukladať tak ako ho užívateľ zadal
function get_hash($password) {
    return hash("sha512", $password);
}

// zaregistruje užívateľa
function do_register() {

    $name = isset($_POST["name"]) ? $_POST["name"] : "";
    if (trim($name) == "") {
        add_message("You have to enter email.");
        return false;
    }

    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    if (trim($email) == "") {
        add_message("You have to enter email.");
        return false;
    }

    $password = isset($_POST["password"]) ? $_POST["password"] : "";
    if (trim($password) == "") {
        add_message("You have to enter password.");
        return false;
    }

    $passwordConfirm = isset($_POST["passwordConfirm"]) ? $_POST["passwordConfirm"] : "";
    if (trim($passwordConfirm) == "") {
        add_message("You have to enter password again.");
        return false;
    }

    if ($password !== $passwordConfirm) {
        add_message("Passwords have to match.");
        return false;
    }

    /*if (!isset($_POST["accept-of-terms"])) {
        add_message("Musíte súhlasiť s podmienkami.");
        return false;
    }*/

    $db = DB::getInstance();

    if (0 != $db->queryOne("SELECT COUNT(user_id) FROM users WHERE user_email = :user_email", array("user_email" => $email))) {
        add_message("You have to choose diferent email.");
        return false;
    }

    try {
        $affected_rows = $db->query(
            "INSERT INTO users (user_name, user_email, user_password) VALUES (:user_name, :user_email, :user_password)",
            array("user_name" => $name, "user_email" => $email, "user_password" => get_hash($password)));
    } catch (PDOException $e) {
        add_message("Application error: " . $e->getMessage());
        return false;
    }

    if (1 != $affected_rows) {
        add_message("Application error: ???");
        return false;
    }

    add_message("Congratulations! Now you can log in.");
    return true;
}

// prihlási užívateľa ak zadal správny email a heslo
function do_login() {

    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    if (trim($email) == "") {
        add_message("You have to enter email.");
        return false;
    }

    $password = isset($_POST["password"]) ? $_POST["password"] : "";
    if (trim($password) == "") {
        add_message("You have to enter password.");
        return false;
    }

    try {
        $db = DB::getInstance();

        $user = $db->queryRow(
            "SELECT * FROM users WHERE user_email = :user_email AND user_password = :user_password",
            array("user_email" => $email, "user_password" => get_hash($password))
        );

        if (empty($user)) {
            add_message("Email or password are not correct.");
            return false;
        }
    } catch (PDOException $e) {
        add_message("Application error: " . $e->getMessage());
        return false;
    }

    $_SESSION["user"] = $user;
    add_message("Welcome " . get_user_name() . "!");
    return true;
}

// odhlási užívateľa
function do_logout() {
    unset($_SESSION["user"]);
    add_message("Good bye!");
    return true;
}
