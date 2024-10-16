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
?>