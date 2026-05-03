/**
 * API Handler JavaScript for 4044.eu Theme
 */

(function() {
    'use strict';

    const API = {
        baseUrl: 4044Config.restUrl,
        nonce: 4044Config.nonce,

        // Get live updates
        getLiveUpdates: function(callback) {
            fetch(this.baseUrl + '4044/v1/live-updates')
                .then(response => {
                    if (!response.ok) throw new Error('Network error');
                    return response.json();
                })
                .then(data => callback(null, data))
                .catch(error => callback(error, null));
        },

        // Get sports matches
        getSportsMatches: function(callback) {
            fetch(this.baseUrl + '4044/v1/sports/matches')
                .then(response => {
                    if (!response.ok) throw new Error('Network error');
                    return response.json();
                })
                .then(data => callback(null, data))
                .catch(error => callback(error, null));
        },

        // Publish news with AI
        publishNewsWithAI: function(data, callback) {
            fetch(this.baseUrl + '4044/v1/news/publish', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': this.nonce
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => callback(null, data))
                .catch(error => callback(error, null));
        },

        // Get posts by category
        getPostsByCategory: function(categoryId, callback) {
            fetch(this.baseUrl + `wp/v2/posts?categories=${categoryId}`)
                .then(response => response.json())
                .then(data => callback(null, data))
                .catch(error => callback(error, null));
        },

        // Search posts
        searchPosts: function(query, callback) {
            fetch(this.baseUrl + `wp/v2/posts?search=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => callback(null, data))
                .catch(error => callback(error, null));
        }
    };

    // Global API object
    window.Api = API;

    // Auto-refresh live updates
    function initLiveUpdates() {
        const liveContainer = document.getElementById('live-updates-container');
        if (!liveContainer) return;

        function loadUpdates() {
            API.getLiveUpdates(function(err, data) {
                if (err) {
                    console.error('Error loading updates:', err);
                    return;
                }

                liveContainer.innerHTML = '';
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(item => {
                        const card = createArticleCard(item);
                        liveContainer.appendChild(card);
                    });
                } else {
                    liveContainer.innerHTML = '<p>No live updates at the moment.</p>';
                }
            });
        }

        loadUpdates();
        setInterval(loadUpdates, 30000); // Refresh every 30 seconds
    }

    // Auto-refresh sports matches
    function initSportsMatches() {
        const sportsTable = document.getElementById('sports-tbody');
        if (!sportsTable) return;

        function loadMatches() {
            API.getSportsMatches(function(err, data) {
                if (err) {
                    console.error('Error loading matches:', err);
                    return;
                }

                sportsTable.innerHTML = '';
                if (data.matches && data.matches.length > 0) {
                    data.matches.slice(0, 10).forEach(match => {
                        const row = createMatchRow(match);
                        sportsTable.appendChild(row);
                    });
                }
            });
        }

        loadMatches();
        setInterval(loadMatches, 60000); // Refresh every minute
    }

    // Create article card
    function createArticleCard(item) {
        const card = document.createElement('article');
        card.className = 'article-card';
        card.innerHTML = `
            <div class="article-content">
                <span class="article-category">Live</span>
                <h3 class="article-title">
                    <a href="${item.link}">${item.title}</a>
                </h3>
                <p class="article-excerpt">${item.excerpt}</p>
                <div class="article-meta">
                    <span class="article-date">
                        <i class="fas fa-clock"></i>
                        ${item.date}
                    </span>
                    <a href="${item.link}" class="read-more">Read More</a>
                </div>
            </div>
        `;
        return card;
    }

    // Create match table row
    function createMatchRow(match) {
        const row = document.createElement('tr');
        const status = match.status === 'LIVE' ? 'live' : 
                      match.status === 'FINISHED' ? 'finished' : 'scheduled';
        const date = new Date(match.utcDate).toLocaleString();
        
        let score = '-';
        if (match.status !== 'SCHEDULED' && match.score && match.score.fullTime) {
            score = `${match.score.fullTime.home !== null ? match.score.fullTime.home : '-'} - ${match.score.fullTime.away !== null ? match.score.fullTime.away : '-'}`;
        }

        row.innerHTML = `
            <td>${date}</td>
            <td class="match-team">${match.homeTeam.name}</td>
            <td class="match-score">${score}</td>
            <td class="match-team">${match.awayTeam.name}</td>
            <td><span class="match-status ${status}">${match.status}</span></td>
            <td>${match.competition ? match.competition.name : 'N/A'}</td>
        `;
        return row;
    }

    // Initialize on document ready
    document.addEventListener('DOMContentLoaded', function() {
        initLiveUpdates();
        initSportsMatches();
    });

})();