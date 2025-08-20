<?php
// lib.php - shared HTML helpers

/**
 * Escapes a string for safe HTML output to prevent XSS.
 */
function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

/**
 * Renders the HTML header and navigation.
 */
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

/**
 * Renders the HTML footer.
 */
function footer_html() {
    echo '</main><footer><small>Demo app for Cloud Computing Lab (PaaS on Azure App Service)</small></footer></body></html>';
}
?>