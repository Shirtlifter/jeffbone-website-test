
<section id="videos" class="py-16 lg:py-24 bg-black">
    <div class="container mx-auto px-4">
        <h2 class="section-title">
            <span>VIDEOS</span>
        </h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <?php if (empty($data['videos'])): ?>
                <div class="col-span-2 text-center py-10">
                    <p style="color: #FFD700;">No videos available at the moment. Check back soon!</p>
                </div>
            <?php else: ?>
                <?php foreach ($data['videos'] as $index => $video): ?>
                    <div class="video-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <div class="video-embed-wrapper">
                            <?php
                            $embed_url = '';
                            if (isset($video['embed']) && !empty($video['embed'])) {
                                // Extract YouTube video ID
                                $youtube_id = '';
                                if (strpos($video['embed'], 'youtube.com/watch?v=') !== false) {
                                    $parts = parse_url($video['embed']);
                                    if (isset($parts['query'])) {
                                        parse_str($parts['query'], $query);
                                        if (isset($query['v'])) {
                                            $youtube_id = $query['v'];
                                        }
                                    }
                                } elseif (strpos($video['embed'], 'youtu.be/') !== false) {
                                    $parts = explode('/', $video['embed']);
                                    $youtube_id = end($parts);
                                }
                                
                                if (!empty($youtube_id)) {
                                    $embed_url = "https://www.youtube.com/embed/" . htmlspecialchars($youtube_id);
                                }
                            }
                            ?>
                            
                            <?php if (!empty($embed_url)): ?>
                                <iframe 
                                    src="<?php echo $embed_url; ?>" 
                                    title="<?php echo htmlspecialchars(isset($video['title']) ? $video['title'] : 'YouTube video player'); ?>"
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                    class="video-iframe"
                                ></iframe>
                            <?php else: ?>
                                <div class="bg-gray-900 flex items-center justify-center h-full">
                                    <p style="color: #FFD700;">Video unavailable</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="text-xl font-semibold mb-2 px-3 py-2"><?php echo htmlspecialchars(isset($video['title']) ? $video['title'] : ''); ?></h3>
                            <?php if (isset($video['description']) && !empty($video['description'])): ?>
                                <p class="text-gray-400 px-3 py-2"><?php echo htmlspecialchars($video['description']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="video-background-elements">
        <div class="video-deco-line"></div>
        <div class="video-deco-circle"></div>
        <div class="video-deco-dots"></div>
    </div>
</section>

<style>
/* Modern video section styling */
#videos {
    position: relative;
    padding: 5rem 0;
    background-color: #000;
    background-image: linear-gradient(to bottom, #0a0a0a, #000);
    overflow: hidden;
}

.video-card {
    background: rgba(10, 10, 10, 0.9);
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1px solid rgba(255,255,255,0.05);
    box-shadow: 0 15px 35px rgba(0,0,0,0.5);
    position: relative;
    display: flex;
    flex-direction: column;
}

.video-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.8);
    border-color: rgba(255,215,0,0.2);
}

.video-embed-wrapper {
    position: relative;
    width: 100%;
    padding-top: 56.25%; /* 16:9 aspect ratio */
    overflow: hidden;
}

.video-iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
}

.video-card .p-4 {
    padding: 1.5rem;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
}

.video-card .p-4 h3 {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #FFD700;
    text-shadow: 0 2px 5px rgba(0,0,0,0.5);
    position: relative;
    padding-left: 1rem;
    transition: all 0.3s ease;
}

.video-card:hover .p-4 h3 {
    transform: translateX(5px);
}

.video-card .p-4 h3:before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 20px;
    background: linear-gradient(to bottom, #FFD700, transparent);
}

.video-card .p-4 p {
    font-size: 0.95rem;
    line-height: 1.6;
    color: rgba(255,255,255,0.7);
}

/* Decorative background elements */
.video-background-elements {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    pointer-events: none;
}

.video-deco-line {
    position: absolute;
    width: 80%;
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(255,215,0,0.2), transparent);
    top: 20%;
    left: 10%;
    transform: rotate(-1deg);
}

.video-deco-circle {
    position: absolute;
    width: 500px;
    height: 500px;
    border-radius: 50%;
    border: 1px solid rgba(255,215,0,0.05);
    bottom: -200px;
    right: -100px;
}

.video-deco-dots {
    position: absolute;
    width: 200px;
    height: 200px;
    background-image: radial-gradient(circle, rgba(255,215,0,0.1) 1px, transparent 1px);
    background-size: 20px 20px;
    top: 10%;
    left: 5%;
    opacity: 0.3;
}

/* Fix for container */
.container {
    position: relative;
    z-index: 1;
}

/* Grid system */
.grid {
    display: grid;
}

.grid-cols-1 {
    grid-template-columns: repeat(1, minmax(0, 1fr));
}

.gap-10 {
    gap: 2.5rem;
}

.col-span-2 {
    grid-column: span 2 / span 2;
}

.text-center {
    text-align: center;
}

.py-10 {
    padding-top: 2.5rem;
    padding-bottom: 2.5rem;
}

/* Media queries */
@media (min-width: 1024px) {
    .lg\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    
    .lg\:py-24 {
        padding-top: 6rem;
        padding-bottom: 6rem;
    }
}

@media (max-width: 640px) {
    .video-card .p-4 h3 {
        font-size: 1.1rem;
    }
    
    .video-card .p-4 p {
        font-size: 0.9rem;
    }
}
</style>
