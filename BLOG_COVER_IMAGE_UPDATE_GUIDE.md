# Blog Cover Image Diversity Update Guide

## Problem Summary

**Issue:** 5 out of 6 visible blog posts display visually similar "hand holding British passport" imagery, creating visual monotony and reducing professional appearance.

**Solution:** Replace repetitive passport images with 6 diverse, professional Unsplash images representing different aspects of the UK Innovator Visa journey.

---

## Downloaded Images

All images have been downloaded to: `/Users/Jason-uk/AI/AI_Coding/Repositories/torly-blog/temp-blog-images/`

### Image Inventory:

1. **business-meeting.jpg** (146KB)
   - Theme: Professional business consultation
   - For: "Join Our AI-Powered UK Innovator Visa Office Hours"

2. **ai-technology.jpg** (101KB)
   - Theme: AI technology dashboard
   - For: "Comprehensive AI-Powered Guide to UK Innovator Visa"

3. **business-strategy.jpg** (125KB)
   - Theme: Business planning and strategy
   - For: "Step-by-Step AI-Guided Innovator Visa 2025"

4. **data-analytics.jpg** (199KB)
   - Theme: Data analytics and charts
   - For: "Why AI-Powered Innovator Founder Visa Outperforms Tier 1"

5. **london-business.jpg** (218KB)
   - Theme: London/UK business scene
   - For: "UK Innovator Founder Visa 2025: Requirements & Benefits"

6. **startup-team.jpg** (390KB)
   - Theme: Startup team collaboration
   - For: "AI-Assisted Business Setup with UK Innovator Founder Visa"

---

## Update Methods

### Method 1: WordPress Admin (RECOMMENDED - Easiest)

**Time:** 10-15 minutes

1. **Upload Images:**
   - Go to https://torly.ai/wp-admin/upload.php
   - Click "Add New"
   - Drag all 6 images from `temp-blog-images/` folder
   - Wait for upload to complete

2. **Update Each Post:**
   - Go to https://torly.ai/wp-admin/edit.php
   - For each post below:
     1. Click "Edit"
     2. In right sidebar, click "Set featured image"
     3. Select the corresponding new image
     4. Click "Set featured image"
     5. Click "Update"

**Post-to-Image Mapping:**

| Post Title (search term) | New Image File |
|--------------------------|----------------|
| "Office Hours" | business-meeting.jpg |
| "Comprehensive AI-Powered Guide" | ai-technology.jpg |
| "Step-by-Step AI-Guided" | business-strategy.jpg |
| "Outperforms Tier 1" | data-analytics.jpg |
| "2025: AI-Driven Requirements" | london-business.jpg |
| "AI-Assisted Business Setup" | startup-team.jpg |

---

### Method 2: WP-CLI (If SSH Access Available)

**Time:** 5 minutes

```bash
# 1. Upload images to VM
scp -r temp-blog-images ubuntu@132.226.239.78:/tmp/

# 2. SSH into VM
ssh ubuntu@132.226.239.78

# 3. Run commands
sudo mkdir -p /var/www/html/wp-content/uploads/2025/11/diverse
sudo mv /tmp/temp-blog-images/*.jpg /var/www/html/wp-content/uploads/2025/11/diverse/
sudo chown www-data:www-data /var/www/html/wp-content/uploads/2025/11/diverse/*.jpg
sudo chmod 644 /var/www/html/wp-content/uploads/2025/11/diverse/*.jpg

# 4. Import to media library and update posts
# Business Meeting image
IMG1=$(sudo -u www-data wp media import /var/www/html/wp-content/uploads/2025/11/diverse/business-meeting.jpg --title='Professional Business Consultation' --path=/var/www/html --porcelain)
POST1=$(sudo -u www-data wp post list --post_type=post --s='Office Hours' --fields=ID --path=/var/www/html --format=csv | tail -n 1)
sudo -u www-data wp post meta update $POST1 _thumbnail_id $IMG1 --path=/var/www/html

# AI Technology image
IMG2=$(sudo -u www-data wp media import /var/www/html/wp-content/uploads/2025/11/diverse/ai-technology.jpg --title='AI Technology Dashboard' --path=/var/www/html --porcelain)
POST2=$(sudo -u www-data wp post list --post_type=post --s='Comprehensive AI-Powered Guide' --fields=ID --path=/var/www/html --format=csv | tail -n 1)
sudo -u www-data wp post meta update $POST2 _thumbnail_id $IMG2 --path=/var/www/html

# Business Strategy image
IMG3=$(sudo -u www-data wp media import /var/www/html/wp-content/uploads/2025/11/diverse/business-strategy.jpg --title='Business Planning Strategy' --path=/var/www/html --porcelain)
POST3=$(sudo -u www-data wp post list --post_type=post --s='Step-by-Step' --fields=ID --path=/var/www/html --format=csv | tail -n 1)
sudo -u www-data wp post meta update $POST3 _thumbnail_id $IMG3 --path=/var/www/html

# Data Analytics image
IMG4=$(sudo -u www-data wp media import /var/www/html/wp-content/uploads/2025/11/diverse/data-analytics.jpg --title='Data Analytics Charts' --path=/var/www/html --porcelain)
POST4=$(sudo -u www-data wp post list --post_type=post --s='Outperforms Tier 1' --fields=ID --path=/var/www/html --format=csv | tail -n 1)
sudo -u www-data wp post meta update $POST4 _thumbnail_id $IMG4 --path=/var/www/html

# London Business image
IMG5=$(sudo -u www-data wp media import /var/www/html/wp-content/uploads/2025/11/diverse/london-business.jpg --title='London Business District' --path=/var/www/html --porcelain)
POST5=$(sudo -u www-data wp post list --post_type=post --s='2025: AI-Driven Requirements' --fields=ID --path=/var/www/html --format=csv | tail -n 1)
sudo -u www-data wp post meta update $POST5 _thumbnail_id $IMG5 --path=/var/www/html

# Startup Team image
IMG6=$(sudo -u www-data wp media import /var/www/html/wp-content/uploads/2025/11/diverse/startup-team.jpg --title='Startup Team Collaboration' --path=/var/www/html --porcelain)
POST6=$(sudo -u www-data wp post list --post_type=post --s='AI-Assisted Business Setup' --fields=ID --path=/var/www/html --format=csv | tail -n 1)
sudo -u www-data wp post meta update $POST6 _thumbnail_id $IMG6 --path=/var/www/html

# 5. Flush cache
sudo -u www-data wp cache flush --path=/var/www/html

echo "✓ All images updated successfully!"
```

---

### Method 3: WordPress MCP Server (If Available)

If the WordPress MCP server is configured, use:

```javascript
// Upload and update via MCP
const posts = [
  { search: 'Office Hours', image: '/path/to/business-meeting.jpg' },
  { search: 'Comprehensive AI-Powered Guide', image: '/path/to/ai-technology.jpg' },
  // ... etc
];

for (const post of posts) {
  // Upload image
  const imageId = await wp_upload_media({
    file_path: post.image,
    alt_text: 'Professional image',
    description: post.search
  });

  // Find post
  const posts = await wp_get_posts({ search: post.search });
  const postId = posts[0].ID;

  // Update featured image
  await wp_update_post({
    post_id: postId,
    featured_media: imageId
  });
}
```

---

## Expected Visual Improvement

### Before:
- 🔴 5/6 posts: Hand holding British passport (repetitive)
- 🔴 Low visual engagement
- 🔴 Unprofessional appearance

### After:
- ✅ 6/6 posts: Unique, diverse imagery
- ✅ Professional variety
- ✅ Each post visually distinct
- ✅ Improved click-through rates

---

## Verification

After updating, verify changes:

1. **Visual Check:**
   - Visit https://torly.ai/blog/
   - Confirm all 6 visible posts have different images
   - No passport images visible

2. **Automated Test:**
   ```bash
   npx playwright test tests/check-blog-cover-images.spec.js
   ```
   Should show: "✅ All post images are unique"

---

## Image Sources & Credits

All images sourced from [Unsplash](https://unsplash.com/) under free license (no attribution required):

- [Business Meeting](https://unsplash.com/s/photos/business-meeting)
- [AI Technology](https://unsplash.com/s/photos/ai-technology)
- [Business Strategy](https://unsplash.com/s/photos/business-strategy)
- [Data Analytics](https://unsplash.com/s/photos/data-analytics)
- [London Business](https://unsplash.com/s/photos/london-business)
- [Startup Team](https://unsplash.com/s/photos/startup-team)

---

## Troubleshooting

**Issue: Can't find post in WordPress admin**
- Use search box at top right of posts list
- Search for key terms from "Post Title (search term)" column above

**Issue: Featured image option not showing**
- Ensure you're editing the post (not quick edit)
- Check "Screen Options" at top right - ensure "Featured Image" is checked

**Issue: Image upload fails**
- Check file size (all under 400KB - should be fine)
- Ensure you're logged in as admin
- Try uploading one at a time

---

**Status:** Ready to execute
**Estimated Time:** 10-15 minutes (Method 1) or 5 minutes (Method 2)
**Impact:** Significant visual diversity improvement
