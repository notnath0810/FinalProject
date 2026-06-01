<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php'); exit;
}

// AJAX: visit history
if (isset($_GET['ajax_history'], $_GET['visitor_id'])) {
    $vid = (int)$_GET['visitor_id'];
    $stmt = $conn->prepare('SELECT log_id,time_in,time_out FROM visit_logs WHERE visitor_id=? ORDER BY time_in DESC');
    $stmt->bind_param('i', $vid); $stmt->execute();
    $res = $stmt->get_result(); $logs = []; while ($r = $res->fetch_assoc()) $logs[] = $r; $stmt->close();
    if (empty($logs)) {
        echo '<p style="padding:20px;color:#6b7280;text-align:center;">No visit records found.</p>';
    } else {
        echo '<table style="width:100%;border-collapse:collapse;font-size:.87rem">';
        echo '<thead style="background:linear-gradient(135deg,#001f5a,#003087);color:#fff">
                <tr>
                  <th style="padding:11px 14px;text-align:left;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px">#</th>
                  <th style="padding:11px 14px;text-align:left;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px">Time In</th>
                  <th style="padding:11px 14px;text-align:left;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px">Time Out</th>
                  <th style="padding:11px 14px;text-align:left;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px">Duration</th>
                </tr>
              </thead><tbody>';
        $i = 1;
        foreach ($logs as $l) {
            $dur = '—';
            if (!empty($l['time_out'])) {
                $s = strtotime($l['time_out']) - strtotime($l['time_in']);
                $dur = (floor($s/3600) > 0 ? floor($s/3600).'h ' : '') . floor(($s%3600)/60).'m';
            }
            $bg = ($i % 2 === 0) ? 'background:#f5f6fa' : 'background:#fff';
            echo "<tr style=\"border-bottom:1px solid #dde1ec;{$bg}\">
                    <td style=\"padding:10px 14px;font-weight:700;color:#003087\">{$i}</td>
                    <td style=\"padding:10px 14px\">" . formatPHTime($l['time_in']) . "</td>
                    <td style=\"padding:10px 14px\">" . formatPHTime($l['time_out']) . "</td>
                    <td style=\"padding:10px 14px;font-weight:600;color:#a07830\">{$dur}</td>
                  </tr>";
            $i++;
        }
        echo '</tbody></table>';
    }
    exit;
}

// DELETE visitor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_visitor'])) {
    $del_id = (int)($_POST['visitor_id'] ?? 0);
    if ($del_id > 0) {
        $stmt = $conn->prepare('DELETE FROM visitors WHERE visitor_id=?');
        $stmt->bind_param('i', $del_id); $stmt->execute(); $stmt->close();
        $deleteMsg = 'Visitor record deleted successfully.';
    }
}

// Search filters
$city      = trim($_GET['city']      ?? '');
$barangay  = trim($_GET['barangay']  ?? '');
$province  = trim($_GET['province']  ?? '');
$id_number = trim($_GET['id_number'] ?? '');
$name      = trim($_GET['name']      ?? '');
$date      = trim($_GET['date']      ?? '');

$conditions = []; $bindTypes = ''; $bindValues = [];
if ($city)      { $conditions[] = 'v.city LIKE ?';     $bindTypes .= 's'; $bindValues[] = '%'.$city.'%'; }
if ($barangay)  { $conditions[] = 'v.barangay LIKE ?'; $bindTypes .= 's'; $bindValues[] = '%'.$barangay.'%'; }
if ($province)  { $conditions[] = 'v.province LIKE ?'; $bindTypes .= 's'; $bindValues[] = '%'.$province.'%'; }
if ($id_number) { $conditions[] = 'v.id_number = ?';   $bindTypes .= 's'; $bindValues[] = $id_number; }
if ($name)      { $conditions[] = '(v.last_name LIKE ? OR v.first_name LIKE ?)'; $bindTypes .= 'ss'; $bindValues[] = '%'.$name.'%'; $bindValues[] = '%'.$name.'%'; }
if ($date)      { $conditions[] = 'DATE(l.time_in) = ?'; $bindTypes .= 's'; $bindValues[] = $date; }
$where = !empty($conditions) ? 'WHERE '.implode(' AND ', $conditions) : '';

$sql = "SELECT v.visitor_id,v.id_number,v.first_name,v.middle_name,v.last_name,
               v.barangay,v.city,v.province,v.contact_number,v.email,
               l.log_id,l.time_in,l.time_out
          FROM visitors v
          LEFT JOIN visit_logs l ON v.visitor_id=l.visitor_id
        {$where}
         ORDER BY l.time_in DESC, v.visitor_id DESC
         LIMIT 500";

$stmt = $conn->prepare($sql);
if (!empty($bindValues)) {
    $refs = [&$bindTypes];
    foreach ($bindValues as $k => $v) { $bindValues[$k] = $v; $refs[] = &$bindValues[$k]; }
    call_user_func_array([$stmt, 'bind_param'], $refs);
}
$stmt->execute(); $res = $stmt->get_result(); $logs = []; while ($r = $res->fetch_assoc()) $logs[] = $r; $stmt->close();

$totalVisitors = 0; $todayVisits = 0; $openVisits = 0;
$r = $conn->query('SELECT COUNT(*) AS c FROM visitors');              if ($r) $totalVisitors = (int)$r->fetch_assoc()['c'];
$r = $conn->query("SELECT COUNT(*) AS c FROM visit_logs WHERE DATE(time_in)=CURDATE()"); if ($r) $todayVisits = (int)$r->fetch_assoc()['c'];
$r = $conn->query('SELECT COUNT(*) AS c FROM visit_logs WHERE time_out IS NULL');         if ($r) $openVisits  = (int)$r->fetch_assoc()['c'];

$pageTitle = 'Admin Dashboard';
$assetBase = '../';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Stats Bar -->
<div class="stats-bar">
    <div class="stat-card">
        <div class="stat-value"><?= (int)$totalVisitors ?></div>
        <div class="stat-label">&#128101; Registered Visitors</div>
    </div>
    <div class="stat-card gold-accent">
        <div class="stat-value" style="color:var(--usc-gold-dark)"><?= (int)$todayVisits ?></div>
        <div class="stat-label">&#128197; Today's Visits</div>
    </div>
    <div class="stat-card green-accent">
        <div class="stat-value" style="color:var(--green)"><?= (int)$openVisits ?></div>
        <div class="stat-label">&#128994; Currently On-Premises</div>
    </div>
    <div class="stat-card" style="border-top-color:var(--red);display:flex;align-items:center;justify-content:center;">
        <a href="logout.php" class="btn btn-outline btn-sm">&#128274; Logout</a>
    </div>
</div>

<?php if (!empty($deleteMsg)): ?>
    <div class="alert alert-success">&#10003; <?= e($deleteMsg) ?></div>
<?php endif; ?>

<!-- Search Filters -->
<div class="card">
    <div class="card-title">&#128269; Search &amp; Filter Visitor Logs</div>
    <form method="GET" id="searchForm" novalidate>
        <div class="filters-grid">
            <div class="filter-item">
                <label for="f_city">City / Municipality</label>
                <input type="text" id="f_city" name="city" placeholder="e.g. Cebu City" value="<?= e($city) ?>">
            </div>
            <div class="filter-item">
                <label for="f_brgy">Barangay</label>
                <input type="text" id="f_brgy" name="barangay" placeholder="e.g. Lahug" value="<?= e($barangay) ?>">
            </div>
            <div class="filter-item">
                <label for="f_prov">Province</label>
                <input type="text" id="f_prov" name="province" placeholder="e.g. Cebu" value="<?= e($province) ?>">
            </div>
            <div class="filter-item">
                <label for="f_id">ID Number (exact)</label>
                <input type="text" id="f_id" name="id_number" placeholder="e.g. 2024-00001" value="<?= e($id_number) ?>">
            </div>
            <div class="filter-item">
                <label for="f_name">First or Last Name</label>
                <input type="text" id="f_name" name="name" placeholder="e.g. dela Cruz" value="<?= e($name) ?>">
            </div>
            <div class="filter-item">
                <label for="f_date">Visit Date</label>
                <input type="date" id="f_date" name="date" value="<?= e($date) ?>">
            </div>
        </div>
        <div class="flex gap-8 align-center flex-wrap">
            <button type="submit" class="btn btn-primary">&#128269; Search</button>
            <a href="dashboard.php" class="btn btn-outline">&#8635; Clear Filters</a>
            <span style="font-size:.85rem;color:var(--gray-muted);margin-left:4px;">
                Showing <strong style="color:var(--usc-blue)"><?= count($logs) ?></strong> record(s)
            </span>
        </div>
    </form>
</div>

<!-- Visit Log Table -->
<div class="card" style="padding:0;overflow:hidden;">
    <div style="padding:22px 28px 0;">
        <div class="card-title">&#128203; Visit Log</div>
    </div>

    <?php if (empty($logs)): ?>
        <div style="padding:28px;">
            <div class="alert alert-info">&#9432; No records match the selected filters. Clear filters to view all records.</div>
        </div>
    <?php else: ?>
    <div class="table-wrapper" style="border-radius:0;box-shadow:none;border:none;border-top:1px solid var(--gray-border);">
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
                    $fullName  = trim($log['first_name'].' '.($log['middle_name'] ? $log['middle_name'].' ' : '').$log['last_name']);
                    $shortName = trim($log['first_name'].' '.$log['last_name']);
                    $isOpen    = empty($log['time_out']);
                ?>
                <tr>
                    <td>
                        <a href="#"
                           onclick="openHistoryModal(<?= (int)$log['visitor_id'] ?>,'<?= addslashes(e($shortName)) ?>');return false;"
                           style="color:var(--usc-blue);font-weight:700;text-decoration:none;border-bottom:1px dotted var(--usc-blue);">
                            <?= e($fullName) ?>
                        </a>
                    </td>
                    <td style="font-family:monospace;font-size:.83rem;">
                        <?= $log['id_number'] ? e($log['id_number']) : '<span style="color:#aaa;font-family:inherit;font-size:.82rem">Guest</span>' ?>
                    </td>
                    <td><?= e($log['barangay']) ?></td>
                    <td><?= e($log['city']) ?></td>
                    <td><?= e($log['province']) ?></td>
                    <td style="font-size:.83rem"><?= e($log['contact_number']) ?></td>
                    <td style="white-space:nowrap;font-size:.83rem"><?= formatPHTime($log['time_in']) ?></td>
                    <td style="white-space:nowrap;font-size:.83rem"><?= formatPHTime($log['time_out']) ?></td>
                    <td>
                        <?php if ($isOpen): ?>
                            <span class="badge badge-in">&#128994; In</span>
                        <?php else: ?>
                            <span class="badge badge-out">&#9898; Out</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="POST" onsubmit="return confirmDelete('<?= addslashes(e($shortName)) ?>')" style="display:inline">
                            <input type="hidden" name="visitor_id" value="<?= (int)$log['visitor_id'] ?>">
                            <button type="submit" name="delete_visitor" class="btn btn-danger btn-sm">&#128465;</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<!-- Visit History Modal -->
<div id="historyModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <span id="modalVisitorName">Visit History</span>
            <button class="modal-close" onclick="closeHistoryModal()">&times;</button>
        </div>
        <div id="modalBody" class="modal-body"></div>
        <div class="modal-footer">
            <button onclick="closeHistoryModal()" class="btn btn-outline btn-sm">Close</button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
