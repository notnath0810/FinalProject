<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// Already logged in → go to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$pageTitle = 'Admin Login';
$assetBase = '../';
$error     = '';

/*
 * AUTHENTICATION METHOD: Plain-text comparison against hardcoded credentials.
 * Username : admin
 * Password : coedept2024
 *
 * For production, replace the plain-text comparison below with:
 *   password_verify($_POST['password'], $stored_hash)
 * where $stored_hash = password_hash('coedept2024', PASSWORD_BCRYPT)
 */
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'coedept2024');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } elseif ($username !== ADMIN_USERNAME || $password !== ADMIN_PASSWORD) {
        // Generic message — do not reveal which field is wrong
        $error = 'Invalid username or password.';
        // Delay to slow brute-force attempts
        sleep(1);
    } else {
        // Regenerate session ID on privilege escalation
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user']      = $username;
        header('Location: dashboard.php');
        exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div style="max-width:420px;margin:0 auto;">
    <div class="card">
        <div class="card-title" style="text-align:center;">
            &#128274; Admin Login
        </div>
        <p style="text-align:center;color:#666;margin-bottom:24px;font-size:.9rem;">
            Department of Computer Engineering &mdash; USC<br>
            Contact Tracing Administration Panel
        </p>

        <?php if ($error !== ''): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="form-group" style="margin-bottom:16px;">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                       placeholder="Enter admin username" required
                       autocomplete="username"
                       value="<?= e($_POST['username'] ?? '') ?>">
            </div>
            <div class="form-group" style="margin-bottom:24px;">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="Enter admin password" required
                       autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login &rarr;</button>
        </form>

        <hr class="divider">
        <p style="text-align:center;font-size:.83rem;">
            <a href="../index.php">&larr; Back to visitor portal</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
