<section class="hero">
    <div class="hero-content">
        <?php if (isset($data['hero']['background_type']) && $data['hero']['background_type'] === 'video' && isset($data['hero']['background_video']) && !empty($data['hero']['background_video'])): ?>
        <div class="hero-background-video">
            <video autoplay muted loop playsinline class="hero-video">
                <source src="<?php echo htmlspecialchars($data['hero']['background_video']); ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <?php else: ?>
        <div class="hero-background-image" style="background-image: url('<?php echo isset($data['hero']['background_image']) && !empty($data['hero']['background_image']) ? htmlspecialchars($data['hero']['background_image']) : 'assets/img/uploads/hero_background.jpg'; ?>')"></div>
        <?php endif; ?>
        
        <h1><?php echo htmlspecialchars(isset($data['hero']['title']) ? $data['hero']['title'] : 'DJ JEFF BONE'); ?></h1>
        <p class="tagline"><?php echo htmlspecialchars(isset($data['hero']['tagline']) ? $data['hero']['tagline'] : 'Electronic Music Artist'); ?></p>
        <div class="cta-buttons">
            <?php if (isset($data['hero']['button1_text']) && !empty($data['hero']['button1_text'])): ?>
            <a href="<?php echo htmlspecialchars(isset($data['hero']['button1_link']) ? $data['hero']['button1_link'] : '#music'); ?>" class="btn-primary"><?php echo htmlspecialchars($data['hero']['button1_text']); ?></a>
            <?php endif; ?>
            
            <?php if (isset($data['hero']['button2_text']) && !empty($data['hero']['button2_text'])): ?>
            <a href="<?php echo htmlspecialchars(isset($data['hero']['button2_link']) ? $data['hero']['button2_link'] : '#events'); ?>" class="btn-secondary"><?php echo htmlspecialchars($data['hero']['button2_text']); ?></a>
            <?php endif; ?>
        </div>
    </div>
</section>