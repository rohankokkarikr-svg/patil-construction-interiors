<?php
// ============================================================
// MODERN ADMIN PIN LOGIN — admin/login.php
// ============================================================
session_start();
require_once '../includes/db.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: /contraction/admin/index.php'); exit;
}

$error = '';
$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pin = trim($_POST['pin'] ?? '');

    if (strlen($pin) === 4 && is_numeric($pin)) {
        try {
            // Check for login attempts/lockout
            $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = 'admin' LIMIT 1");
            $stmt->execute();
            $admin = $stmt->fetch();

            if ($admin) {
                // Rate limiting (30 min lockout after 5 attempts)
                $lockoutTime = 30 * 60; 
                if ($admin['login_attempts'] >= 5 && (time() - strtotime($admin['last_attempt'] ?? '')) < $lockoutTime) {
                    $error = 'Too many attempts. Locked for 30 min.';
                } else {
                    if (password_verify($pin, $admin['admin_pin'])) {
                        // Success
                        $db->prepare("UPDATE admin_users SET login_attempts = 0, last_attempt = NULL WHERE id = ?")
                           ->execute([$admin['id']]);
                        
                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_username']  = $admin['username'];
                        $_SESSION['last_login_time'] = date('H:i:s');
                        
                        header('Location: /contraction/admin/index.php'); exit;
                    } else {
                        // Failure
                        $db->prepare("UPDATE admin_users SET login_attempts = login_attempts + 1, last_attempt = CURRENT_TIMESTAMP WHERE id = ?")
                           ->execute([$admin['id']]);
                        $error = 'Invalid PIN. Access Denied.';
                    }
                }
            }
        } catch (PDOException $e) {
            $error = 'System connectivity error.';
        }
    } else {
        $error = 'Entry must be 4 digits.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Secure Access | Construction Engineering</title>
  <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --clr-bg: #0a0e14;
      --clr-card: #141b25;
      --clr-accent: #ffb400;
      --clr-border: rgba(255, 180, 0, 0.2);
    }

    body {
      background: var(--clr-bg);
      color: #fff;
      font-family: 'Outfit', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      margin: 0;
    }

    /* Animated background */
    .bg-animation {
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      z-index: -1;
      background: radial-gradient(circle at 50% 50%, #1a222c 0%, #0a0e14 100%);
    }

    .login-container {
      width: 100%;
      max-width: 400px;
      padding: 2rem;
      animation: fadeIn 0.8s ease-out;
    }

    .glass-card {
      background: var(--clr-card);
      border: 1px solid var(--clr-border);
      border-radius: 24px;
      padding: 3rem 2rem;
      box-shadow: 0 20px 40px rgba(0,0,0,0.4), 0 0 20px rgba(255, 180, 0, 0.05);
      text-align: center;
      backdrop-filter: blur(10px);
    }

    .brand-icon {
      font-size: 3rem;
      color: var(--clr-accent);
      margin-bottom: 1.5rem;
      text-shadow: 0 0 15px rgba(255, 180, 0, 0.4);
    }

    h1 {
      font-family: 'Rajdhani', sans-serif;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 2px;
      font-size: 1.75rem;
      margin-bottom: 0.5rem;
    }

    p.subtitle {
      color: #8892b0;
      font-size: 0.9rem;
      margin-bottom: 2.5rem;
    }

    /* PIN Dots */
    .pin-display {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-bottom: 2.5rem;
    }

    .pin-dot {
      width: 16px;
      height: 16px;
      border: 2px solid var(--clr-border);
      border-radius: 50%;
      transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .pin-dot.active {
      background: var(--clr-accent);
      border-color: var(--clr-accent);
      box-shadow: 0 0 10px var(--clr-accent);
      transform: scale(1.2);
    }

    /* Keypad */
    .keypad {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .key {
      width: 100%;
      aspect-ratio: 1;
      background: rgba(255,255,255,0.03);
      border: 1px solid rgba(255,255,255,0.05);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
      font-weight: 600;
      cursor: pointer;
      user-select: none;
      transition: all 0.2s;
    }

    .key:hover {
      background: rgba(255, 180, 0, 0.1);
      border-color: var(--clr-accent);
      color: var(--clr-accent);
    }

    .key:active {
      transform: scale(0.9);
    }

    .key.action {
      background: rgba(255, 180, 0, 0.05);
      color: var(--clr-accent);
    }

    /* Error Message */
    .error-msg {
      color: #ff4d4d;
      font-size: 0.85rem;
      margin-bottom: 1.5rem;
      min-height: 1.2rem;
      font-weight: 500;
    }

    .loading-overlay {
      position: absolute;
      top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(10, 14, 20, 0.8);
      display: none;
      align-items: center;
      justify-content: center;
      border-radius: 24px;
      z-index: 10;
    }

    .spinner {
      width: 40px; height: 40px;
      border: 4px solid rgba(255, 180, 0, 0.1);
      border-top: 4px solid var(--clr-accent);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin { 100% { transform: rotate(360deg); } }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    .shake { animation: shake 0.4s ease-in-out; }
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-10px); }
      75% { transform: translateX(10px); }
    }
  </style>
</head>
<body>

<div class="bg-animation"></div>

<div class="login-container">
  <div class="glass-card" id="loginCard">
    <div class="loading-overlay" id="loader">
      <div class="spinner"></div>
    </div>

    <div class="brand-icon">
      <i class="fas fa-hard-hat"></i>
    </div>
    <h1>Secure Access</h1>
    <p class="subtitle">Enter 4-digit site terminal PIN</p>

    <div class="error-msg" id="errorDisplay"><?= htmlspecialchars($error) ?></div>

    <div class="pin-display" id="pinDisplay">
      <div class="pin-dot"></div>
      <div class="pin-dot"></div>
      <div class="pin-dot"></div>
      <div class="pin-dot"></div>
    </div>

    <div class="keypad">
      <div class="key" data-val="1">1</div>
      <div class="key" data-val="2">2</div>
      <div class="key" data-val="3">3</div>
      <div class="key" data-val="4">4</div>
      <div class="key" data-val="5">5</div>
      <div class="key" data-val="6">6</div>
      <div class="key" data-val="7">7</div>
      <div class="key" data-val="8">8</div>
      <div class="key" data-val="9">9</div>
      <div class="key action" data-val="clear"><i class="fas fa-times"></i></div>
      <div class="key" data-val="0">0</div>
      <div class="key action" data-val="back"><i class="fas fa-backspace"></i></div>
    </div>

    <form id="pinForm" method="POST" style="display:none;">
      <input type="hidden" name="pin" id="pinInput">
    </form>
    
    <a href="../index.php" style="color:rgba(255,255,255,0.3); font-size:0.8rem; text-decoration:none;">
      <i class="fas fa-arrow-left me-1"></i> Exit Terminal
    </a>
  </div>
</div>

<script>
  const dots = document.querySelectorAll('.pin-dot');
  const inputField = document.getElementById('pinInput');
  const form = document.getElementById('pinForm');
  const keys = document.querySelectorAll('.key');
  const errorDisplay = document.getElementById('errorDisplay');
  const loginCard = document.getElementById('loginCard');
  const loader = document.getElementById('loader');

  let currentPin = "";

  function updateDots() {
    dots.forEach((dot, index) => {
      if (index < currentPin.length) {
        dot.classList.add('active');
      } else {
        dot.classList.remove('active');
      }
    });

    if (currentPin.length === 4) {
      submitPin();
    }
  }

  function submitPin() {
    inputField.value = currentPin;
    loader.style.display = 'flex';
    setTimeout(() => {
      form.submit();
    }, 600);
  }

  keys.forEach(key => {
    key.addEventListener('click', () => {
      const val = key.getAttribute('data-val');
      
      if (val === "clear") {
        currentPin = "";
      } else if (val === "back") {
        currentPin = currentPin.slice(0, -1);
      } else if (currentPin.length < 4) {
        currentPin += val;
      }
      
      updateDots();
    });
  });

  // Handle errors
  if ("<?= $error ?>") {
    loginCard.classList.add('shake');
    setTimeout(() => loginCard.classList.remove('shake'), 400);
  }

  // Keyboard support
  document.addEventListener('keydown', (e) => {
    if (e.key >= '0' && e.key <= '9') {
      if (currentPin.length < 4) {
        currentPin += e.key;
        updateDots();
      }
    } else if (e.key === 'Backspace') {
      currentPin = currentPin.slice(0, -1);
      updateDots();
    } else if (e.key === 'Escape') {
      currentPin = "";
      updateDots();
    }
  });
</script>

</body>
</html>
