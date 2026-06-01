<?php
if (!isset($pageTitle)) $pageTitle = 'Contact Tracing System';
$isAdmin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | USC Contact Tracing</title>
    <link rel="stylesheet" href="<?= $assetBase ?? '../' ?>assets/style.css">
</head>
<body>
<header>
    <!-- USC Seal -->
    <div class="usc-seal">
        <img src="<?= $assetBase ?? '../' ?>assets/usc-logo.png" alt="University of San Carlos" class="usc-seal-img">
    </div>

    <div class="dept-info">
        <h1>University of San Carlos</h1>
        <p>Department of Computer Engineering</p>
        <span class="dept-sub">Contact Tracing &amp; Visitor Management System</span>
    </div>

    <nav>
        <?php if ($isAdmin): ?>
            <a href="<?= $assetBase ?? '../' ?>admin/dashboard.php">&#128203; Dashboard</a>
            <a href="<?= $assetBase ?? '../' ?>admin/logout.php">&#128274; Logout</a>
        <?php else: ?>
            <a href="<?= $assetBase ?? '../' ?>index.php">&#127968; Home</a>
            <a href="<?= $assetBase ?? '../' ?>admin/login.php">&#128274; Admin</a>
        <?php endif; ?>
    </nav>
</header>
<main>
