<?php
session_start();
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Welcome';
$assetBase = './';
require_once __DIR__ . '/includes/header.php';
?>

<div class="card text-center">
    <div class="card-title" style="border:none;font-size:1.6rem;">
        Welcome to the CoE Contact Tracing System
    </div>
    <p style="color:#666;margin-bottom:28px;">
        Please select how you would like to sign in today.
    </p>

    <div class="choice-grid">
        <a class="choice-btn" href="register.php">
            <span class="icon">&#128221;</span>
            First-Time Visitor
            <span style="font-size:.82rem;font-weight:400;opacity:.85;">Register &amp; sign in</span>
        </a>

        <a class="choice-btn secondary" href="returning.php">
            <span class="icon">&#128100;</span>
            Returning Visitor
            <span style="font-size:.82rem;font-weight:400;opacity:.75;">Already registered? Sign in here</span>
        </a>

        <a class="choice-btn danger" href="signout.php">
            <span class="icon">&#128682;</span>
            Sign Out
            <span style="font-size:.82rem;font-weight:400;opacity:.85;">Log your departure</span>
        </a>
    </div>
</div>

<div class="card" style="text-align:center;padding:20px;">
    <p style="font-size:.88rem;color:#888;">
        &#9432;&nbsp; All information collected is for health &amp; safety compliance only.
        Data is handled in accordance with the Data Privacy Act of 2012.
    </p>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
