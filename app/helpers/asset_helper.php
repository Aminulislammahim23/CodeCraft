<?php

if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
}

function css_url($file) {
    return CSS_URL . '/' . $file;
}

function js_url($file) {
    return JS_URL . '/' . $file;
}

function img_url($file) {
    return IMAGES_URL . '/' . $file;
}

function upload_url($file) {
    return UPLOADS_URL . '/' . $file;
}

function asset_url($path) {
    return ASSETS_URL . '/' . ltrim($path, '/');
}

function controller_url($path) {
    return CONTROLLERS_URL . '/' . ltrim($path, '/');
}

function view_url($path) {
    return VIEWS_URL . '/' . ltrim($path, '/');
}