
<section id="gallery" class="gallery-section">
    <div class="container">
        <h2 class="section-title">
            <span>GALLERY</span>
        </h2>
        
        <div class="gallery-container">
            <?php if (empty($data['gallery'])): ?>
                <div class="no-gallery">
                    <p>No gallery images available at the moment. Check back soon!</p>
                </div>
            <?php else: ?>
                <div class="gallery-grid">
                    <?php 
                    error_log("Gallery data in template: " . print_r($data['gallery'], true));
                    // Reverse the array to show newest images first
                    $gallery_items = array_reverse($data['gallery']);
                    foreach ($gallery_items as $image): ?>
                        <?php if (!empty($image['src'])): ?>
                        <div class="gallery-item">
                            <?php 
                            // Debug info for images
                            error_log("Image source: " . $image['src']);
                            // Determine correct path - handle both absolute and relative paths
                            $img_path = $image['src'];
                            if (strpos($img_path, '/') === 0) {
                                // Remove leading slash if present as we're using relative paths
                                $img_path = ltrim($img_path, '/');
                            }
                            ?>
                            <img src="<?php echo htmlspecialchars($img_path); ?>" alt="<?php echo htmlspecialchars(isset($image['alt']) ? $image['alt'] : ''); ?>" />
                            <div class="gallery-overlay">
                                <div class="gallery-caption"><?php echo htmlspecialchars(isset($image['alt']) ? $image['alt'] : ''); ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                
                <script>
                // Add touch event handlers for mobile devices
                document.addEventListener('DOMContentLoaded', function() {
                    // Check if it's a touch device
                    var isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
                    
                    if (isTouchDevice) {
                        var galleryItems = document.querySelectorAll('.gallery-item');
                        
                        galleryItems.forEach(function(item) {
                            // Show overlay on touch
                            item.addEventListener('touchstart', function(e) {
                                var overlay = this.querySelector('.gallery-overlay');
                                if (overlay) {
                                    overlay.style.opacity = '1';
                                }
                            });
                            
                            // Hide overlay when touch ends
                            item.addEventListener('touchend', function(e) {
                                // Small delay to allow user to read the text
                                setTimeout(function() {
                                    var overlay = item.querySelector('.gallery-overlay');
                                    if (overlay) {
                                        overlay.style.opacity = '0';
                                    }
                                }, 2000); // 2 second delay before hiding
                            });
                        });
                    }
                });
                </script>
                
                <style>
                /* Mobile touch styles for gallery */
                @media (max-width: 768px) {
                    .gallery-item {
                        position: relative;
                        overflow: hidden;
                    }
                    
                    .gallery-overlay {
                        position: absolute;
                        bottom: 0;
                        left: 0;
                        right: 0;
                        background-color: rgba(0, 0, 0, 0.7);
                        padding: 10px;
                        opacity: 0;
                        transition: opacity 0.3s ease;
                    }
                    
                    .gallery-caption {
                        color: white;
                        text-align: center;
                        font-size: 14px;
                    }
                }
                </style>
            <?php endif; ?>
        </div>
    </div>
</section>
