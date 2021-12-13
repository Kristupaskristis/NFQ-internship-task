<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

define('MSG_SUCCESS', 1);
define('MSG_ERROR', 2);

define('MAX_GROUP_NUMBER', 30);

function raise_msg(string $msg, int $status) : void
{
    $_SESSION['RAISE_MSG'] = [
        'msg'    => $msg,
        'status' => $status,
    ];
}

function get_mgs() : ?string
{
    if (empty($_SESSION['RAISE_MSG'])) {
        return null;
    }

    $msg = '
        <div class="alert alert-' . ($_SESSION['RAISE_MSG']['status'] === MSG_SUCCESS ? 'success' : 'error') . '"> ' .
            $_SESSION['RAISE_MSG']['msg']
        . ' </div>
    ';

    unset($_SESSION['RAISE_MSG']);
    return $msg;
}