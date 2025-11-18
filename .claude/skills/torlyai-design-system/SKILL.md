---
name: torlyai-design-system
description: Apply TorlyAI Design System with Granola.ai-inspired styling (yellow/green/orange gradients, glass-morphism, system fonts). Use when creating pages, components, forms, layouts, or any UI/UX work for torly.ai. Ensures consistent colors (HSL values), typography scale (clamp), spacing (0.25rem units), and responsive design.
allowed-tools: Read, Write, Edit, Glob, Grep
version: 1.0.0
tags: [design, ui, ux, wordpress, css, frontend]
---

# TorlyAI Design System Skill

## Overview

This skill ensures all UI/UX work for torly.ai follows the **TorlyAI Design System** (v2.0.0), which is inspired by Granola.ai's modern, vibrant aesthetic. The design system emphasizes:

- **Vibrant gradients** using yellow/green/orange HSL colors
- **Glass-morphism** effects for primary CTAs
- **System fonts** for optimal performance
- **Fluid typography** using clamp() for responsive scaling
- **Consistent spacing** with 0.25rem base unit
- **Accessibility** meeting WCAG 2.1 AA standards

**Reference Document:** `/TORLYAI_DESIGN_SYSTEM.md` (complete 2000+ line specification)

---

## 🚨 MANDATORY RULES - NEVER VIOLATE

### 1. Color Palette - EXACT VALUES ONLY

**ALWAYS use these exact HSL colors:**

```css
/* Core Brand Colors - REQUIRED */
--color-yellow: hsl(60, 100%, 50%);    /* #ffff00 - Pure yellow */
--color-green: hsl(108, 100%, 50%);    /* #66ff00 - Lime green */
--color-orange: hsl(30, 100%, 50%);    /* #ff9900 - Orange */
--color-chat-green: #10b981;           /* Emerald green for CTAs */

/* Neutrals */
--white: #ffffff;
--black: #000000;
--border-color: #e5e7eb;

/* Text */
--text-primary: #000000;
--text-secondary: rgba(0, 0, 0, 0.7);
--text-tertiary: rgba(0, 0, 0, 0.5);
```

**❌ NEVER USE:**
- Blue colors (old theme is deprecated)
- Purple colors
- Custom colors outside this palette
- Yellow/green/orange for body text

### 2. Typography - NEVER USE CUSTOM FONT SIZES

**ALWAYS use these clamp() formulas:**

```css
/* Hero Title (H1) */
font-size: clamp(2.25rem, 5vw, 4.5rem);
font-weight: 800;
line-height: 0.9;
letter-spacing: -0.020em;

/* Section Title (H2) */
font-size: clamp(1.875rem, 4vw, 3rem);
font-weight: 700;
line-height: 1.1;
letter-spacing: -0.015em;

/* Card Title (H3) */
font-size: clamp(1.25rem, 2.5vw, 1.5rem);
font-weight: 600;
line-height: 1.3;

/* Body Text */
font-size: clamp(1rem, 1.5vw, 1.125rem);
font-weight: 400;
line-height: 1.6;

/* Small Text */
font-size: 0.875rem;
font-weight: 400;
line-height: 1.5;
```

**Font Family (System Fonts):**
```css
font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
             "Helvetica Neue", Arial, sans-serif;
```

### 3. Spacing - MULTIPLES OF 0.25rem ONLY

```css
--space-1: 0.25rem;   /* 4px */
--space-2: 0.5rem;    /* 8px */
--space-3: 0.75rem;   /* 12px */
--space-4: 1rem;      /* 16px */
--space-6: 1.5rem;    /* 24px */
--space-8: 2rem;      /* 32px */
--space-12: 3rem;     /* 48px */
--space-16: 4rem;     /* 64px */
--space-24: 6rem;     /* 96px */
```

**Never use arbitrary values like:** `padding: 17px` or `margin: 23px`

---

## Critical Component Patterns

### 1. Glass-Morphism Primary Button

**ALWAYS use this exact pattern for primary CTAs:**

```css
.btn-primary {
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.8);
    border-radius: 9999px;          /* Fully rounded */
    padding: 0.75rem 2rem;
    font-size: 1rem;
    font-weight: 600;
    color: var(--black);
    cursor: pointer;
    transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateZ(0);       /* GPU acceleration */
}

.btn-primary:hover {
    background: rgba(255, 255, 255, 0.8);
    transform: scale(1.02) translateZ(0);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}
```

### 2. Feature Card with Hover Effect

```css
.feature-card {
    background: var(--white);
    border: 1px solid var(--border-color);
    border-radius: 1rem;          /* 16px */
    padding: 2rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.feature-card:hover {
    transform: translateY(-5px) scale(1.01);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border-color: rgba(0, 0, 0, 0.1);
}
```

### 3. Feature Icon (Gradient Background)

```css
.feature-icon {
    width: 64px;
    height: 64px;
    border-radius: 1rem;
    background: linear-gradient(135deg,
        hsla(60,100%,50%,0.2) 0%,
        hsla(108,100%,50%,0.2) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
}
```

### 4. Form Inputs

```css
.form-input,
.form-textarea {
    width: 100%;
    padding: 1rem 1.25rem;
    font-size: 1rem;
    border: 2px solid var(--border-color);
    border-radius: 0.75rem;       /* 12px */
    background: var(--white);
    color: var(--black);
    transition: all 0.2s;
    font-family: inherit;
}

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--color-chat-green);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}
```

---

## Gradient Formulas

### Hero Section Background

**ALWAYS use this exact gradient pattern for hero sections:**

```css
.hero-section {
    background: var(--white);
    background-image:
        radial-gradient(at 53% 78%, hsla(60,100%,50%,0.3) 0px, transparent 50%),
        radial-gradient(at 71% 91%, hsla(108,100%,50%,0.3) 0px, transparent 50%),
        radial-gradient(at 31% 91%, hsla(30,100%,50%,0.17) 0px, transparent 50%);
}
```

### CTA Section (Dark Background with Gradients)

```css
.cta-section {
    background: var(--black);
    background-image:
        radial-gradient(at 30% 50%, hsla(60,100%,50%,0.2) 0px, transparent 50%),
        radial-gradient(at 70% 50%, hsla(108,100%,50%,0.2) 0px, transparent 50%);
    color: var(--white);
}
```

### Gradient Text (Stats Numbers)

```css
.stat-number {
    background: linear-gradient(135deg,
        var(--color-yellow),
        var(--color-green));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
```

---

## Responsive Design

### Breakpoints (Mobile-First)

```css
/* Small devices (landscape phones, 640px and up) */
@media (min-width: 640px) { }

/* Medium devices (tablets, 768px and up) */
@media (min-width: 768px) { }

/* Large devices (desktops, 1024px and up) */
@media (min-width: 1024px) { }

/* Extra large devices (large desktops, 1280px and up) */
@media (min-width: 1280px) { }
```

### Grid Patterns

```css
.features-grid {
    display: grid;
    grid-template-columns: 1fr;           /* Mobile: 1 column */
    gap: 2rem;
}

@media (min-width: 768px) {
    .features-grid {
        grid-template-columns: repeat(2, 1fr);  /* Tablet: 2 columns */
    }
}

@media (min-width: 1024px) {
    .features-grid {
        grid-template-columns: repeat(3, 1fr);  /* Desktop: 3 columns */
    }
}
```

---

## Animations

### Scroll Fade-In (Use Intersection Observer)

**CSS:**
```css
.fade-in-element {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1),
                transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.fade-in-element.visible {
    opacity: 1;
    transform: translateY(0);
}
```

**JavaScript:**
```javascript
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.fade-in-element').forEach(el => {
    observer.observe(el);
});
```

---

## Instructions for Claude

When creating UI/UX work for torly.ai, follow this workflow:

### Step 1: Identify the Component Type

Determine what you're building:
- Hero section → Use hero gradient background
- Feature cards → Use card pattern with hover effect
- Forms → Use form input styles with focus states
- CTA section → Use dark background with gradients
- Buttons → Use glass-morphism primary button

### Step 2: Apply Color Palette

- **Gradients:** Use yellow/green/orange HSL values only
- **Text:** Black on white backgrounds
- **CTAs:** Chat green (#10b981) or glass-morphism
- **Borders:** #e5e7eb

### Step 3: Use Typography Scale

- **H1:** `clamp(2.25rem, 5vw, 4.5rem)` with weight 800
- **H2:** `clamp(1.875rem, 4vw, 3rem)` with weight 700
- **H3:** `clamp(1.25rem, 2.5vw, 1.5rem)` with weight 600
- **Body:** `clamp(1rem, 1.5vw, 1.125rem)` with weight 400

### Step 4: Apply Spacing System

Use multiples of 0.25rem:
- Card padding: `2rem` (32px)
- Section padding: `4rem 0` (64px vertical)
- Element gaps: `1.5rem` or `2rem`
- Button padding: `0.75rem 2rem`

### Step 5: Add Responsive Behavior

- Use clamp() for fluid typography (already included)
- Use CSS Grid with media queries for layouts
- Mobile-first approach

### Step 6: Include Animations

- Add `.fade-in-element` class to cards/sections
- Include Intersection Observer JavaScript
- Use hover effects on interactive elements

### Step 7: Ensure Accessibility

- All text meets WCAG 2.1 AA contrast ratios
- Add focus states (2px solid outline)
- Include aria-labels where needed
- Ensure keyboard navigation works

---

## Complete Page Template

```html
<style>
    /* CSS Variables */
    :root {
        --white: #ffffff;
        --black: #000000;
        --color-yellow: hsl(60, 100%, 50%);
        --color-green: hsl(108, 100%, 50%);
        --color-orange: hsl(30, 100%, 50%);
        --color-chat-green: #10b981;
        --border-color: #e5e7eb;
        --text-primary: #000000;
        --text-secondary: rgba(0, 0, 0, 0.7);
    }

    /* Container */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    /* Hero Section */
    .hero-section {
        padding: 7rem 0 4rem;
        background: var(--white);
        background-image:
            radial-gradient(at 53% 78%, hsla(60,100%,50%,0.3) 0px, transparent 50%),
            radial-gradient(at 71% 91%, hsla(108,100%,50%,0.3) 0px, transparent 50%),
            radial-gradient(at 31% 91%, hsla(30,100%,50%,0.17) 0px, transparent 50%);
    }

    .hero-title {
        font-size: clamp(2.25rem, 5vw, 4.5rem);
        font-weight: 800;
        line-height: 0.9;
        letter-spacing: -0.020em;
        color: var(--black);
        margin-bottom: 1.5rem;
    }

    /* Primary Button */
    .btn-primary {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 9999px;
        padding: 0.75rem 2rem;
        font-size: 1rem;
        font-weight: 600;
        color: var(--black);
        cursor: pointer;
        transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-primary:hover {
        background: rgba(255, 255, 255, 0.8);
        transform: scale(1.02);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    /* Feature Card */
    .feature-card {
        background: var(--white);
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        padding: 2rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .feature-card:hover {
        transform: translateY(-5px) scale(1.01);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1 class="hero-title">Your Headline Here</h1>
        <p class="hero-subtitle">Supporting subtitle</p>
        <a href="#cta" class="btn-primary">Get Started</a>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="features-grid">
            <div class="feature-card">
                <h3>Feature Title</h3>
                <p>Feature description...</p>
            </div>
            <!-- More cards -->
        </div>
    </div>
</section>

<script>
    // Scroll animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.fade-in-element').forEach(el => {
        observer.observe(el);
    });
</script>
```

---

## ✅ Do's

- Use radial gradients sparingly (hero, CTA sections only)
- Use glass-morphism for primary CTAs
- Apply hover effects to all interactive elements
- Use the spacing system consistently
- Keep animations subtle and purposeful
- Ensure WCAG AA accessibility
- Test on mobile devices
- Use clamp() for all typography

## ❌ Don'ts

- **Never use blue or purple colors** (old theme is deprecated)
- **Never use custom font sizes** (stick to clamp() formulas)
- **Never use yellow/green/orange for body text** (readability)
- **Never create buttons without hover states**
- **Never use arbitrary spacing** (use 0.25rem multiples)
- **Never animate too many elements at once**
- **Never forget focus states** for keyboard users
- **Never use backdrop-filter without fallbacks**

---

## WordPress Integration Notes

### Content Structure for WordPress Pages

WordPress pages require inline styles and scripts:

```html
<!-- All CSS at the top -->
<style>
    /* Design system CSS here */
</style>

<!-- HTML content -->
<section class="hero-section">
    <!-- Content -->
</section>

<!-- All JavaScript at the bottom -->
<script>
    // Animations and interactions
</script>
```

### Handling wpautop Filter

WordPress may filter `<style>` tags. Solutions:

1. **Add styles to theme CSS:** `/wp-content/themes/torly-theme/style.css`
2. **Use !important for specificity:** `border-radius: 0.75rem !important;`
3. **Deploy via SSH:** Preserve exact HTML structure

### Deployment Commands

```bash
# Upload to server
scp page-content.html user@server:/tmp/

# Update WordPress post
ssh user@server "sudo -u www-data wp post update POST_ID \
  --post_content='\$(cat /tmp/page-content.html)' \
  --path=/var/www/html"
```

---

## Examples

### Example 1: Creating a Contact Form Page

**User request:** "Create a contact page with a form"

**Your approach:**
1. Use hero section with gradient background
2. Create form with design system input styles
3. Add glass-morphism submit button
4. Include form validation with focus states
5. Add scroll animations for form appearance
6. Use clamp() for responsive typography
7. Ensure 12px border-radius on inputs

### Example 2: Creating a Services Page

**User request:** "Create a services page with feature cards"

**Your approach:**
1. Hero section: Title + subtitle with gradients
2. Features grid: 3 columns on desktop, 1 on mobile
3. Feature cards: 16px border-radius, hover lift effect
4. Feature icons: Gradient backgrounds (yellow → green)
5. CTA section: Dark background with gradients
6. Glass-morphism "Get Started" button
7. Intersection Observer for scroll animations

### Example 3: Updating Blog Layout

**User request:** "Improve the blog listing design"

**Your approach:**
1. Blog cards: White background, 16px border-radius
2. Hover effect: translateY(-5px) + shadow
3. Typography: clamp() for titles and excerpts
4. Grid layout: 2 columns tablet, 3 desktop
5. Featured images: 200px height, object-fit cover
6. Spacing: 2rem gap between cards
7. No blue colors (use chat green for links)

---

## Related Files

- **Complete design system:** `/TORLYAI_DESIGN_SYSTEM.md` (2086 lines)
- **WordPress theme:** `/theme/torly-theme/`
- **Theme CSS:** `/theme/torly-theme/style.css`
- **Frontend template:** `/theme/torly-theme/front-page.php`
- **Blog template:** `/theme/torly-theme/home.php`
- **Component examples:** `.claude/skills/torlyai-design-system/examples/`

---

## When NOT to Use This Skill

This skill is NOT appropriate for:

- Backend PHP code (theme functions, REST API endpoints)
- Database queries or WordPress admin customization
- Server configuration or deployment scripts
- Non-UI work (documentation, README files)
- Email templates (different styling requirements)

---

## Quick Reference Checklist

Before completing any UI/UX task, verify:

- [ ] Colors: Only yellow/green/orange HSL values used
- [ ] Typography: All text uses clamp() formulas
- [ ] Buttons: Glass-morphism with 9999px border-radius
- [ ] Cards: 16px border-radius with hover effects
- [ ] Forms: 12px border-radius with focus states
- [ ] Spacing: All values are multiples of 0.25rem
- [ ] Gradients: Radial gradients in hero/CTA sections only
- [ ] Responsive: Mobile-first with proper breakpoints
- [ ] Animations: Intersection Observer for scroll effects
- [ ] Accessibility: Focus states and WCAG AA contrast

---

**Version:** 1.0.0
**Last Updated:** 2025-11-18
**Design System Version:** 2.0.0 (Granola.ai-inspired)
