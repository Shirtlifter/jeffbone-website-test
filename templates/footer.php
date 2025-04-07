
<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-logo">DJ JEFF BONE</div>
            
            <div class="footer-nav">
                <ul class="footer-links">
                    <li><a href="#about">About</a></li>
                    <li><a href="#events">Events</a></li>
                    <li><a href="#music">Music</a></li>
                    <li><a href="#gallery">Gallery</a></li>
                    <li><a href="#newsbuzz">Newsbuzz</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            
            <div class="footer-social">
                <?php if (!empty($data['contact']['socials']['instagram'])): ?>
                    <a href="<?php echo htmlspecialchars($data['contact']['socials']['instagram']); ?>" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-instagram"></i>
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($data['contact']['socials']['facebook'])): ?>
                    <a href="<?php echo htmlspecialchars($data['contact']['socials']['facebook']); ?>" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($data['contact']['socials']['twitter'])): ?>
                    <a href="<?php echo htmlspecialchars($data['contact']['socials']['twitter']); ?>" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-x"></i>
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($data['contact']['socials']['soundcloud'])): ?>
                    <a href="<?php echo htmlspecialchars($data['contact']['socials']['soundcloud']); ?>" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-soundcloud"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> DJ Jeff Bone. All rights reserved.</p>
        </div>
    </div>
    
    <script src="assets/js/main.js"></script>
    <script>
    // Fix for any potential mobile overflow issues
    document.addEventListener('DOMContentLoaded', function() {
        // Find any elements that might be causing overflow
        const checkForOverflow = function() {
            const body = document.body;
            const html = document.documentElement;
            if (body.offsetWidth > window.innerWidth) {
                console.log('Body is causing overflow');
            }
            
            // Check all iframes and make sure they're responsive
            const iframes = document.querySelectorAll('iframe');
            iframes.forEach(function(iframe) {
                iframe.setAttribute('width', '100%');
                const parent = iframe.parentElement;
                if (parent) {
                    parent.style.overflow = 'hidden';
                    parent.style.maxWidth = '100%';
                }
            });
        };
        
        checkForOverflow();
        window.addEventListener('resize', checkForOverflow);
        
        // Fix footer links wrapping on mobile
        if (window.innerWidth < 768) {
            const footerLinks = document.querySelector('.footer-links');
            if (footerLinks) {
                footerLinks.style.display = 'flex';
                footerLinks.style.flexWrap = 'wrap';
                footerLinks.style.justifyContent = 'center';
                
                const listItems = footerLinks.querySelectorAll('li');
                listItems.forEach(function(item) {
                    item.style.margin = '0 8px 8px 8px';
                });
            }
        }
    });
    </script>
    <style>
    @media (max-width: 768px) {
        .footer-links {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding-left: 0;
            margin: 0 -8px;
        }
        
        .footer-links li {
            margin: 0 8px 8px 8px;
        }
        
        .footer-content {
            padding-bottom: 10px;
        }
        
        .footer-social {
            margin-top: 15px;
        }
    }
    </style>
</body>
</html>
