<?php
// header.php — included at the top of every public page
// $pageTitle must be set by the including file before requiring this.
if (!isset($pageTitle)) $pageTitle = 'Contact Tracing System';
$isAdmin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | CoE Contact Tracing</title>
    <link rel="stylesheet" href="<?= $assetBase ?? '../' ?>assets/style.css">
</head>
<body>
<header>
    <div class="dept-logo">CoE</div>
    <div class="dept-info">
        <h1>Department of Computer Engineering</h1>
        <p>University of San Carlos &mdash; Contact Tracing System</p>
    </div>
    <nav>
        <?php if ($isAdmin): ?>
            <a href="<?= $assetBase ?? '../' ?>admin/dashboard.php">Dashboard</a>
            <a href="<?= $assetBase ?? '../' ?>admin/logout.php">Logout</a>
        <?php else: ?>
            <a href="<?= $assetBase ?? '../' ?>index.php">Home</a>
            <a href="<?= $assetBase ?? '../' ?>admin/login.php">Admin</a>
        <?php endif; ?>
    </nav>
</header>
<main>
