<?php
// Start session at the very beginning
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define data directory and credentials file path
$data_dir = __DIR__ . '/data';
$credentials_file = $data_dir . '/admin_credentials.php';

// Log important information for debugging
error_log("Auth.php accessed. Method: " . $_SERVER['REQUEST_METHOD'] . ", Action: " . (isset($_GET['action']) ? $_GET['action'] : 'None'));

// Ensure data directory exists
if (!file_exists($data_dir)) {
    if (mkdir($data_dir, 0755, true)) {
        error_log("Created data directory: " . $data_dir);
    } else {
        error_log("ERROR: Failed to create data directory: " . $data_dir);
    }
}

// Variables to store credentials
$admin_username = 'admin';
$admin_password = null; // Will be loaded from file

// Create the credentials file if it doesn't exist
if (!file_exists($credentials_file)) {
    // Create default admin password
    $default_password_hash = password_hash('password123', PASSWORD_DEFAULT);
    
    $credentials_content = "<?php\n";
    $credentials_content .= "// Admin credentials\n";
    $credentials_content .= "\$admin_username = 'admin';\n";
    $credentials_content .= "\$admin_password = '{$default_password_hash}';\n";
    
    if (file_put_contents($credentials_file, $credentials_content)) {
        chmod($credentials_file, 0644);
        error_log("Created admin credentials file with default values");
    } else {
        error_log("ERROR: Failed to create credentials file at: " . $credentials_file);
    }
}

// Try to include the credentials file
if (file_exists($credentials_file)) {
    include_once($credentials_file);
    
    // Check if variables were properly loaded
    if (!isset($admin_username) || !isset($admin_password)) {
        error_log("WARNING: Credentials file exists but variables weren't loaded properly");
        
        // Set default values if not defined in the file
        if (!isset($admin_username)) $admin_username = 'admin';
        if (!isset($admin_password)) {
            // Only set a default password if none exists
            $admin_password = password_hash('password123', PASSWORD_DEFAULT);
            error_log("WARNING: Default password was set because none was found in credentials file");
        }
    }
    
    error_log("Loaded credentials from file. Username exists: " . (isset($admin_username) ? 'Yes' : 'No'));
} else {
    error_log("WARNING: Credentials file still does not exist after creation attempt");
}

// Handle change password request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'change_password') {
    error_log("Password change request received");
    
    // Check if user is authenticated
    if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'You must be logged in to change the password.'
        ];
        error_log("Password change failed: User not authenticated");
        header('Location: index.php?admin=true');
        exit;
    }
    
    $current_password = isset($_POST['current_password']) ? $_POST['current_password'] : '';
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    // Validate inputs
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'All fields are required'
        ];
        error_log("Password change failed: Empty fields");
        header('Location: index.php?admin=true');
        exit;
    }
    
    if ($new_password !== $confirm_password) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'New passwords do not match'
        ];
        error_log("Password change failed: Passwords don't match");
        header('Location: index.php?admin=true');
        exit;
    }
    
    // Verify current password - now we only check against the admin_password from file
    if (!isset($admin_password) || !password_verify($current_password, $admin_password)) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Current password is incorrect'
        ];
        error_log("Password change failed: Current password incorrect");
        header('Location: index.php?admin=true');
        exit;
    }
    
    // Create new password hash
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update credentials file
    $credentials_content = "<?php\n";
    $credentials_content .= "// Admin credentials\n";
    $credentials_content .= "\$admin_username = 'admin';\n";
    $credentials_content .= "\$admin_password = '{$new_password_hash}';\n";
    
    if (file_put_contents($credentials_file, $credentials_content)) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Password successfully updated!'
        ];
        error_log("Password change successful");
    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Failed to update password. Please try again.'
        ];
        error_log("Password change failed: Could not write to file");
    }
    
    header('Location: index.php?admin=true');
    exit;
}

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['action'])) {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    error_log("Login attempt - Username: " . $username);
    
    // Verify credentials - now we only check against the admin_password from file
    if ($username === $admin_username && isset($admin_password) && password_verify($password, $admin_password)) {
        $_SESSION['authenticated'] = true;
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Login successful!'
        ];
        error_log("Login successful for: " . $username);
        
        // Redirect to admin panel
        header('Location: index.php?admin=true');
        exit;
    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Invalid username or password. Please try again.'
        ];
        
        error_log("Login failed for: " . $username);
        
        // Redirect back to login page
        header('Location: index.php?admin=true');
        exit;
    }
}

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Clear all session data
    $_SESSION = array();
    
    // If a session cookie is used, unset that too
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destroy the session
    session_destroy();
    
    // Start a new session for the message
    session_start();
    $_SESSION['message'] = [
        'type' => 'success',
        'text' => 'Logged out successfully!'
    ];
    
    header('Location: index.php?admin=true');
    exit;
}

// For direct access debugging
if (!isset($_GET['action']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<!DOCTYPE html><html><head><title>Auth Debug</title></head><body>";
    echo "<h1>Auth Debugging Page</h1>";
    echo "<p>Session authenticated: " . (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] ? 'Yes' : 'No') . "</p>";
    echo "<p>Admin username expected: " . htmlspecialchars($admin_username) . "</p>";
    
    echo "<h2>Test Default Login</h2>";
    echo "<form method='post' action='auth.php'>";
    echo "<p>Username: <input type='text' name='username' value='admin'></p>";
    echo "<p>Password: <input type='password' name='password' value='password123'></p>";
    echo "<p><button type='submit'>Test Login</button></p>";
    echo "</form>";
    
    echo "<p><a href='index.php?admin=true'>Back to admin login</a></p>";
    echo "</body></html>";
    exit;
}
?>