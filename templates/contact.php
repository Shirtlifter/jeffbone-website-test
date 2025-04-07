<section id="contact" class="contact-section">
    <div class="container">
        <h2 class="section-title">
            <span>CONTACT</span>
        </h2>
        
        <div class="contact-container">
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-share-alt"></i>
                    <div class="contact-detail">
                        <h3>Social Media</h3>
                        <div class="social-links">
                            <?php if (!empty($data['contact']['socials']['instagram'])): ?>
                                <a href="<?php echo htmlspecialchars($data['contact']['socials']['instagram']); ?>" target="_blank" rel="noopener noreferrer">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($data['contact']['socials']['facebook'])): ?>
                                <a href="<?php echo htmlspecialchars($data['contact']['socials']['facebook']); ?>" target="_blank" rel="noopener noreferrer">
                                    <i class="fab fa-facebook"></i>
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
                </div>
            </div>
            
            <div class="contact-form">
                <h3>Send a Message</h3>
                <form id="contactForm" action="https://formspree.io/f/xdovkvnn" method="post" role="form" class="php-email-form">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="4" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>