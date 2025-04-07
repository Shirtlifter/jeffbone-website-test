
<section id="music" class="music-section">
    <div class="container">
        <h2 class="section-title">
            <span>MUSIC</span>
        </h2>
        
        <div class="music-container">
            <?php if (empty($data['music'])): ?>
                <div class="no-music">
                    <p>No music tracks available at the moment. Check back soon!</p>
                </div>
            <?php else: ?>
                <div class="tracks-grid">
                    <?php foreach ($data['music'] as $track): ?>
                        <div class="track-card">
                            <h3 class="track-title"><?php echo htmlspecialchars($track['title']); ?></h3>
                            <div class="track-embed aspect-ratio-16-9">
                                <?php echo $track['embed']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
