# Technical Documentation

## Theme Structure

```
4044-eu-theme/
├── style.css              # Main stylesheet + theme header
├── index.php              # Main template
├── header.php             # Header template
├── footer.php             # Footer template
├── single.php             # Single post template
├── search.php             # Search results template
├── category.php           # Category archive template
├── 404.php                # Error page
├── page.php               # Page template
├── functions.php          # Theme functions
├── theme-setup.php        # Setup/installation
├── theme-updater.php      # Update checker
├── README.md              # User guide
├── SETUP.md               # Setup guide
├── TECHNICAL.md           # This file
├── package.json           # Package info
├── includes/
│   ├── api-handler.php    # REST API endpoints
│   ├── sports-api.php     # Football-data.org integration
│   ├── rss-handler.php    # RSS feed management
│   ├── ai-content.php     # OpenAI integration
│   └── admin-panel.php    # Admin interface
├── templates/
│   ├── rss-google-news.php
│   ├── rss-live-updates.php
│   └── rss-sports.php
└── assets/
    ├── css/
    │   └── responsive.css
    └── js/
        ├── main.js
        └── api-handler.js
```

## REST API Endpoints

### Live Updates
```
GET /wp-json/4044/v1/live-updates

Response:
[
  {
    "id": 1,
    "title": "Breaking News...",
    "excerpt": "...",
    "link": "http://site.com/...",
    "date": "2026-05-03T...",
    "image": "http://site.com/..."
  }
]
```

### Sports Matches
```
GET /wp-json/4044/v1/sports/matches

Response:
{
  "matches": [
    {
      "id": "...",
      "homeTeam": { "id": ..., "name": "Team A" },
      "awayTeam": { "id": ..., "name": "Team B" },
      "score": {
        "fullTime": { "home": 2, "away": 1 }
      },
      "status": "FINISHED",
      "utcDate": "2026-05-03T...",
      "competition": { "id": ..., "name": "Premier League" }
    }
  ]
}
```

### Publish News with AI
```
POST /wp-json/4044/v1/news/publish
Content-Type: application/json
Authorization: Bearer {token}

Body:
{
  "title": "Article Title",
  "content": "Article content...",
  "category": 1
}

Response:
{
  "success": true,
  "post_id": 123,
  "message": "Article published successfully"
}
```

## Hooks & Filters

### Custom Actions
- `sync_google_news_hook` - Runs hourly
- `4044_before_publish_ai` - Before AI publication
- `4044_after_publish_ai` - After AI publication

### Custom Filters
- `4044_live_updates_args` - Modify live updates query
- `4044_sports_matches` - Filter sports matches
- `4044_ai_image_prompt` - Customize image prompt

## Database Tables

The theme uses standard WordPress tables:
- `wp_posts` - Articles, updates, events
- `wp_postmeta` - Post metadata
- `wp_termmeta` - Category metadata
- `wp_options` - Theme settings

## API Integration

### Football-Data.org
- Endpoint: `https://api.football-data.org/v4/`
- Authentication: X-Auth-Token header
- Rate limit: 10 requests per minute (free tier)
- Cache: 5 minutes

### OpenAI
- Endpoint: `https://api.openai.com/v1/`
- Authentication: Bearer token header
- Models: gpt-3.5-turbo, dall-e-3
- Rate limit: Depends on plan

## Performance Optimization

1. **Caching**
   - Transient caching for API responses
   - 5-minute cache for sports matches

2. **Lazy Loading**
   - Images lazy load via Intersection Observer
   - JavaScript optimized

3. **CSS & JS**
   - Minified in production
   - Responsive and mobile-first

## Security

1. **Input Sanitization**
   - `sanitize_text_field()`
   - `sanitize_textarea_field()`
   - `esc_html()`, `esc_url()`

2. **Output Escaping**
   - All user data escaped
   - CSRF protection with nonces

3. **API Security**
   - Keys stored in theme settings
   - Not in code or version control
   - SSL/TLS for API calls

## Development

### Adding Custom Features

1. Create file in `includes/` directory
2. Require it in `functions.php`
3. Use WordPress hooks and filters
4. Follow coding standards

### Custom Post Type
```php
register_post_type('custom_post', array(
    'public' => true,
    'show_in_rest' => true,
    'supports' => array('title', 'editor', 'thumbnail'),
));
```

### Custom REST Endpoint
```php
add_action('rest_api_init', function() {
    register_rest_route('4044/v1', '/custom', array(
        'methods' => 'GET',
        'callback' => 'custom_callback',
        'permission_callback' => '__return_true',
    ));
});

function custom_callback() {
    return rest_ensure_response(array('data' => 'value'));
}
```

## Testing

### Manual Testing
1. Test responsive design on all devices
2. Test API endpoints with Postman
3. Test admin panel functionality
4. Test RSS feeds

### API Testing
```bash
# Test live updates
curl http://site.com/wp-json/4044/v1/live-updates

# Test sports matches
curl http://site.com/wp-json/4044/v1/sports/matches
```

## Deployment

1. Backup existing WordPress
2. Upload theme to `wp-content/themes/`
3. Activate in admin
4. Configure APIs
5. Run initial setup
6. Test all features

## Maintenance

- Monitor API usage
- Check error logs
- Update dependencies
- Backup regularly
- Monitor performance

## Version History

### v1.0.0
- Initial release
- All features included
- Full documentation