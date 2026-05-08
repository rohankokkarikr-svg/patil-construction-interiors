<?php
// ============================================================
// ADMIN STATISTICS MANAGEMENT
// ============================================================
session_start();
require_once '../includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$db = getDB();
$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'update') {
            try {
                $statId = $_POST['stat_id'];
                $statCount = (int)$_POST['stat_count'];
                $statSuffix = $_POST['stat_suffix'];
                $statLabel = $_POST['stat_label'];
                $statIcon = $_POST['stat_icon'];
                $statOrder = (int)$_POST['stat_order'];
                $isActive = isset($_POST['is_active']) ? 1 : 0;
                
                $stmt = $db->prepare("UPDATE site_statistics SET stat_count = ?, stat_suffix = ?, stat_label = ?, stat_icon = ?, stat_order = ?, is_active = ? WHERE id = ?");
                $stmt->execute([$statCount, $statSuffix, $statLabel, $statIcon, $statOrder, $isActive, $statId]);
                
                $message = 'Statistics updated successfully!';
            } catch (PDOException $e) {
                $error = 'Error updating statistics: ' . $e->getMessage();
            }
        } elseif ($_POST['action'] === 'add') {
            try {
                $statKey = $_POST['stat_key'];
                $statLabel = $_POST['stat_label'];
                $statCount = (int)$_POST['stat_count'];
                $statSuffix = $_POST['stat_suffix'];
                $statIcon = $_POST['stat_icon'];
                $statOrder = (int)$_POST['stat_order'];
                
                $stmt = $db->prepare("INSERT INTO site_statistics (stat_key, stat_label, stat_count, stat_suffix, stat_icon, stat_order) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$statKey, $statLabel, $statCount, $statSuffix, $statIcon, $statOrder]);
                
                $message = 'New statistic added successfully!';
            } catch (PDOException $e) {
                $error = 'Error adding statistic: ' . $e->getMessage();
            }
        } elseif ($_POST['action'] === 'delete') {
            try {
                $statId = $_POST['stat_id'];
                $stmt = $db->prepare("DELETE FROM site_statistics WHERE id = ?");
                $stmt->execute([$statId]);
                
                $message = 'Statistic deleted successfully!';
            } catch (PDOException $e) {
                $error = 'Error deleting statistic: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all statistics
$statistics = $db->query("SELECT * FROM site_statistics ORDER BY stat_order ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics Management | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --clr-bg: #0a0e14;
            --clr-card: #141b25;
            --clr-accent: #ffb400;
            --clr-border: rgba(255, 180, 0, 0.2);
            --clr-text: #ffffff;
            --clr-text-muted: #8892b0;
        }

        body {
            background: var(--clr-bg);
            color: var(--clr-text);
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 20px;
        }

        .admin-header {
            background: var(--clr-card);
            border: 1px solid var(--clr-border);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-title {
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--clr-accent);
            margin: 0;
        }

        .btn-custom {
            background: var(--clr-accent);
            color: #000;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }

        .btn-custom:hover {
            background: #ffcc00;
            color: #000;
            text-decoration: none;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--clr-card);
            border: 1px solid var(--clr-border);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s;
        }

        .stat-card:hover {
            border-color: var(--clr-accent);
            box-shadow: 0 0 20px rgba(255, 180, 0, 0.1);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-icon {
            font-size: 2rem;
            color: var(--clr-accent);
        }

        .stat-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--clr-text);
            padding: 0.3rem 0.8rem;
            border-radius: 4px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-action:hover {
            background: var(--clr-accent);
            color: #000;
            border-color: var(--clr-accent);
        }

        .btn-action.delete:hover {
            background: #ff4d4d;
            border-color: #ff4d4d;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--clr-text);
            border-radius: 6px;
            padding: 0.5rem;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--clr-accent);
            color: var(--clr-text);
            box-shadow: 0 0 0 0.2rem rgba(255, 180, 0, 0.25);
        }

        .form-label {
            color: var(--clr-text-muted);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .alert-custom {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: rgba(39, 174, 96, 0.1);
            border: 1px solid rgba(39, 174, 96, 0.3);
            color: #27ae60;
        }

        .alert-error {
            background: rgba(231, 76, 60, 0.1);
            border: 1px solid rgba(231, 76, 60, 0.3);
            color: #e74c3c;
        }

        .modal-content {
            background: var(--clr-card);
            border: 1px solid var(--clr-border);
            color: var(--clr-text);
        }

        .modal-header {
            border-bottom: 1px solid var(--clr-border);
        }

        .modal-footer {
            border-top: 1px solid var(--clr-border);
        }

        .form-check-input:checked {
            background-color: var(--clr-accent);
            border-color: var(--clr-accent);
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1 class="admin-title">Statistics Management</h1>
        <div>
            <a href="index.php" class="btn-custom">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="../index.php" class="btn-custom">
                <i class="fas fa-home"></i> View Site
            </a>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="alert-custom alert-success">
            <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert-custom alert-error">
            <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="stats-grid">
        <?php foreach ($statistics as $stat): ?>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="<?= htmlspecialchars($stat['stat_icon']) ?>"></i>
                    </div>
                    <div class="stat-actions">
                        <button class="btn-action" onclick="editStat(<?= $stat['id'] ?>)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-action delete" onclick="deleteStat(<?= $stat['id'] ?>)">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
                <h4><?= htmlspecialchars($stat['stat_label']) ?></h4>
                <h2 style="color: var(--clr-accent); font-size: 2rem; font-weight: 700;">
                    <?= number_format($stat['stat_count']) ?><?= htmlspecialchars($stat['stat_suffix']) ?>
                </h2>
                <small class="text-muted">
                    Order: <?= $stat['stat_order'] ?> | 
                    Status: <?= $stat['is_active'] ? 'Active' : 'Inactive' ?>
                </small>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="text-align: center;">
        <button class="btn-custom" onclick="addNewStat()">
            <i class="fas fa-plus"></i> Add New Statistic
        </button>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Statistic</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="stat_id" id="edit_stat_id">
                        
                        <div class="mb-3">
                            <label class="form-label">Label</label>
                            <input type="text" class="form-control" name="stat_label" id="edit_stat_label" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Count</label>
                            <input type="number" class="form-control" name="stat_count" id="edit_stat_count" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Suffix</label>
                            <input type="text" class="form-control" name="stat_suffix" id="edit_stat_suffix" maxlength="10">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Icon Class</label>
                            <input type="text" class="form-control" name="stat_icon" id="edit_stat_icon" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Order</label>
                            <input type="number" class="form-control" name="stat_order" id="edit_stat_order" required>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active" checked>
                                <label class="form-check-label" for="edit_is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Statistic</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        
                        <div class="mb-3">
                            <label class="form-label">Key (unique identifier)</label>
                            <input type="text" class="form-control" name="stat_key" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Label</label>
                            <input type="text" class="form-control" name="stat_label" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Count</label>
                            <input type="number" class="form-control" name="stat_count" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Suffix</label>
                            <input type="text" class="form-control" name="stat_suffix" maxlength="10" placeholder="+, etc.">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Icon Class</label>
                            <input type="text" class="form-control" name="stat_icon" required placeholder="fas fa-building">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Order</label>
                            <input type="number" class="form-control" name="stat_order" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Add Statistic</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <form method="POST" id="deleteForm" style="display: none;">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="stat_id" id="delete_stat_id">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editStat(id) {
            // Fetch statistic data
            const stats = <?= json_encode($statistics) ?>;
            const stat = stats.find(s => s.id == id);
            
            if (stat) {
                document.getElementById('edit_stat_id').value = stat.id;
                document.getElementById('edit_stat_label').value = stat.stat_label;
                document.getElementById('edit_stat_count').value = stat.stat_count;
                document.getElementById('edit_stat_suffix').value = stat.stat_suffix;
                document.getElementById('edit_stat_icon').value = stat.stat_icon;
                document.getElementById('edit_stat_order').value = stat.stat_order;
                document.getElementById('edit_is_active').checked = stat.is_active == 1;
                
                new bootstrap.Modal(document.getElementById('editModal')).show();
            }
        }

        function addNewStat() {
            new bootstrap.Modal(document.getElementById('addModal')).show();
        }

        function deleteStat(id) {
            if (confirm('Are you sure you want to delete this statistic?')) {
                document.getElementById('delete_stat_id').value = id;
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
</body>
</html>
