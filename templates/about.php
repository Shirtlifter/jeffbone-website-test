<section id="about" class="about-section">
    <div class="container">
        <h2 class="section-title">
            <span><?php echo htmlspecialchars($data['about']['title']); ?></span>
        </h2>
        <div class="about-content">
            <div class="about-image">
                <img src="<?php echo htmlspecialchars($data['about']['image']); ?>" alt="DJ Jeff Bone" />
            </div>
            <div class="about-text">
                <p class="text-left"><?php echo nl2br(htmlspecialchars($data['about']['description'])); ?></p>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3 class="stat-value"><?php echo htmlspecialchars(isset($data['about']['stats']['years']) ? $data['about']['stats']['years'] : '10+'); ?></h3>
                        <p class="stat-label"><?php echo htmlspecialchars(isset($data['about']['stats_titles']['years']) ? $data['about']['stats_titles']['years'] : 'Years Experience'); ?></p>
                    </div>
                    
                    <div class="stat-card">
                        <h3 class="stat-value"><?php echo htmlspecialchars(isset($data['about']['stats']['shows']) ? $data['about']['stats']['shows'] : '500+'); ?></h3>
                        <p class="stat-label"><?php echo htmlspecialchars(isset($data['about']['stats_titles']['shows']) ? $data['about']['stats_titles']['shows'] : 'Shows Played'); ?></p>
                    </div>
                    
                    <div class="stat-card">
                        <h3 class="stat-value"><?php echo htmlspecialchars(isset($data['about']['stats']['countries']) ? $data['about']['stats']['countries'] : '20+'); ?></h3>
                        <p class="stat-label"><?php echo htmlspecialchars(isset($data['about']['stats_titles']['countries']) ? $data['about']['stats_titles']['countries'] : 'Countries'); ?></p>
                    </div>
                    
                    <div class="stat-card">
                        <h3 class="stat-value"><?php echo htmlspecialchars(isset($data['about']['stats']['festivals']) ? $data['about']['stats']['festivals'] : '50+'); ?></h3>
                        <p class="stat-label"><?php echo htmlspecialchars(isset($data['about']['stats_titles']['festivals']) ? $data['about']['stats_titles']['festivals'] : 'Major Festivals'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>