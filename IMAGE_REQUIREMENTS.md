# TorlyAI Image & Logo Requirements

## Overview

This document outlines all images and logos needed to complete the TorlyAI website design.

---

## 1. Endorsing Body Logos

**Location**: Front page, Social Proof section
**Current State**: Text placeholders
**Required**: 5 logos

### Specifications:
- **Format**: SVG (preferred) or PNG with transparent background
- **Dimensions**: 200px width × 80px height (approximately)
- **Color**: Grayscale or original brand colors
- **Quality**: High resolution (2x for PNG: 400px × 160px)

### Logos Needed:

1. **UKES** (Unlocked)
   - UK Endorsing for Knowledge, Excellence & Skills
   - Website: https://www.ukes.co.uk

2. **Innovator International**
   - Website: https://www.innovatorinternational.com

3. **TechNation**
   - Website: https://technation.io

4. **The Global Entrepreneurs Programme**
   - Website: https://www.entrepreneur.uk.com

5. **Envestors**
   - Website: https://envestors.co.uk

**Implementation**:
```php
// Replace in front-page.php social proof section
<div class="logo-item">
    <img src="<?php echo get_template_directory_uri(); ?>/assets/logos/ukes-logo.svg" alt="UKES" />
</div>
```

---

## 2. Testimonial Photos

**Location**: Front page, Testimonials section
**Current State**: Gradient placeholder circles
**Required**: 3 photos

### Specifications:
- **Format**: JPG or WebP
- **Dimensions**: 300px × 300px (square)
- **Quality**: High resolution (2x: 600px × 600px)
- **Style**: Professional headshots with neutral backgrounds
- **File size**: < 100KB each (optimized)

### Photos Needed:

1. **Sarah Chen**
   - Founder, TechStart UK
   - Description: "Professional Asian woman, late 20s-early 30s, business attire"
   - Alternative: Use stock photo from Unsplash (search: "professional asian woman headshot")

2. **David Okonkwo**
   - CEO, AfriTech Solutions
   - Description: "Professional Black man, mid 30s, confident smile, business attire"
   - Alternative: Unsplash search: "professional black man headshot"

3. **Priya Patel**
   - Director, FinanceFlow Ltd
   - Description: "Professional Indian woman, early 30s, warm expression, business attire"
   - Alternative: Unsplash search: "professional indian woman headshot"

**Implementation**:
```php
// Update in front-page.php testimonials section
<div class="testimonial-avatar">
    <img src="<?php echo get_template_directory_uri(); ?>/assets/testimonials/sarah-chen.jpg" alt="Sarah Chen" />
</div>
```

**Asset Directory Structure:**
```
theme/torly-theme/assets/
├── logos/
│   ├── ukes-logo.svg
│   ├── innovator-international-logo.svg
│   ├── technation-logo.svg
│   ├── global-entrepreneurs-logo.svg
│   └── envestors-logo.svg
└── testimonials/
    ├── sarah-chen.jpg (or .webp)
    ├── david-okonkwo.jpg
    └── priya-patel.jpg
```

---

## 3. Blog Post Featured Images

**Status**: ✅ Already implemented with custom SVG covers
**Location**: `theme/torly-theme/assets/blog-covers/`

Current blog posts use AI-generated SVG covers:
- `innovator-visa-guide.svg`
- `business-plan-tips.svg`
- `endorsing-bodies.svg`
- `visa-comparison.svg`
- `success-story.svg`

---

## 4. Favicon & App Icons

**Status**: ✅ Already implemented
**Current**: `theme/torly-theme/assets/torlyai-logo.png` (285KB)

**Optimization Recommended**:
- Create smaller favicon sizes (16×16, 32×32, 48×48)
- Generate Apple Touch Icon (180×180)
- Create Android icons (192×192, 512×512)

**Tools**:
- https://realfavicongenerator.net
- Upload torlyai-logo.png and generate all sizes

---

## 5. Open Graph & Social Sharing Images

**Recommended**: Create custom OG images for key pages

### Specifications:
- **Format**: JPG or PNG
- **Dimensions**: 1200px × 630px
- **File size**: < 300KB
- **Content**: Branded graphic with page title overlay

### Pages Needing OG Images:
1. Homepage: "UK Innovator Visa AI Assistant"
2. Blog: "TorlyAI Visa Guidance Blog"
3. About: "About TorlyAI"
4. Contact: "Get Expert Visa Guidance"

**Implementation** (already in header.php for blog posts):
```php
<meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/assets/og-images/homepage.jpg" />
```

---

## 6. Granola.ai Brand Compliance

### Color Palette (Must Match):
- Yellow: `hsl(60, 100%, 50%)` (#ffff00)
- Green: `hsl(108, 100%, 50%)` (#66ff00)
- Orange: `hsl(30, 100%, 50%)` (#ff9900)
- Chat Green: `#10b981`

### Gradient Formulas:
```css
/* Hero section gradient (use for OG images) */
background-image:
    radial-gradient(at 53% 78%, hsla(60,100%,50%,0.3) 0px, transparent 50%),
    radial-gradient(at 71% 91%, hsla(108,100%,50%,0.3) 0px, transparent 50%),
    radial-gradient(at 31% 91%, hsla(30,100%,50%,0.17) 0px, transparent 50%);
```

---

## Priority Tasks

### Immediate (High Priority):
1. **Endorsing Body Logos** - Critical for credibility
   - Can use grayscale versions initially
   - Must be official logos (obtain permission or use from press kits)

2. **Testimonial Photos** - Important for trust
   - Use Unsplash stock photos as placeholders
   - Replace with real client photos when available

### Future (Medium Priority):
3. **Custom OG Images** - Improves social sharing
4. **Optimized Favicons** - Better mobile experience

---

## Image Optimization Tools

**Before uploading to WordPress:**
1. **TinyPNG**: https://tinypng.com (PNG/JPG compression)
2. **SVGOMG**: https://jakearchibald.github.io/svgomg (SVG optimization)
3. **ImageOptim**: https://imageoptim.com (Mac batch optimization)
4. **Squoosh**: https://squoosh.app (WebP conversion)

**Target file sizes:**
- Logos (SVG): < 20KB
- Logos (PNG): < 50KB
- Testimonials: < 100KB each
- OG images: < 300KB

---

## Free Stock Photo Resources

**Professional Headshots:**
- **Unsplash**: https://unsplash.com (CC0 license)
- **Pexels**: https://pexels.com (free for commercial use)
- **Generated Photos**: https://generated.photos (AI-generated faces)

**Logo Sources:**
- Company press kits (check each company website)
- Contact endorsing bodies directly for logo files
- Ensure proper usage rights

---

## Implementation Checklist

- [ ] Obtain/download 5 endorsing body logos
- [ ] Optimize logos to required dimensions
- [ ] Upload to `/assets/logos/` directory
- [ ] Update front-page.php social proof section
- [ ] Select 3 professional headshot photos
- [ ] Optimize to 300×300px, < 100KB
- [ ] Upload to `/assets/testimonials/` directory
- [ ] Update front-page.php testimonials section
- [ ] Deploy updated theme files to production
- [ ] Clear WordPress cache
- [ ] Verify images display correctly on all devices

---

## Contact for Assets

If you need help obtaining official logos or have access to real client photos:
- **Email**: jasonxu05@gmail.com
- **Documentation**: See TORLYAI_DESIGN_SYSTEM.md for design guidelines

---

**Last Updated**: 2025-11-18
**Status**: Awaiting image assets for implementation
