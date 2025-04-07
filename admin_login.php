<?php
// Start session at the very beginning if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize authentication status
$isAuthenticated = isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;

// Check for any messages
$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
if (isset($_SESSION['message'])) {
    unset($_SESSION['message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | DJ JEFF BONE</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <script>
    // Function to display toast messages
    function showToast(message, type = 'info') {
        const toastContainer = document.getElementById('toastContainer');
        if (!toastContainer) return;
        
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = message;
        
        toastContainer.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.classList.add('fade-out');
            setTimeout(() => {
                toastContainer.removeChild(toast);
            }, 500);
        }, 5000);
    }
    
    // Check for session messages on page load
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($message)): ?>
        showToast('<?php echo addslashes($message['text']); ?>', '<?php echo $message['type']; ?>');
        <?php endif; ?>
        
        // For debugging: Log to console
        console.log('Admin login page loaded');
        console.log('Authentication status: <?php echo ($isAuthenticated ? 'true' : 'false'); ?>');
    });
    </script>
</head>
<body class="admin-page">
    <div class="toast-container" id="toastContainer"></div>
    
    <!-- Debug information - only visible during development -->
    <?php if (isset($_GET['debug'])): ?>
    <div style="background-color: #ffeeee; color: #990000; padding: 10px; margin: 10px; border: 1px solid #ff0000;">
        <h3>Debug Information</h3>
        <p>Session authenticated: <?php echo (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] ? 'Yes' : 'No'); ?></p>
        <p>Form will submit to: auth.php (POST method)</p>
        <p>Default credentials: admin / password123</p>
    </div>
    <?php endif; ?>
    
    <div class="admin-login-container">
        <div class="admin-login-card">
            <div class="login-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h2>Admin Login</h2>
            <p class="login-desc">Enter your credentials to access the admin panel</p>
            
            <form action="auth.php" method="post" id="loginForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-primary btn-full">Login</button>
                
                <div class="login-footer">
                    <a href="index.php" class="return-link">Return to site</a>
                    <a href="auth.php" class="debug-link" style="margin-left: 10px; font-size: 0.8em; color: #aaa;">Auth Debug</a>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/js/admin.js"></script>
</body>
</html>
