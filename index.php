<?php
/**
 * Main Template - Homepage and Blog
 */
get_header();
?>

<div class="container">
    <!-- Hero Section -->
    <section class="hero-section">
        <h1><?php _e('Latest News From Europe', '4044-eu-theme'); ?></h1>
        <p><?php _e('Breaking news, live updates, and sports results from across Europe', '4044-eu-theme'); ?></p>
    </section>

    <!-- Live Updates Section -->
    <section class="live-updates-section">
        <h2>
            <span class="live-indicator"></span>
            <?php _e('Live Updates', '4044-eu-theme'); ?>
        </h2>
        <div id="live-updates-container" class="articles-grid">
            <div class="loading-spinner">
                <div class="loading"></div>
                <?php _e('Loading live updates...', '4044-eu-theme'); ?>
            </div>
        </div>
    </section>

    <!-- Sports Results Section -->
    <section class="sports-section">
        <h2 class="sports-title">
            <span class="live-indicator"></span>
            <?php _e('Football Results & Standings', '4044-eu-theme'); ?>
        </h2>
        <div id="sports-container" class="sports-table-wrapper">
            <table class="sports-table">
                <thead>
                    <tr>
                        <th><?php _e('Time', '4044-eu-theme'); ?></th>
                        <th><?php _e('Home Team', '4044-eu-theme'); ?></th>
                        <th><?php _e('Score', '4044-eu-theme'); ?></th>
                        <th><?php _e('Away Team', '4044-eu-theme'); ?></th>
                        <th><?php _e('Status', '4044-eu-theme'); ?></th>
                        <th><?php _e('Competition', '4044-eu-theme'); ?></th>
                    </tr>
                </thead>
                <tbody id="sports-tbody">
                    <tr>
                        <td colspan="6" style="text-align: center;">
                            <div class="loading"></div>
                            <?php _e('Loading matches...', '4044-eu-theme'); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- News Articles Section -->
    <section class="articles-section">
        <h2><?php _e('All News', '4044-eu-theme'); ?></h2>
        
        <div class="articles-grid">
            <?php
            if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    ?>
                    <article class="article-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="article-image">
                                <?php the_post_thumbnail('article-featured'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="article-content">
                            <?php
                            $categories = get_the_category();
                            if (!empty($categories)) {
                                foreach ($categories as $cat) {
                                    echo '<span class="article-category">' . esc_html($cat->name) . '</span>';
                                }
                            }
                            ?>
                            <h3 class="article-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            <p class="article-excerpt">
                                <?php
                                if (has_excerpt()) {
                                    the_excerpt();
                                } else {
                                    echo wp_trim_words(get_the_content(), 20, '...');
                                }
                                ?>
                            </p>
                            <div class="article-meta">
                                <span class="article-date">
                                    <i class="fas fa-clock"></i>
                                    <?php echo human_time_diff(get_the_time('U'), current_time('U')); ?>
                                    <?php _e('ago', '4044-eu-theme'); ?>
                                </span>
                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    <?php _e('Read More', '4044-eu-theme'); ?>
                                </a>
                            </div>
                        </div>
                    </article>
                    <?php
                }

                // Pagination
                echo '<div class="pagination">';
                echo paginate_links(array(
                    'prev_text' => __('← Previous', '4044-eu-theme'),
                    'next_text' => __('Next →', '4044-eu-theme'),
                ));
                echo '</div>';
            } else {
                echo '<p>' . __('No articles found.', '4044-eu-theme') . '</p>';
            }
            ?>
        </div>
    </section>
</div>

<script>
// Load live updates
document.addEventListener('DOMContentLoaded', function() {
    loadLiveUpdates();
    loadSportsMatches();
    
    // Refresh live updates every 30 seconds
    setInterval(loadLiveUpdates, 30000);
    setInterval(loadSportsMatches, 60000);
});

function loadLiveUpdates() {
    const container = document.getElementById('live-updates-container');
    
    fetch('<?php echo esc_url(rest_url('4044/v1/live-updates')); ?>')
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data)) {
                container.innerHTML = '';
                data.forEach(item => {
                    const article = document.createElement('article');
                    article.className = 'article-card';
                    article.innerHTML = `
                        <div class="article-content">
                            <span class="article-category"><?php _e('Live', '4044-eu-theme'); ?></span>
                            <h3 class="article-title">
                                <a href="${item.link}">${item.title}</a>
                            </h3>
                            <p class="article-excerpt">${item.excerpt}</p>
                            <div class="article-meta">
                                <span class="article-date">
                                    <i class="fas fa-circle-notch live-indicator"></i>
                                    <?php _e('Just now', '4044-eu-theme'); ?>
                                </span>
                                <a href="${item.link}" class="read-more"><?php _e('Read More', '4044-eu-theme'); ?></a>
                            </div>
                        </div>
                    `;
                    container.appendChild(article);
                });
            }
        })
        .catch(error => console.error('Error loading updates:', error));
}

function loadSportsMatches() {
    const tbody = document.getElementById('sports-tbody');
    
    fetch('<?php echo esc_url(rest_url('4044/v1/sports/matches')); ?>')
        .then(response => response.json())
        .then(data => {
            if (data.matches) {
                tbody.innerHTML = '';
                data.matches.slice(0, 10).forEach(match => {
                    const status = match.status === 'LIVE' ? 'live' : 
                                 match.status === 'FINISHED' ? 'finished' : 'scheduled';
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${new Date(match.utcDate).toLocaleString()}</td>
                        <td class="match-team">${match.homeTeam.name}</td>
                        <td class="match-score">${match.score.fullTime.home !== null ? match.score.fullTime.home : '-'}</td>
                        <td class="match-team">${match.awayTeam.name}</td>
                        <td>
                            <span class="match-status ${status}">
                                ${match.status}
                            </span>
                        </td>
                        <td>${match.competition.name}</td>
                    `;
                    tbody.appendChild(row);
                });
            }
        })
        .catch(error => console.error('Error loading matches:', error));
}
</script>

<?php
get_footer();
