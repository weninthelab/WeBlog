<?php
define('BASE_PATH', __DIR__);
define('BASE_URL', '/WeBlog');

$conn = new mysqli("localhost", "root", "", "weblog");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
