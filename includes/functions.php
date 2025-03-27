<?php
function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function current_user()
{
    return $_SESSION['username'] ?? 'Guest';
}
