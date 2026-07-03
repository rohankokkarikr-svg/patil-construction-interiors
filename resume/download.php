<?php
// ============================================================
// RESUME DOWNLOAD — resume/download.php
// ============================================================

// If a real PDF has been placed here, serve it directly
$pdf = __DIR__ . '/engineer-cv.pdf';
if (file_exists($pdf)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="Pradeepgouda-B-Patil-Civil-Engineer-CV.pdf"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($pdf));
    ob_clean();
    flush();
    readfile($pdf);
    exit;
}

// Otherwise, redirect to the printable HTML resume
// (user can Ctrl+P / ⌘+P → Save as PDF from browser)
header('Location: /resume/engineer-cv.html');
exit;
