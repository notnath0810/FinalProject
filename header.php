<?php
session_start();
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Visitor Sign-Out';
$assetBase = './';

$error   = '';
$success = false;
$step    = 'lookup'; // 'lookup' | 'confirm' | 'pick' | 'done'

/* ── Step 2: Confirm sign-out ────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_signout'])) {
    $log_id     = (int)($_POST['log_id']     ?? 0);
    $visitor_id = (int)($_POST['visitor_id'] ?? 0);

    if ($log_id <= 0 || $visitor_id <= 0) {
        $error = 'Invalid session data. Please search again.';
        $step  = 'lookup';
    } else {
        // Ensure log still open (time_out IS NULL)
        $chk = $conn->prepare(
            'SELECT log_id FROM visit_logs
              WHERE log_id = ? AND visitor_id = ? AND time_out IS NULL'
        );
        $chk->bind_param('ii', $log_id, $visitor_id);
        $chk->execute();
        $chk->store_result();

        if ($chk->num_rows === 0) {
            $error = 'This visit entry is already signed out or does not exist.';
            $step  = 'lookup';
        } else {
            $chk->close();
            $upd = $conn->prepare(
                'UPDATE visit_logs SET time_out = NOW() WHERE log_id = ? AND visitor_id = ?'
            );
            $upd->bind_param('ii', $log_id, $visitor_id);
            if ($upd->execute()) {
                $success = true;
                $step    = 'done';
                // Retrieve visitor name for success message
                $vs = $conn->prepare(
                    'SELECT first_name, last_name FROM visitors WHERE visitor_id = ?'
                );
                $vs->bind_param('i', $visitor_id);
                $vs->execute();
                $vs->bind_result($fn, $ln);
                $vs->fetch();
                $vs->close();
                $visitorName = trim("$fn $ln");
            } else {
                $error = 'Could not record sign-out. Please try again.';
                $step  = 'lookup';
            }
            $upd->close();
        }
    }
}

/* ── Step 1: Lookup open visit logs ──────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lookup'])) {
    $id_number = trim($_POST['id_number'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');

    if ($id_number === '' && $last_name === '') {
        $error = 'Please enter your USC ID number or last name.';
    } else {
        if ($id_number !== '') {
            $stmt = $conn->prepare(
                'SELECT v.visitor_id, v.id_number, v.first_name, v.middle_name, v.last_name,
                        v.barangay, v.city, v.province, v.contact_number,
                        l.log_id, l.time_in
                   FROM visitors v
                   JOIN visit_logs l ON v.visitor_id = l.visitor_id
                  WHERE v.id_number = ? AND l.time_out IS NULL
                  ORDER BY l.time_in DESC
                  LIMIT 10'
            );
            $stmt->bind_param('s', $id_number);
        } else {
            $stmt = $conn->prepare(
                'SELECT v.visitor_id, v.id_number, v.first_name, v.middle_name, v.last_name,
                        v.barangay, v.city, v.province, v.contact_number,
                        l.log_id, l.time_in
                   FROM visitors v
                   JOIN visit_logs l ON v.visitor_id = l.visitor_id
                  WHERE v.last_name LIKE ? AND l.time_out IS NULL
                  ORDER BY l.time_in DESC
                  LIMIT 10'
            );
            $likeParam = '%' . $last_name . '%';
            $stmt->bind_param('s', $likeParam);
        }

        $stmt->execute();
        $res  = $stmt->get_result();
        $rows = [];
        while ($row = $res->fetch_assoc()) $rows[] = $row;
        $stmt->close();

        if (count($rows) === 0) {
            $error = 'No active (signed-in) visit found for that ID or name. You may already be signed out.';
        } elseif (count($rows) === 1) {
            $openLog = $rows[0];
            $step    = 'confirm';
        } else {
            $openLogs = $rows;
            $step     = 'pick';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="card">
    <div class="card-title">&#128682; Visitor Sign-Out</div>

    <?php if ($step === 'done'): ?>
        <div class="alert alert-success">
            &#10003; <strong><?= e($visitorName ?? 'Visitor') ?></strong>, you have been signed out successfully
            at <strong><?= formatPHTime(date('Y-m-d H:i:s')) ?></strong>.
            Thank you for visiting the Department of Computer Engineering!
        </div>
        <a href="index.php" class="btn btn-outline mt-16">&#8592; Back to Home</a>

    <?php elseif ($step === 'confirm' && !empty($openLog)): ?>
        <?php if ($error !== ''): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
        <div class="alert alert-info">
            Confirm that you are signing out this visitor:
        </div>
        <div class="info-preview">
            <h3>Visit Record</h3>
            <dl class="info-grid">
                <dt>Name:</dt>
                <dd><?= e(trim($openLog['first_name'] . ' ' . $openLog['middle_name'] . ' ' . $openLog['last_name'])) ?></dd>
                <dt>USC ID:</dt>
                <dd><?= $openLog['id_number'] ? e($openLog['id_number']) : '(Guest)' ?></dd>
                <dt>Barangay:</dt>
                <dd><?= e($openLog['barangay']) ?></dd>
                <dt>City:</dt>
                <dd><?= e($openLog['city']) ?></dd>
                <dt>Province:</dt>
                <dd><?= e($openLog['province']) ?></dd>
                <dt>Signed In At:</dt>
                <dd><?= formatPHTime($openLog['time_in']) ?></dd>
            </dl>
        </div>
        <form method="POST">
            <input type="hidden" name="log_id"     value="<?= (int)$openLog['log_id'] ?>">
            <input type="hidden" name="visitor_id" value="<?= (int)$openLog['visitor_id'] ?>">
            <div class="flex gap-8 flex-wrap">
                <button type="submit" name="confirm_signout" class="btn btn-danger">&#10003; Confirm Sign-Out</button>
                <a href="signout.php" class="btn btn-outline">&#8592; Search Again</a>
                <a href="index.php"   class="btn btn-outline">Cancel</a>
            </div>
        </form>

    <?php elseif ($step === 'pick' && !empty($openLogs)): ?>
        <div class="alert alert-info">Multiple open visits found. Select yours:</div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>USC ID</th>
                        <th>Time In</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($openLogs as $l): ?>
                    <tr>
                        <td><?= e($l['first_name'] . ' ' . $l['last_name']) ?></td>
                        <td><?= $l['id_number'] ? e($l['id_number']) : '(Guest)' ?></td>
                        <td><?= formatPHTime($l['time_in']) ?></td>
                        <td>
                            <form method="POST" style="display:inline">
                                <input type="hidden" name="log_id"     value="<?= (int)$l['log_id'] ?>">
                                <input type="hidden" name="visitor_id" value="<?= (int)$l['visitor_id'] ?>">
                                <button type="submit" name="confirm_signout" class="btn btn-danger btn-sm">Sign Out</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-16">
            <a href="signout.php" class="btn btn-outline btn-sm">&#8592; Search Again</a>
        </div>

    <?php else: ?>
        <!-- ── Lookup form ── -->
        <?php if ($error !== ''): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

        <p style="color:#666;margin-bottom:20px;font-size:.93rem;">
            Enter your <strong>USC ID number</strong> OR your <strong>last name</strong>
            to find your active visit and record your departure.
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
                <button type="submit" class="btn btn-danger">&#128682; Find My Visit &amp; Sign Out</button>
                <a href="index.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
