<?php

function session_get($key, $default = null) {
    return $_SESSION[$key] ?? $default;
}

function session_set($key, $value) {
    $_SESSION[$key] = $value;
}
