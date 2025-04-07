
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>DJ JEFF BONE - Electronic Music Artist</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    <link rel="stylesheet" href="newsbuzz/css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
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
        
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-out',
            once: true,
            offset: 100
        });
    });
    </script>
    <style>
    /* Fix for horizontal scrolling on mobile */
    html, body {
        overflow-x: hidden;
        width: 100%;
        position: relative;
    }
    
    /* Ensure all content is properly contained */
    img {
        max-width: 100%;
        height: auto;
    }
    
    /* Fix for mobile menu */
    @media (max-width: 768px) {
        .nav-links {
            width: 100vw;
            max-width: 100%;
        }
    }
    
    /* Ensure gallery items don't overflow */
    .gallery-grid {
        width: 100%;
        box-sizing: border-box;
    }
    
    /* Modern header styling */
    .site-header {
        background: rgba(0,0,0,0.9);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    
    .logo {
        font-weight: 700;
        letter-spacing: 1px;
        background: linear-gradient(to right, #FFFFFF, #FFD700);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .nav-links a {
        position: relative;
        padding: 5px 0;
    }
    
    .nav-links a:after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 0;
        left: 0;
        background: linear-gradient(to right, #FFD700, transparent);
        transition: width 0.3s ease;
    }
    
    .nav-links a:hover:after {
        width: 100%;
    }
    </style>
</head>
<body>
    <div class="toast-container" id="toastContainer"></div>
    <header class="site-header">
        <div class="container">
            <nav class="main-nav">
                <div class="logo">DJ JEFF BONE</div>
                <div class="nav-toggle" id="navToggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <ul class="nav-links" id="navLinks">
                    <li><a href="#about" data-aos="fade-down" data-aos-delay="100">About</a></li>
                    <li><a href="#events" data-aos="fade-down" data-aos-delay="200">Events</a></li>
                    <li><a href="#music" data-aos="fade-down" data-aos-delay="300">Music</a></li>
                    <li><a href="#videos" data-aos="fade-down" data-aos-delay="400">Videos</a></li>
                    <li><a href="#gallery" data-aos="fade-down" data-aos-delay="500">Gallery</a></li>
                    <li><a href="#newsbuzz" data-aos="fade-down" data-aos-delay="600">Newsbuzz</a></li>
                    <li><a href="#contact" data-aos="fade-down" data-aos-delay="700">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>
