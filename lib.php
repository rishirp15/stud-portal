<?php
// lib.php - shared helpers for simple JSON persistence on Azure App Service
function data_dir() {
    $home = getenv('HOME');
    if (!$home) {
        // Fallbacks for Windows/Linux
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $home = 'D:\\home';
        } else {
            $home = '/home';
        }
    }
    $dir = rtrim($home, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'data';
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
    return $dir;
}

function data_path($filename) {
    return data_dir() . DIRECTORY_SEPARATOR . $filename;
}

function load_json($filename) {
    $path = data_path($filename);
    if (!file_exists($path)) {
        return [];
    }
    $json = file_get_contents($path);
    $data = json_decode($json, true);
    if (!is_array($data)) $data = [];
    return $data;
}

function save_json($filename, $data) {
    $path = data_path($filename);
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $tmp = $path . '.tmp';
    file_put_contents($tmp, $json, LOCK_EX);
    rename($tmp, $path);
}

function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

function header_html($title = 'Student Portal') {
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . e($title) . '</title><link rel="stylesheet" href="styles.css"></head><body>';
    echo '<header><h1>Student Portal</h1><nav>';
    echo '<a href="index.php">Home</a>';
    echo '<a href="enrollments.php">Enrollments</a>';
    echo '<a href="attendance.php">Attendance</a>';
    echo '<a href="schedules.php">Schedules</a>';
    echo '</nav></header><main>';
}

function footer_html() {
    echo '</main><footer><small>Demo app for Cloud Computing Lab (PaaS on Azure App Service)</small></footer></body></html>';
}
?>