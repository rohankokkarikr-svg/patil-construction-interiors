<?php
// ============================================================
// Helper Functions
// ============================================================

function sanitize(string $input): string {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function formatDate(string $date): string {
    return date('M Y', strtotime($date));
}

function certStatus(string|null $expiry): string {
    if (!$expiry) return 'valid'; // No expiry
    $exp = new DateTime($expiry);
    $now = new DateTime();
    $diff = $now->diff($exp);
    if ($exp < $now) return 'expired';
    if ($diff->days <= 90) return 'expiring';
    return 'valid';
}

function certStatusLabel(string|null $expiry): string {
    $s = certStatus($expiry);
    return match($s) {
        'expired'  => 'Expired',
        'expiring' => 'Expiring Soon',
        default    => 'Valid',
    };
}

function getFeaturedProjects(): array {
    $db = getDB();
    if ($db === null) return [];
    $stmt = $db->prepare("SELECT * FROM projects WHERE is_featured = 1 ORDER BY created_at DESC LIMIT 3");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getAllProjects(string $category = ''): array {
    $db = getDB();
    if ($db === null) return [];
    if ($category && $category !== 'all') {
        $stmt = $db->prepare("SELECT * FROM projects WHERE category = ? ORDER BY created_at DESC");
        $stmt->execute([$category]);
    } else {
        $stmt = $db->prepare("SELECT * FROM projects ORDER BY created_at DESC");
        $stmt->execute();
    }
    return $stmt->fetchAll();
}

function getProjectById(int $id): array|false {
    $db = getDB();
    if ($db === null) return false;
    $stmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getAllCertifications(): array {
    $db = getDB();
    if ($db === null) return [];
    $stmt = $db->prepare("SELECT * FROM certifications ORDER BY issue_date DESC");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getContactCount(bool $unread = false): int {
    $db = getDB();
    if ($db === null) return 0;
    $sql = $unread ? "SELECT COUNT(*) FROM contacts WHERE is_read = 0" : "SELECT COUNT(*) FROM contacts";
    return (int)$db->query($sql)->fetchColumn();
}

function getProjectCount(): int {
    $db = getDB();
    if ($db === null) return 0;
    return (int)$db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
}

function isAdminLoggedIn(): bool {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireAdmin(): void {
    if (!isAdminLoggedIn()) {
        header('Location: /admin/login.php');
        exit;
    }
}

function redirect(string $url): void {
    header("Location: $url");
    exit;
}

function toolBadges(string $tools): string {
    $arr = array_filter(array_map('trim', explode(',', $tools)));
    $html = '';
    foreach ($arr as $tool) {
        $html .= '<span class="tool-badge">' . sanitize($tool) . '</span>';
    }
    return $html;
}

function get_img_src(?string $path): string {
    if (empty($path)) return '';
    if (strpos($path, 'data:') === 0 || strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
        return $path;
    }
    return (strpos($path, '/') === 0) ? $path : '/' . $path;
}
