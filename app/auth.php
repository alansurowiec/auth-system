<?php
require_once __DIR__ . '/helpers.php';

function current_user()
{
    return $_SESSION['user'] ?? null;
}

function require_guest()
{
    if (current_user()) {
        redirect('dashboard.php');
    }
}

function require_login()
{
    if (!current_user()) {
        redirect('login.php');
    }
}