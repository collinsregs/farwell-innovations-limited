<?php
// includes/functions.php

// Sanitize input
function sanitize($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

// Redirect helper
function redirect($url)
{
    header("Location: $url");
    exit();
}
function setToast($message, $type)
{
    $_SESSION['toast'] = [
        'message' => $message,
        'type' => $type // 'success' or 'error'
    ];
}
function getToast()
{
    if (isset($_SESSION['toast'])) {
        $toast = $_SESSION['toast'];
        unset($_SESSION['toast']); // Clear after retrieving
        return json_encode($toast); // Return as JSON
    }
    return null;
}
?>