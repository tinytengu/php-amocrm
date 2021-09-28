<?php

function pre($content) {
    echo("<pre>" . print_r($content, true) . "</pre>");
}

function log_console($data) {
    echo '<script>';
    echo 'console.log('. json_encode($data) .')';
    echo '</script>';
}

function log_file($filename, $data, $flags=FILE_APPEND) {
    file_put_contents($filename, print_r($data, true), $flags);
}
