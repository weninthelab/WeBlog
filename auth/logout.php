<?php
include_once '../config.php';
include_once BASE_PATH . '/includes/header.php';

session_start();
session_destroy();
header('Location: ' . BASE_URL . '/auth/login.php');
