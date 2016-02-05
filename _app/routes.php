<?php
// Podstránky ktoré sú dostupné sa zapisujú do tohoto poľa ako dvojice "link" => "súbor" bez koncovky.
// Súbory sa nachádzajú v _app/views/pages. Ak sú v podzložke, tak sa zapíše ako "link" => "zložka/súbor".
return array(
    ""             => "index",
    "show"         => "index",
    "add"          => "add",
    "edit"         => "edit",
    "delete"       => "delete",
    // ...
    "ajax"         => "ajax",
    // ...
    "registration" => "registration", // stránka na registrovanie
    "login"        => "login", // stránka na prihlásenie
    "logout"       => "logout", // stránka na odhlásenie
);
