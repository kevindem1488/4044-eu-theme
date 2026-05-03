/**
 * Main JavaScript for 4044.eu Theme
 */

(function() {
    'use strict';

    // Navigation Toggle
    document.addEventListener('DOMContentLoaded', function() {
        const navToggle = document.getElementById('nav-toggle');
        const mainNav = document.getElementById('main-nav');

        if (navToggle && mainNav) {
            navToggle.addEventListener('click', function() {
                mainNav.classList.toggle('active');
            });

            // Close menu when clicking on a link
            const navLinks = mainNav.querySelectorAll('a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mainNav.classList.remove('active');
                });
            });
        }
    });

    // Smooth Scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Add active class to current navigation link
    window.addEventListener('load', function() {
        const currentLocation = location.pathname;
        const navLinks = document.querySelectorAll('nav a');
        navLinks.forEach(link => {
            if (link.pathname === currentLocation) {
                link.classList.add('active');
            }
        });
    });

    // Image Lazy Loading
    if ('IntersectionObserver' in window) {
        const images = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });
        images.forEach(img => imageObserver.observe(img));
    }

    // Format timestamps
    function formatTime(date) {
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);

        if (seconds < 60) return 'just now';
        if (minutes < 60) return `${minutes}m ago`;
        if (hours < 24) return `${hours}h ago`;
        if (days < 7) return `${days}d ago`;
        return date.toLocaleDateString();
    }

    // Add to favorites
    window.addToFavorites = function(postId) {
        let favorites = JSON.parse(localStorage.getItem('4044_favorites') || '[]');
        if (!favorites.includes(postId)) {
            favorites.push(postId);
            localStorage.setItem('4044_favorites', JSON.stringify(favorites));
            showNotification('Added to favorites');
        }
    };

    // Show notification
    window.showNotification = function(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            background: ${type === 'success' ? '#28a745' : '#dc3545'};
            color: white;
            border-radius: 4px;
            z-index: 10000;
            animation: slideIn 0.3s ease;
        `;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    };

    // Share article
    window.shareArticle = function(title, url) {
        if (navigator.share) {
            navigator.share({
                title: title,
                url: url
            }).catch(err => console.log('Error sharing:', err));
        } else {
            const shareText = `${title} - ${url}`;
            navigator.clipboard.writeText(shareText).then(() => {
                showNotification('Link copied to clipboard');
            });
        }
    };

    // Real-time search
    const searchForm = document.querySelector('[role="search"]');
    if (searchForm) {
        const searchInput = searchForm.querySelector('input[type="search"]');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                const query = e.target.value;
                
                if (query.length > 2) {
                    searchTimeout = setTimeout(() => {
                        performSearch(query);
                    }, 300);
                }
            });
        }
    }

    function performSearch(query) {
        // Implementation for real-time search
        console.log('Searching for:', query);
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K for search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.querySelector('input[type="search"]');
            if (searchInput) searchInput.focus();
        }

        // Escape to close mobile menu
        if (e.key === 'Escape') {
            const mainNav = document.getElementById('main-nav');
            if (mainNav) mainNav.classList.remove('active');
        }
    });

    // Scroll to top button
    const scrollBtn = document.createElement('button');
    scrollBtn.innerHTML = '↑';
    scrollBtn.className = 'scroll-to-top';
    scrollBtn.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.3s;
        z-index: 999;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    `;
    document.body.appendChild(scrollBtn);

    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollBtn.style.opacity = '1';
            scrollBtn.style.pointerEvents = 'auto';
        } else {
            scrollBtn.style.opacity = '0';
            scrollBtn.style.pointerEvents = 'none';
        }
    });

    scrollBtn.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

})();