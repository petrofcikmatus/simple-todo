<?php

// dump
function d() {
    foreach (func_get_args() as $arg) var_dump($arg);
}

// dump and die
function dd() {
    foreach (func_get_args() as $arg) d($arg);
    exit;
}

// vráti základnú url projektu
function url() {
    return BASE_URL;
}

// vráti text ošetrený od XSS útoku
function plain($string) {
    return htmlspecialchars($string, ENT_QUOTES); // potencialne nebezpečné, poradil Luboš Beran
    //return htmlentities($string, ENT_QUOTES, "UTF-8");
}

function healOutput($x = null) {
    if (!isset($x)) {
        return null;
    } else if (is_string($x)) {
        return plain($x);
    } else if (is_array($x)) {
        foreach ($x as $k => $v) {
            $x[$k] = healOutput($v);
        }
        return $x;
    }
    return $x;
}

//function experiment(){
//    extract(healOutput($data));
//    extract($data, EXTR_PREFIX_ALL, "unsafe_");
//}

// vráti true ak sa jedná o post request, inak false
function is_post() {
    return (isset($_SERVER["REQUEST_METHOD"]) && strtolower($_SERVER["REQUEST_METHOD"]) === "post");
}

// vráti true ak sa jedná o ajax request, inak false
function is_ajax() {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
}

// vráti segmenty v url pre segment() funkciu
function get_segments() {
    // najprv vyskladáme aktuálnu url adresu
    $current_url = "http";
    $current_url .= ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "s://" : "://");
    $current_url .= $_SERVER['HTTP_HOST'];
    $current_url .= $_SERVER['REQUEST_URI'];

    // odstránime z nej nepotrebnú časť url nastavenú v config súbore ako BASE_URL
    $path = str_replace(BASE_URL, "", $current_url);

    // naparsujeme cestu ktorá nam ostala, a odstránime z nej nepotrebné lomítka na začiatku a na konci
    $path = trim(parse_url($path, PHP_URL_PATH), '/');

    // rozbijeme cestu na segmenty podľa lomítok
    $segments = explode('/', $path);

    // vrátime segmenty
    return $segments;
}

// funkcia vracia segment podľa indexu, alebo false ak segment s takým indexom neexistuje
function segment($index) {
    // získame segmenty
    $segments = get_segments();

    // ak segment s daným indexom existuje, vrátime ho, inak vrátime false
    return isset($segments[$index - 1]) ? $segments[$index - 1] : false;
}

// funkcia na presmerovanie na inú stránku
function redirect($page = "/", $status_code = 301) {
    $page = str_replace(BASE_URL, '', $page);
    $page = ltrim($page, '/');

    $location = BASE_URL . "/" . $page;

    $codes = array(
        301 => "Moved Permanently",
        302 => "Found",
        303 => "See Other",
        307 => "Temporary Redirect",
    );

    if (!isset($codes[$status_code])) $status_code = 301;

    header("Location: " . $location, true, $status_code);
    header("Connection: close");
    exit;
}

// zobrazí stránku
function show_page($page) {
    if (!file_exists($file = APP_PATH . "/views/pages/" . $page . ".php")) exit("Application error: File from routes not found.");
    /** @noinspection PhpIncludeInspection */
    include_once($file);
}

// zobrazí 404 stránku
function show_404() {
    header("HTTP/1.0 404 Not Found");
    include_once(APP_PATH . "/views/errors/404.php");
    exit;
}

// pridá do kódu časť stránky zo zložky layout
function include_layout($file, $vars = array()) {
    extract($vars);
    include_once(APP_PATH . "/views/layout/" . $file . ".php");
}

// pridá do kódu vrchnú časť stránky s head časťou, headerom a navigáciou
function include_header($vars = array()) {
    include_layout("header", $vars);
}

// pridá do kódu spodnú časť stránky s footerom
function include_footer($vars = array()) {
    include_layout("footer", $vars);
}

// funkcia ktorá dokáže limitovať počet slov
function word_limiter($str, $limit = 100, $end_char = '&#8230;') {
    if (trim($str) === '') {
        return $str;
    }

    preg_match('/^\s*+(?:\S++\s*+){1,' . (int)$limit . '}/', $str, $matches);

    if (strlen($str) === strlen($matches[0])) {
        $end_char = '';
    }

    return rtrim($matches[0]) . $end_char;
}

function text_limiter($str, $limit = 100, $end_char = '&#8230;') {
    if (trim($str) === '') {
        return $str;
    }

    if (strlen($str) <= $limit) {
        $end_char = '';
    }

    return substr($str, 0, $limit) . $end_char;
}

// funkcia ktorá naformátuje text blogu aby mal html elementy
function add_paragraphs($str) {
    if (($str = trim($str)) === '') return '';

    $str = str_replace(array("\r\n", "\r"), "\n", $str);

    $str = preg_replace('~^[ \t]+~m', '', $str);
    $str = preg_replace('~[ \t]+$~m', '', $str);

    if ($html_found = (strpos($str, '<') !== false)) {
        $no_p = '(?:p|div|article|header|aside|hgroup|canvas|output|progress|section|figcaption|audio|video|nav|figure|footer|video|details|main|menu|summary|h[1-6r]|ul|ol|li|blockquote|d[dlt]|pre|t[dhr]|t(?:able|body|foot|head)|c(?:aption|olgroup)|form|s(?:elect|tyle)|a(?:ddress|rea)|ma(?:p|th))';

        $str = preg_replace('~^<' . $no_p . '[^>]*+>~im', "\n$0", $str);
        $str = preg_replace('~</' . $no_p . '\s*+>$~im', "$0\n", $str);
    }

    $str = '<p>' . trim($str) . '</p>';
    $str = preg_replace('~\n{2,}~', "</p>\n\n<p>", $str);

    if ($html_found !== false) {
        $str = preg_replace('~<p>(?=</?' . $no_p . '[^>]*+>)~i', '', $str);
        $str = preg_replace('~(</?' . $no_p . '[^>]*+>)</p>~i', '$1', $str);
    }

    $str = preg_replace('~(?<!\n)\n(?!\n)~', "<br>\n", $str);

    return $str;
}
