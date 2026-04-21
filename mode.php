<?php
// Toggle mode
if(isset($_COOKIE['mode']) && ($_COOKIE['mode'] === 'night')) {
    // Switch to day mode
    setcookie('mode', 'day', time() + (86400 * 30), "/"); // Set cookie for 30 days
} else {
    // Switch to night mode
    setcookie('mode', 'night', time() + (86400 * 30), "/"); // Set cookie for 30 days
}

// Redirect back to the referring page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>
