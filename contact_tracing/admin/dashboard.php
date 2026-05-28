<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// ── Auth guard ────────────────────────────────────────────
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// ── AJAX: Visit history for a single visitor ──────────────
if (isset($_GET['ajax_history']) && isset($_GET['visitor_id'])) {
    $vid = (int)$_GET['visitor_id'];
    $stmt = $conn->prepare(
        'SELECT l.log_id, l.time_in, l.time_out
           FROM visit_logs l
          WHERE l.visitor_id = ?
          ORDER BY l.time_in DESC'
    );
    $stmt->bind_param('i', $vid);
    $stmt->execute();
    $res  = $stmt->get_result();
    $logs = [];
    while ($r = $res->fetch_assoc()) $logs[] = $r;
    $stmt->close();

    if (empty($logs)) {
        echo '<p style="padding:16px;color:#888">No visit records found.</p>';
    } else {
        echo '<table style="width:100%;border-collapse:collapse;font-size:.87rem">';
        echo '<thead style="background:#003366;color:#fff"><tr>
                <th style="padding:10px 12px;text-align:left">#</th>
                <th style="padding:10px 12px;text-align:left">Time In</th>
                <th style="padding:10px 12px;text-align:left">Time Out</th>
                <th style="padding:10px 12px;text-align:left">Duration</th>
              </tr></thead><tbody>';
        $i = 1;
        foreach ($logs as $l) {
            $dur = '—';
            if (!empty($l['time_out'])) {
                $secs = strtotime($l['time_out']) - strtotime($l['time_in']);
                $h    = floor($secs / 3600);
                $m    = floor(($secs % 3600) / 60);
                $dur  = ($h > 0 ? "{$h}h " : '') . "{$m}m";
            }
            $bg = ($i % 2 === 0) ? 'background:#f4f7fb' : '';
            echo "<tr style=\"border-bottom:1px solid #d0daea;{$bg}\">
                    <td style=\"padding:9px 12px\">{$i}</td>
                    <td style=\"padding:9px 12px\">" . formatPHTime($l['time_in']) . "</td>
                    <td style=\"padding:9px 12px\">" . formatPHTime($l['time_out']) . "</td>
                    <td style=\"padding:9px 12px\">{$dur}</td>
                  </tr>";
            $i++;
        }
        echo '</tbody></table>';
    }
    exit; // AJAX response complete — do not render page HTML
}

// ── DELETE visitor (cascade deletes their logs) ───────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_visitor'])) {
    $del_id = (int)($_POST['visitor_id'] ?? 0);
    if ($del_id > 0) {
        $stmt = $conn->prepare('DELETE FROM visitors WHERE visitor_id = ?');
        $stmt->bind_param('i', $del_id);
        $stmt->execute();
        $stmt->close();
        $deleteMsg = 'Visitor record deleted successfully.';
    }
    // After delete, fall through to re-render the page
}

// ── Search / filter logic ─────────────────────────────────
$city      = trim($_GET['city']      ?? '');
$barangay  = trim($_GET['barangay']  ?? '');
$province  = trim($_GET['province']  ?? '');
$id_number = trim($_GET['id_number'] ?? '');
$name      = trim($_GET['name']      ?? '');
$date      = trim($_GET['date']      ?? '');

// Build WHERE conditions using prepared statement
$conditions  = [];
$bindTypes   = '';
$bindValues  = [];

if ($city !== '') {
    $conditions[] = 'v.city LIKE ?';
    $bindTypes   .= 's';
    $bindValues[] = '%' . $city . '%';
}
if ($barangay !== '') {
    $conditions[] = 'v.barangay LIKE ?';
    $bindTypes   .= 's';
    $bindValues[] = '%' . $barangay . '%';
}
if ($province !== '') {
    $conditions[] = 'v.province LIKE ?';
    $bindTypes   .= 's';
    $bindValues[] = '%' . $province . '%';
}
if ($id_number !== '') {
    $conditions[] = 'v.id_number = ?';
    $bindTypes   .= 's';
    $bindValues[] = $id_number;
}
if ($name !== '') {
    $conditions[] = '(v.last_name LIKE ? OR v.first_name LIKE ?)';
    $bindTypes   .= 'ss';
    $bindValues[] = '%' . $name . '%';
    $bindValues[] = '%' . $name . '%';
}
if ($date !== '') {
    $conditions[] = 'DATE(l.time_in) = ?';
    $bindTypes   .= 's';
    $bindValues[] = $date;
}

$whereClause = !empty($conditions)
    ? 'WHERE ' . implode(' AND ', $conditions)
    : '';

$sql = "SELECT v.visitor_id, v.id_number, v.first_name, v.middle_name, v.last_name,
               v.barangay, v.city, v.province, v.contact_number, v.email,
               l.log_id, l.time_in, l.time_out
          FROM visitors v
          LEFT JOIN visit_logs l ON v.visitor_id = l.visitor_id
        {$whereClause}
         ORDER BY l.time_in DESC, v.visitor_id DESC
         LIMIT 500";

$stmt = $conn->prepare($sql);

if (!empty($bindValues)) {
    // bind_param requires variables by reference — use splat with call_user_func_array
    $refs   = [];
    $refs[] = &$bindTypes;
    foreach ($bindValues as $k => $v) {
        $bindValues[$k] = $v; // keep value
        $refs[]         = &$bindValues[$k];
    }
    call_user_func_array([$stmt, 'bind_param'], $refs);
}

$stmt->execute();
$res  = $stmt->get_result();
$logs = [];
while ($r = $res->fetch_assoc()) $logs[] = $r;
$stmt->close();

// Count totals for the stats bar
$totalVisitors = 0;
$chkV = $conn->query('SELECT COUNT(*) AS c FROM visitors');
if ($chkV) { $row = $chkV->fetch_assoc(); $totalVisitors = (int)$row['c']; }

$todayVisits = 0;
$chkT = $conn->query("SELECT COUNT(*) AS c FROM visit_logs WHERE DATE(time_in) = CURDATE()");
if ($chkT) { $row = $chkT->fetch_assoc(); $todayVisits = (int)$row['c']; }

$openVisits = 0;
$chkO = $conn->query('SELECT COUNT(*) AS c FROM visit_logs WHERE time_out IS NULL');
if ($chkO) { $row = $chkO->fetch_assoc(); $openVisits = (int)$row['c']; }

$pageTitle = 'Admin Dashboard';
$assetBase = '../';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- ── Stats bar ────────────────────────────────────────── -->
<div style="display:flex;gap:16px;flex-wrap:wrap;margin-bottom:24px;">
    <?php
    $stats = [
        ['Total Registered Visitors', $totalVisitors, '#003366'],
        ["Today's Visits",            $todayVisits,   '#004a99'],
        ['Currently On-Premises',     $openVisits,    '#007a33'],
    ];
    foreach ($stats as [$label, $val, $color]): ?>
    <div style="flex:1 1 160px;background:<?= $color ?>;color:#fff;border-radius:8px;
                padding:20px 24px;box-shadow:0 2px 8px rgba(0,0,0,.15);">
        <div style="font-size:2rem;font-weight:700"><?= (int)$val ?></div>
        <div style="font-size:.85rem;opacity:.85"><?= e($label) ?></div>
    </div>
    <?php endforeach; ?>
    <div style="flex:1 1 160px;background:#fff;border:2px solid #003366;border-radius:8px;
                padding:20px 24px;display:flex;align-items:center;justify-content:center;">
        <a href="logout.php" class="btn btn-outline" style="font-size:.88rem;">&#128274; Logout</a>
    </div>
</div>

<!-- ── Delete success message ───────────────────────────── -->
<?php if (!empty($deleteMsg)): ?>
    <div class="alert alert-success"><?= e($deleteMsg) ?></div>
<?php endif; ?>

<!-- ── Search / filter card ─────────────────────────────── -->
<div class="card">
    <div class="card-title">&#128269; Search &amp; Filter Visitor Logs</div>
    <form method="GET" id="searchForm" novalidate>
        <div class="filters-grid">
            <div class="filter-item">
                <label for="f_city">City</label>
                <input type="text" id="f_city" name="city"
                       placeholder="e.g. Cebu City"
                       value="<?= e($city) ?>">
            </div>
            <div class="filter-item">
                <label for="f_barangay">Barangay</label>
                <input type="text" id="f_barangay" name="barangay"
                       placeholder="e.g. Lahug"
                       value="<?= e($barangay) ?>">
            </div>
            <div class="filter-item">
                <label for="f_province">Province</label>
                <input type="text" id="f_province" name="province"
                       placeholder="e.g. Cebu"
                       value="<?= e($province) ?>">
            </div>
            <div class="filter-item">
                <label for="f_id">ID Number (exact)</label>
                <input type="text" id="f_id" name="id_number"
                       placeholder="e.g. 2024-00001"
                       value="<?= e($id_number) ?>">
            </div>
            <div class="filter-item">
                <label for="f_name">First / Last Name</label>
                <input type="text" id="f_name" name="name"
                       placeholder="e.g. dela Cruz"
                       value="<?= e($name) ?>">
            </div>
            <div class="filter-item">
                <label for="f_date">Visit Date</label>
                <input type="date" id="f_date" name="date"
                       value="<?= e($date) ?>">
            </div>
        </div>
        <div class="flex gap-8 align-center flex-wrap">
            <button type="submit" class="btn btn-primary">&#128269; Search</button>
            <a href="dashboard.php" class="btn btn-outline">&#8635; Clear Filters</a>
            <span style="font-size:.85rem;color:#888;">
                Showing <strong><?= count($logs) ?></strong> record(s)
            </span>
        </div>
    </form>
</div>

<!-- ── Logs table ────────────────────────────────────────── -->
<div class="card" style="padding:0;overflow:hidden;">
    <div style="padding:20px 24px 0;">
        <div class="card-title">&#128203; Visit Log</div>
    </div>

    <?php if (empty($logs)): ?>
        <div style="padding:24px;">
            <div class="alert alert-info">No records match the selected filters.</div>
        </div>
    <?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Visitor Name</th>
                    <th>USC ID</th>
                    <th>Barangay</th>
                    <th>City</th>
                    <th>Province</th>
                    <th>Contact</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log):
                    $fullName  = trim($log['first_name'] . ' ' . $log['middle_name'] . ' ' . $log['last_name']);
                    $shortName = trim($log['first_name'] . ' ' . $log['last_name']);
                    $isOpen    = empty($log['time_out']);
                ?>
                <tr>
                    <td>
                        <a href="#" title="View full visit history"
                           onclick="openHistoryModal(<?= (int)$log['visitor_id'] ?>, '<?= addslashes(e($shortName)) ?>');return false;"
                           style="color:var(--blue-dark);font-weight:600;text-decoration:none;">
                            <?= e($fullName) ?>
                        </a>
                    </td>
                    <td><?= $log['id_number'] ? e($log['id_number']) : '<span style="color:#aaa">(Guest)</span>' ?></td>
                    <td><?= e($log['barangay']) ?></td>
                    <td><?= e($log['city']) ?></td>
                    <td><?= e($log['province']) ?></td>
                    <td><?= e($log['contact_number']) ?></td>
                    <td style="white-space:nowrap"><?= formatPHTime($log['time_in']) ?></td>
                    <td style="white-space:nowrap"><?= formatPHTime($log['time_out']) ?></td>
                    <td>
                        <?php if ($isOpen): ?>
                            <span class="badge badge-in">Signed In</span>
                        <?php else: ?>
                            <span class="badge badge-out">Signed Out</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="POST"
                              onsubmit="return confirmDelete('<?= addslashes(e($shortName)) ?>')"
                              style="display:inline">
                            <input type="hidden" name="visitor_id" value="<?= (int)$log['visitor_id'] ?>">
                            <button type="submit" name="delete_visitor"
                                    class="btn btn-danger btn-sm"
                                    title="Delete visitor and all logs">
                                &#128465; Delete
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<!-- ── Visit History Modal ───────────────────────────────── -->
<div id="historyModal"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);
            z-index:1000;align-items:center;justify-content:center;padding:16px;">
    <div style="background:#fff;border-radius:8px;max-width:680px;width:100%;
                max-height:80vh;display:flex;flex-direction:column;
                box-shadow:0 8px 40px rgba(0,0,0,.3);">
        <!-- Modal header -->
        <div style="background:var(--blue-dark);color:#fff;padding:16px 20px;
                    border-radius:8px 8px 0 0;display:flex;align-items:center;gap:12px;">
            <span style="font-weight:700;font-size:1rem;" id="modalVisitorName">Visit History</span>
            <button onclick="closeHistoryModal()"
                    style="margin-left:auto;background:none;border:none;color:#fff;
                           font-size:1.4rem;cursor:pointer;line-height:1;">&times;</button>
        </div>
        <!-- Modal body (filled by JS fetch) -->
        <div id="modalBody" style="overflow-y:auto;flex:1;"></div>
        <div style="padding:14px 20px;text-align:right;border-top:1px solid #d0daea;">
            <button onclick="closeHistoryModal()" class="btn btn-outline btn-sm">Close</button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
