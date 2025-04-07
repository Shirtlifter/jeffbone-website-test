
<section id="events" class="events-section">
    <div class="container">
        <h2 class="section-title">
            <span>UPCOMING EVENTS</span>
        </h2>
        
        <?php if (empty($data['events'])): ?>
            <p class="no-events">No upcoming events scheduled at the moment. Check back soon!</p>
        <?php else: ?>
            <div class="events-grid">
                <?php 
                // Sort events by date - upcoming events first (closest to today at top)
                $events = $data['events'];
                $today = time();
                
                // First separate upcoming and past events
                $upcomingEvents = array();
                $pastEvents = array();
                
                foreach ($events as $event) {
                    $eventTime = strtotime($event['date']);
                    if ($eventTime >= $today) {
                        $upcomingEvents[] = $event;
                    } else {
                        $pastEvents[] = $event;
                    }
                }
                
                // Sort upcoming events (closest date first)
                usort($upcomingEvents, function($a, $b) {
                    return strtotime($a['date']) - strtotime($b['date']);
                });
                
                // Sort past events (most recent first)
                usort($pastEvents, function($a, $b) {
                    return strtotime($b['date']) - strtotime($a['date']);
                });
                
                // Display upcoming events first, then past events
                $sortedEvents = array_merge($upcomingEvents, $pastEvents);
                
                foreach ($sortedEvents as $event): 
                ?>
                    <div class="event-card">
                        <?php if (!empty($event['image'])): ?>
                            <div class="event-image">
                                <img src="<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['venue']); ?>" />
                            </div>
                        <?php endif; ?>
                        <div class="event-details">
                            <div class="event-date"><?php echo formatDate($event['date']); ?></div>
                            <h3 class="event-venue"><?php echo htmlspecialchars($event['venue']); ?></h3>
                            <div class="event-location"><?php echo htmlspecialchars($event['location']); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
