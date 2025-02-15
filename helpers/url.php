<?php
include_once BASE_PATH.DS."bootstrap.php";

use App\Bootstrap;

function uploadedFileUrl($file_name) {
    $base_url = rtrim(getBaseUrl(), '/');
    return $base_url . '/files/' . urlencode($file_name);
}

function getBaseUrl() {
    // $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    // $host = $_SERVER['HTTP_HOST'];
    // return $protocol . '://' . $host;
    return  Bootstrap::getInstance()->getConfig("base_url") ?? "/";
}
?>
