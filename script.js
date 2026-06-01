<?php
session_start();
require_once __DIR__ . '/includes/db.php';
$pageTitle = 'Welcome';
$assetBase = './';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Banner -->
<div class="hero-banner" style="margin:-32px -16px 0;max-width:none;">
    <h2>&#128203; Visitor Sign-In Portal</h2>
    <p>Department of Computer Engineering &mdash; University of San Carlos</p>
    <div class="hero-gold-bar"></div>
</div>

<div style="padding-top:0;">
    <div class="choice-grid">
        <a class="choice-btn primary-action" href="register.php">
            <span class="icon">&#128221;</span>
            <span class="btn-label">First-Time Visitor</span>
            <span class="btn-sub">Register and sign in for the first time</span>
        </a>

        <a class="choice-btn" href="returning.php">
            <span class="icon">&#128100;</span>
            <span class="btn-label">Returning Visitor</span>
            <span class="btn-sub">Already registered? Sign in here</span>
        </a>

        <a class="choice-btn danger-action" href="signout.php">
            <span class="icon">&#128682;</span>
            <span class="btn-label">Sign Out</span>
            <span class="btn-sub">Log your time of departure</span>
        </a>
    </div>
</div>

<div class="card" style="text-align:center;padding:18px 24px;margin-top:8px;">
    <p style="font-size:.85rem;color:var(--gray-muted);">
        &#9432;&nbsp; All information collected is strictly for <strong>health &amp; safety compliance</strong> purposes only.
        Handled in accordance with the <strong>Data Privacy Act of 2012</strong> (Republic Act No. 10173).
    </p>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
