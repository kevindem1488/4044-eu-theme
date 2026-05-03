# 4044.eu Theme Configuration Guide

## Quick Start

### 1. Install Theme
```bash
# Extract ZIP to wp-content/themes/4044-eu-theme/
# OR use WP Admin > Appearance > Themes > Add New > Upload Theme
```

### 2. Activate Theme
- Go to **Appearance → Themes**
- Find "4044.eu - Live News Europe"
- Click **Activate**

### 3. Configure APIs

#### Football-Data.org
1. Sign up at https://www.football-data.org/
2. Get your API key
3. Go to **4044 Panel → Sports**
4. Enter API key
5. Click Save

#### OpenAI
1. Sign up at https://platform.openai.com/
2. Generate API key
3. Go to **Appearance → Customize → API Settings**
4. Enter OpenAI API key
5. Click Save

### 4. Create Menu
1. Go to **Appearance → Menus**
2. Create new menu "Primary Menu"
3. Add items:
   - Home
   - News
   - Sports
   - Contact
4. Set as Primary Menu

### 5. Set Homepage
1. Go to **Settings → Reading**
2. Select "A static page" under "Your homepage displays"
3. Set Homepage to your home page
4. Click Save

## Theme Features

### Live Updates
- Post type: `live_update`
- Goes in main feed
- Auto-refreshes every 30 seconds

### Sports Results
- Fetches from football-data.org
- Shows live matches
- Auto-updates every 60 seconds

### AI Content
- Auto-generates images for posts
- Expands article content
- Generates headlines

### RSS Feeds
- Main feed: `site.com/feed/`
- Live updates: `site.com/feed/live-updates/`
- Sports: `site.com/feed/sports/`
- Google News: `site.com/feed/google-news/`

## Admin Panel Features

### 4044 Control Panel
- API status
- Quick stats
- Feed links

### Live Updates
- Create/manage live updates
- Post type: `live_update`

### Sports Integration
- Configure football-data.org
- Manage competitions
- Test API

### AI Tools
- Generate images
- Expand content
- Generate titles

### RSS Settings
- View feeds
- Manual sync
- Feed management

### Statistics
- Posts count
- Comments count
- Categories count

## Custom Widgets

### Sidebar Widgets
1. Go to **Appearance → Widgets**
2. Add widgets to "Primary Sidebar"
3. Customize as needed

## Performance Tips

1. Enable caching
2. Optimize images
3. Use CDN for assets
4. Monitor API usage
5. Regular backups

## Customization

### Colors
Edit `style.css` CSS variables:
```css
:root {
  --primary-color: #0066cc;
  --dark-color: #000000;
  --light-color: #ffffff;
}
```

### Fonts
Add in **Appearance → Customize → Additional CSS**

### Logo
1. **Appearance → Customize → Site Identity**
2. Upload custom logo

## Troubleshooting

### API Issues
- Check API keys
- Verify rate limits
- Check firewall

### Updates Not Loading
- Clear cache
- Check REST API
- Verify permissions

### Images Not Generating
- Check OpenAI key
- Verify credits
- Check file permissions

## Support

- GitHub: https://github.com/kevindem1488/4044-eu-theme
- Issues: https://github.com/kevindem1488/4044-eu-theme/issues