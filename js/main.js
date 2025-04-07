// DOM Elements
const navToggle = document.getElementById('navToggle');
const navLinks = document.getElementById('navLinks');
const showMoreEvents = document.getElementById('showMoreEvents');
const showLessEvents = document.getElementById('showLessEvents');
const hiddenEvents = document.getElementById('hiddenEvents');
const showLessContainer = document.getElementById('showLessContainer');
const toastContainer = document.getElementById('toastContainer');

// Mobile navigation toggle
if (navToggle && navLinks) {
  navToggle.addEventListener('click', () => {
    navToggle.classList.toggle('active');
    navLinks.classList.toggle('active');
  });
}

// Close menu when clicking on links
if (navLinks) {
  navLinks.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      navToggle.classList.remove('active');
      navLinks.classList.remove('active');
    });
  });
}

// Show/Hide events functionality
if (showMoreEvents && hiddenEvents && showLessContainer) {
  showMoreEvents.addEventListener('click', () => {
    hiddenEvents.classList.remove('hidden');
    showMoreEvents.parentElement.classList.add('hidden');
    showLessContainer.classList.remove('hidden');
  });
}

if (showLessEvents && hiddenEvents && showLessContainer) {
  showLessEvents.addEventListener('click', () => {
    hiddenEvents.classList.add('hidden');
    showLessContainer.classList.add('hidden');
    showMoreEvents.parentElement.classList.remove('hidden');
    // Scroll back to events section
    document.getElementById('events').scrollIntoView({ behavior: 'smooth' });
  });
}

// Smooth scrolling for anchor links
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const targetId = this.getAttribute('href');
      
      // Only handle # links that aren't just "#"
      if (targetId && targetId !== '#') {
        e.preventDefault();
        
        const targetElement = document.querySelector(targetId);
        
        if (targetElement) {
          console.log("Scrolling to:", targetId);
          
          // Offset for header height
          const headerOffset = 80;
          const elementPosition = targetElement.getBoundingClientRect().top;
          const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
          
          window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
          });
          
          // Close mobile menu if open
          if (navToggle && navLinks) {
            navToggle.classList.remove('active');
            navLinks.classList.remove('active');
          }
        } else {
          console.log("Target not found:", targetId);
        }
      }
    });
  });
});

// Toast notification function
function showToast(message, type = 'success') {
  if (!toastContainer) return;
  
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `
    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
    <span>${message}</span>
  `;
  
  toastContainer.appendChild(toast);
  
  // Remove toast after 3 seconds
  setTimeout(() => {
    toast.remove();
  }, 3000);
}

// Check for session messages (PHP)
document.addEventListener('DOMContentLoaded', function() {
  // This would normally be handled by PHP outputting JavaScript
  // For demo purposes, we'll check URL parameters
  const urlParams = new URLSearchParams(window.location.search);
  const messageType = urlParams.get('message_type');
  const messageText = urlParams.get('message_text');
  
  if (messageText && messageType) {
    showToast(messageText, messageType);
  }
});

// Header scroll effect
window.addEventListener('scroll', function() {
  const header = document.querySelector('.site-header');
  if (header) {
    if (window.scrollY > 100) {
      header.style.backgroundColor = 'rgba(7, 0, 12, 0.95)';
    } else {
      header.style.backgroundColor = 'rgba(7, 0, 12, 0.9)';
    }
  }
});