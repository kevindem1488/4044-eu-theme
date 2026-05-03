# 4044.eu - WordPress Theme

Professional WordPress theme for live news, sports results, and AI-generated content from Europe.

## 🚀 Features

### Live News & Updates
- ✅ Real-time news updates with live indicators
- ✅ Automatic RSS feed sync from Google News
- ✅ Custom live update post type
- ✅ Auto-refresh every 30 seconds

### Sports Integration
- ✅ Football-data.org API integration
- ✅ Live match results and standings
- ✅ Multiple league support (PL, La Liga, Serie A, Bundesliga, Ligue 1)
- ✅ Auto-update every minute
- ✅ Match status tracking (SCHEDULED, LIVE, FINISHED)

### AI-Powered Content
- ✅ OpenAI integration for image generation (DALL-E)
- ✅ Automatic image generation for articles
- ✅ AI content expansion and improvement
- ✅ Automatic article title generation

### Admin Panel
- ✅ Centralized control panel
- ✅ Live updates manager
- ✅ Sports configuration
- ✅ AI tools interface
- ✅ RSS feed management
- ✅ Site statistics dashboard

### Design & UX
- ✅ Fully responsive (mobile, tablet, desktop)
- ✅ Dark mode support
- ✅ Accessibility features (WCAG 2.1)
- ✅ Fast loading with lazy loading
- ✅ Modern blue/black/white color scheme
- ✅ Smooth animations and transitions

## 📋 Requirements

- **WordPress:** 6.0 or higher
- **PHP:** 8.0 or higher
- **MySQL:** 5.7 or higher

## 📥 Installation

### Method 1: Upload ZIP (Recommended)

1. Download the theme ZIP from GitHub:
   ```bash
   git clone https://github.com/kevindem1488/4044-eu-theme.git
   cd 4044-eu-theme
   ```

2. Create a ZIP file:
   ```bash
   zip -r 4044-eu-theme.zip . -x ".*" "*.git*"
   ```

3. In WordPress Admin:
   - Go to **Appearance → Themes**
   - Click **Add New**
   - Click **Upload Theme**
   - Select the ZIP file
   - Click **Install Now**
   - Click **Activate**

### Method 2: Via FTP/SFTP

1. Download and extract the theme
2. Upload folder to: `/wp-content/themes/4044-eu-theme/`
3. Go to **Appearance → Themes** and activate

### Method 3: Via WP-CLI

```bash
wp theme install https://github.com/kevindem1488/4044-eu-theme/archive/main.zip --activate
```

## ⚙️ Configuration

### 1. Set Up APIs

#### Football-Data.org API
1. Visit [football-data.org](https://www.football-data.org/)
2. Create a free account
3. Get your API key
4. Go to **4044 Panel → Sports**
5. Paste your API key

#### OpenAI API
1. Visit [platform.openai.com](https://platform.openai.com/)
2. Create an account
3. Generate an API key
4. Go to **Appearance → Customize → API Settings**
5. Paste your OpenAI API key

### 2. Configure Menus

1. Go to **Appearance → Menus**
2. Create "Primary Menu"
3. Add pages/categories:
   - Home
   - News
   - Sports
   - Europe
   - Contact
4. Set as "Primary Menu"

### 3. Create Essential Pages

Create the following pages and add content:

- **Home** (Homepage)
- **Privacy Policy**
- **Terms of Service**
- **Contact Us**
- **About**
- **Category: Sport**

### 4. Configure Homepage

1. Go to **Settings → Reading**
2. Select "A static page" for homepage
3. Choose your Home page
4. Set Posts page to your Blog page

## 📱 Features Overview

### Homepage
- Hero section with tagline
- Live updates section (auto-refresh)
- Football results table (auto-refresh)
- Latest news articles grid
- Pagination

### Single Post
- Featured image
- Article metadata (date, author, category)
- Related posts
- Comments section
- Social sharing

### Sports
- Live match results
- Team names and scores
- Match status indicators
- League/competition name
- Auto-update every minute

### RSS Feeds
- Main news feed: `/feed/`
- Live updates feed: `/feed/live-updates/`
- Sports feed: `/feed/sports/`
- Google News feed: `/feed/google-news/`

## 🎨 Customization

### Colors
Edit `style.css` and modify CSS variables:

```css
:root {
  --primary-color: #0066cc;      /* Blue */
  --dark-color: #000000;          /* Black */
  --light-color: #ffffff;         /* White */
  --accent-color: #004999;
  --text-dark: #1a1a1a;
  --text-light: #666666;
}
```

### Logo & Branding
1. Go to **Appearance → Customize**
2. Click **Site Identity**
3. Upload your logo
4. Set site title and tagline

### Fonts
Add custom fonts via **Appearance → Customize → Additional CSS**

## 🔄 Auto-Refresh Features

### Live Updates
- Auto-refreshes every **30 seconds**
- Fetches from REST API endpoint
- Shows only latest updates

### Sports Matches
- Auto-refreshes every **60 seconds**
- Gets data from football-data.org
- Displays top 10 matches
- Caches for 5 minutes

### Google News Sync
- Runs **hourly** automatically
- Fetches latest Europe news
- Creates posts automatically
- Prevents duplicates

## 📊 Admin Panel

Access at **4044 Panel** (left sidebar)

### Control Panel
- API connection status
- Quick statistics
- Feed URLs

### Live Updates Manager
- Create new live updates
- View all updates
- Publish immediately

### Sports Integration
- Configure football-data.org API
- Manage leagues
- Test API connection

### AI Tools
- Generate images from text
- Expand article content
- Generate headlines

### RSS Settings
- View all feed URLs
- Manage feed settings
- Manual sync trigger

### Statistics
- Total posts count
- Live updates count
- Comments count
- Categories count

## 🔌 REST API Endpoints

### Get Live Updates
```
GET /wp-json/4044/v1/live-updates
```

### Get Sports Matches
```
GET /wp-json/4044/v1/sports/matches
```

### Publish News with AI
```
POST /wp-json/4044/v1/news/publish
Body: {
  "title": "Article Title",
  "content": "Article content",
  "category": 1
}
```

## 🎯 Best Practices

1. **Keep plugins minimal** - Use only essential plugins
2. **Optimize images** - Use WebP format when possible
3. **Enable caching** - Use W3 Total Cache or similar
4. **Monitor API limits** - Track football-data.org and OpenAI usage
5. **Regular backups** - Backup database and uploads
6. **Update WordPress** - Keep WordPress and plugins updated

## 🐛 Troubleshooting

### API Not Working
- Verify API keys in theme settings
- Check API rate limits
- Test API connectivity
- Check server firewall settings

### Live Updates Not Loading
- Verify REST API is enabled
- Check browser console for errors
- Ensure posts exist in database

### Images Not Generating
- Verify OpenAI API key is valid
- Check account has sufficient credits
- Monitor API usage limits

### Mobile Menu Not Working
- Clear browser cache
- Check JavaScript is enabled
- Verify no CSS conflicts

## 📞 Support

For issues or questions:
1. Check GitHub Issues: https://github.com/kevindem1488/4044-eu-theme/issues
2. Review documentation
3. Check WordPress error logs

## 📄 License

GPL v2 or later - See LICENSE file

## 👨‍💻 Author

**kevindem1488**
- GitHub: https://github.com/kevindem1488

## 🙏 Credits

- Football Data API: https://www.football-data.org/
- OpenAI API: https://openai.com/
- Font Awesome Icons: https://fontawesome.com/
- WordPress: https://wordpress.org/

## 📝 Changelog

### v1.0.0 (Initial Release)
- Complete theme with all features
- Live updates system
- Sports integration
- AI content generation
- Custom admin panel
- Responsive design
- RSS feed management

---

**Made with ❤️ for 4044.eu**