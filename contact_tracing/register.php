<?php
session_start();
require_once __DIR__ . '/includes/db.php';

$pageTitle  = 'First-Time Visitor Registration';
$assetBase  = './';
$error      = '';
$success    = false;
$newVisitorId = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ── Server-side validation ─────────────────────────────────
    $id_number      = trim($_POST['id_number']      ?? '');
    $first_name     = trim($_POST['first_name']     ?? '');
    $middle_name    = trim($_POST['middle_name']    ?? '');
    $last_name      = trim($_POST['last_name']      ?? '');
    $barangay       = trim($_POST['barangay']       ?? '');
    $city           = trim($_POST['city']           ?? '');
    $province       = trim($_POST['province']       ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $email          = trim($_POST['email']          ?? '');

    // Required fields (id_number is optional for guests)
    $requiredFields = [
        'First Name'     => $first_name,
        'Last Name'      => $last_name,
        'Barangay'       => $barangay,
        'City'           => $city,
        'Province'       => $province,
        'Contact Number' => $contact_number,
        'Email'          => $email,
    ];

    $missing = [];
    foreach ($requiredFields as $label => $val) {
        if ($val === '') $missing[] = $label;
    }

    if (!empty($missing)) {
        $error = 'Please fill in the following required fields: ' . implode(', ', $missing) . '.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (!preg_match('/^[0-9+\-\s()]{7,20}$/', $contact_number)) {
        $error = 'Please enter a valid contact number (7–20 digits).';
    } else {
        // ── Check for duplicate ID number ──────────────────────
        if ($id_number !== '') {
            $chk = $conn->prepare('SELECT visitor_id FROM visitors WHERE id_number = ?');
            $chk->bind_param('s', $id_number);
            $chk->execute();
            $chk->store_result();
            if ($chk->num_rows > 0) {
                $error = 'A visitor with that ID number is already registered. Use "Returning Visitor" to sign in.';
            }
            $chk->close();
        }

        if ($error === '') {
            // ── INSERT visitor ─────────────────────────────────
            $idParam = ($id_number !== '') ? $id_number : null;
            $stmt = $conn->prepare(
                'INSERT INTO visitors
                    (id_number, first_name, middle_name, last_name,
                     barangay, city, province, contact_number, email)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->bind_param(
                'sssssssss',
                $idParam, $first_name, $middle_name, $last_name,
                $barangay, $city, $province, $contact_number, $email
            );

            if ($stmt->execute()) {
                $newVisitorId = $stmt->insert_id;
                $stmt->close();

                // ── INSERT visit_log ───────────────────────────
                $log = $conn->prepare(
                    'INSERT INTO visit_logs (visitor_id, time_in) VALUES (?, NOW())'
                );
                $log->bind_param('i', $newVisitorId);
                if ($log->execute()) {
                    $success = true;
                } else {
                    $error = 'Registration succeeded but could not log your visit. Please notify the front desk.';
                }
                $log->close();
            } else {
                $error = 'Registration failed. Please try again or contact the administrator.';
                $stmt->close();
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="card">
    <div class="card-title">&#128221; First-Time Visitor Registration</div>

    <?php if ($success): ?>
        <div class="alert alert-success">
            &#10003; You have been successfully registered and signed in!
            Your visit has been recorded at <strong><?= formatPHTime(date('Y-m-d H:i:s')) ?></strong>.
        </div>
        <p style="margin-bottom:16px;">
            <strong>Welcome, <?= e($first_name) ?> <?= e($last_name) ?>!</strong><br>
            <?php if ($newVisitorId): ?>
                Your visitor number is <strong>#<?= (int)$newVisitorId ?></strong>.
                <?php if ($id_number !== ''): ?>
                    Please remember your USC ID number (<strong><?= e($id_number) ?></strong>)
                    for future sign-ins.
                <?php endif; ?>
            <?php endif; ?>
        </p>
        <div class="flex gap-8 flex-wrap">
            <a href="index.php" class="btn btn-outline">&#8592; Back to Home</a>
            <a href="signout.php" class="btn btn-primary">Sign Out Later</a>
        </div>

    <?php else: ?>

        <?php if ($error !== ''): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" id="registerForm" novalidate>

            <p style="font-size:.88rem;color:#666;margin-bottom:20px;">
                Fields marked <span style="color:#cc0000">*</span> are required.
                <strong>USC students/staff:</strong> enter your ID number for faster future sign-ins.
                Guests may leave it blank.
            </p>

            <div class="form-grid">
                <!-- ID Number (optional) -->
                <div class="form-group span-2">
                    <label for="id_number">USC ID Number</label>
                    <input type="text" id="id_number" name="id_number"
                           placeholder="e.g. 2024-00001 (leave blank if guest)"
                           value="<?= e($_POST['id_number'] ?? '') ?>">
                    <span class="field-note">Optional — for USC students, faculty, and staff only.</span>
                </div>

                <!-- Name -->
                <div class="form-group">
                    <label for="first_name">First Name <span class="req">*</span></label>
                    <input type="text" id="first_name" name="first_name" required
                           placeholder="e.g. Juan"
                           value="<?= e($_POST['first_name'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="middle_name">Middle Name</label>
                    <input type="text" id="middle_name" name="middle_name"
                           placeholder="e.g. Santos (optional)"
                           value="<?= e($_POST['middle_name'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name <span class="req">*</span></label>
                    <input type="text" id="last_name" name="last_name" required
                           placeholder="e.g. dela Cruz"
                           value="<?= e($_POST['last_name'] ?? '') ?>">
                </div>

                <!-- Contact -->
                <div class="form-group">
                    <label for="contact_number">Contact Number <span class="req">*</span></label>
                    <input type="tel" id="contact_number" name="contact_number" required
                           placeholder="e.g. 09171234567"
                           value="<?= e($_POST['contact_number'] ?? '') ?>">
                </div>

                <div class="form-group span-2">
                    <label for="email">Email Address <span class="req">*</span></label>
                    <input type="email" id="email" name="email" required
                           placeholder="e.g. juan@email.com"
                           value="<?= e($_POST['email'] ?? '') ?>">
                </div>

                <!-- Address -->
                <div class="form-group" style="grid-column:span 2;">
                    <p style="font-weight:700;color:var(--blue-dark);margin-bottom:4px;">Home Address</p>
                </div>

                <div class="form-group">
                    <label for="barangay">Barangay <span class="req">*</span></label>
                    <input type="text" id="barangay" name="barangay" required
                           placeholder="e.g. Lahug"
                           value="<?= e($_POST['barangay'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="city">City / Municipality <span class="req">*</span></label>
                    <input type="text" id="city" name="city" required
                           placeholder="e.g. Cebu City"
                           value="<?= e($_POST['city'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="province">Province <span class="req">*</span></label>
                    <input type="text" id="province" name="province" required
                           placeholder="e.g. Cebu"
                           value="<?= e($_POST['province'] ?? '') ?>">
                </div>
            </div><!-- /form-grid -->

            <hr class="divider">
            <div class="flex gap-8 flex-wrap align-center">
                <button type="submit" class="btn btn-primary">&#10003; Register &amp; Sign In</button>
                <a href="index.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
