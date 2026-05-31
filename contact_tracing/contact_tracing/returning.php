<?php
session_start();
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Returning Visitor Sign-In';
$assetBase = './';

$error     = '';
$success   = false;
$visitor   = null;  // array of visitor data shown for confirmation
$step      = 'lookup'; // 'lookup' | 'confirm'

/* ── Step 2: Confirm sign-in ─────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_signin'])) {
    $visitor_id = (int)($_POST['visitor_id'] ?? 0);

    if ($visitor_id <= 0) {
        $error = 'Invalid visitor record. Please search again.';
        $step  = 'lookup';
    } else {
        // Verify visitor still exists
        $chk = $conn->prepare('SELECT visitor_id FROM visitors WHERE visitor_id = ?');
        $chk->bind_param('i', $visitor_id);
        $chk->execute();
        $chk->store_result();

        if ($chk->num_rows === 0) {
            $error = 'Visitor record not found. Please contact the front desk.';
            $step  = 'lookup';
        } else {
            $chk->close();
            // Insert new visit log
            $log = $conn->prepare('INSERT INTO visit_logs (visitor_id, time_in) VALUES (?, NOW())');
            $log->bind_param('i', $visitor_id);
            if ($log->execute()) {
                $success = true;
                $step    = 'done';
                // Reload visitor info for success message
                $vs = $conn->prepare(
                    'SELECT first_name, last_name, id_number FROM visitors WHERE visitor_id = ?'
                );
                $vs->bind_param('i', $visitor_id);
                $vs->execute();
                $vs->bind_result($fn, $ln, $idn);
                $vs->fetch();
                $vs->close();
                $visitorName  = trim("$fn $ln");
                $visitorIdNum = $idn;
            } else {
                $error = 'Could not record sign-in. Please try again.';
                $step  = 'lookup';
            }
            $log->close();
        }
    }
}

/* ── Step 1: Lookup ──────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lookup'])) {
    $id_number = trim($_POST['id_number'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');

    if ($id_number === '' && $last_name === '') {
        $error = 'Please enter your USC ID number or last name to look up your record.';
    } else {
        if ($id_number !== '') {
            $stmt = $conn->prepare(
                'SELECT visitor_id, id_number, first_name, middle_name, last_name,
                        barangay, city, province, contact_number, email, created_at
                 FROM visitors WHERE id_number = ?'
            );
            $stmt->bind_param('s', $id_number);
        } else {
            $stmt = $conn->prepare(
                'SELECT visitor_id, id_number, first_name, middle_name, last_name,
                        barangay, city, province, contact_number, email, created_at
                 FROM visitors WHERE last_name LIKE ? LIMIT 10'
            );
            $likeParam = '%' . $last_name . '%';
            $stmt->bind_param('s', $likeParam);
        }

        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        while ($row = $res->fetch_assoc()) $rows[] = $row;
        $stmt->close();

        if (count($rows) === 0) {
            $error = 'No visitor record found. If this is your first visit, please register.';
        } elseif (count($rows) === 1) {
            $visitor = $rows[0];
            $step    = 'confirm';
        } else {
            // Multiple results — let user pick
            $visitors = $rows;
            $step     = 'pick';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="card">
    <div class="card-title">&#128100; Returning Visitor Sign-In</div>

    <?php if ($step === 'done'): ?>
        <!-- ── Success ── -->
        <div class="alert alert-success">
            &#10003; Welcome back, <strong><?= e($visitorName ?? '') ?></strong>!
            You have been signed in at <strong><?= formatPHTime(date('Y-m-d H:i:s')) ?></strong>.
        </div>
        <div class="flex gap-8 flex-wrap mt-16">
            <a href="index.php" class="btn btn-outline">&#8592; Back to Home</a>
            <a href="signout.php" class="btn btn-primary">Sign Out</a>
        </div>

    <?php elseif ($step === 'confirm' && $visitor): ?>
        <!-- ── Confirm visitor info ── -->
        <?php if ($error !== ''): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>
        <div class="alert alert-info">
            Is this you? Review your information below and click <strong>Confirm Sign-In</strong>.
        </div>
        <div class="info-preview">
            <h3>Visitor Information</h3>
            <dl class="info-grid">
                <dt>Name:</dt>
                <dd><?= e(trim($visitor['first_name'] . ' ' . $visitor['middle_name'] . ' ' . $visitor['last_name'])) ?></dd>
                <dt>USC ID:</dt>
                <dd><?= $visitor['id_number'] ? e($visitor['id_number']) : '(Guest)' ?></dd>
                <dt>Barangay:</dt>
                <dd><?= e($visitor['barangay']) ?></dd>
                <dt>City:</dt>
                <dd><?= e($visitor['city']) ?></dd>
                <dt>Province:</dt>
                <dd><?= e($visitor['province']) ?></dd>
                <dt>Contact:</dt>
                <dd><?= e($visitor['contact_number']) ?></dd>
                <dt>Email:</dt>
                <dd><?= e($visitor['email']) ?></dd>
                <dt>First registered:</dt>
                <dd><?= formatPHTime($visitor['created_at']) ?></dd>
            </dl>
        </div>
        <form method="POST">
            <input type="hidden" name="visitor_id" value="<?= (int)$visitor['visitor_id'] ?>">
            <div class="flex gap-8 flex-wrap">
                <button type="submit" name="confirm_signin" class="btn btn-primary">&#10003; Confirm Sign-In</button>
                <a href="returning.php" class="btn btn-outline">&#8592; Search Again</a>
                <a href="index.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>

    <?php elseif ($step === 'pick' && !empty($visitors)): ?>
        <!-- ── Multiple results — pick one ── -->
        <div class="alert alert-info">
            Multiple records found. Select the correct one:
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>USC ID</th>
                        <th>City</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($visitors as $v): ?>
                    <tr>
                        <td><?= e($v['first_name'] . ' ' . $v['last_name']) ?></td>
                        <td><?= $v['id_number'] ? e($v['id_number']) : '(Guest)' ?></td>
                        <td><?= e($v['city']) ?></td>
                        <td>
                            <form method="POST" style="display:inline">
                                <input type="hidden" name="visitor_id" value="<?= (int)$v['visitor_id'] ?>">
                                <button type="submit" name="confirm_signin" class="btn btn-primary btn-sm">Select &amp; Sign In</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-16">
            <a href="returning.php" class="btn btn-outline btn-sm">&#8592; Search Again</a>
        </div>

    <?php else: ?>
        <!-- ── Lookup form ── -->
        <?php if ($error !== ''): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <p style="color:#666;margin-bottom:20px;font-size:.93rem;">
            Enter your <strong>USC ID number</strong> OR your <strong>last name</strong> to find your record.
        </p>

        <form method="POST" id="lookupForm" novalidate>
            <input type="hidden" name="lookup" value="1">
            <div class="form-grid">
                <div class="form-group">
                    <label for="id_number">USC ID Number</label>
                    <input type="text" id="id_number" name="id_number"
                           placeholder="e.g. 2024-00001"
                           value="<?= e($_POST['id_number'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="last_name">OR Last Name</label>
                    <input type="text" id="last_name" name="last_name"
                           placeholder="e.g. dela Cruz"
                           value="<?= e($_POST['last_name'] ?? '') ?>">
                </div>
            </div>
            <hr class="divider">
            <div class="flex gap-8 flex-wrap align-center">
                <button type="submit" class="btn btn-primary">&#128269; Look Up My Record</button>
                <a href="index.php" class="btn btn-outline">Cancel</a>
                <span style="font-size:.85rem;color:#888;">
                    First time here? <a href="register.php">Register instead</a>
                </span>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
