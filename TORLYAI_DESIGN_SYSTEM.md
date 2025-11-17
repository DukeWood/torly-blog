# TorlyAI Design System
## Granola.ai-Inspired UI/UX Style Guide

**Version:** 2.0.0
**Last Updated:** 2025-11-17
**Design Philosophy:** Modern, vibrant, AI-focused with subtle gradients and glass-morphism

---

## Table of Contents
1. [Color Palette](#color-palette)
2. [Typography](#typography)
3. [Spacing System](#spacing-system)
4. [Components](#components)
5. [Layout Patterns](#layout-patterns)
6. [Animations](#animations)
7. [Forms](#forms)
8. [Responsive Design](#responsive-design)
9. [Accessibility](#accessibility)

---

## 1. Color Palette

### Primary Colors

```css
/* Neutrals */
--white: #ffffff;
--black: #000000;
--neutral-50: #f9fafb;
--neutral-100: #f3f4f6;
--neutral-200: #e5e7eb;
```

### Accent Colors (HSL for Gradients)

```css
/* Core Brand Colors */
--color-yellow: hsl(60, 100%, 50%);    /* #ffff00 - Pure yellow */
--color-green: hsl(108, 100%, 50%);    /* #66ff00 - Lime green */
--color-orange: hsl(30, 100%, 50%);    /* #ff9900 - Orange */
--color-chat-green: #10b981;           /* Emerald green for CTAs */
```

### Text Colors

```css
--text-primary: #000000;               /* Main headings, body text */
--text-secondary: rgba(0, 0, 0, 0.7);  /* Subheadings, descriptions */
--text-tertiary: rgba(0, 0, 0, 0.5);   /* Captions, meta info */
```

### Background Colors

```css
--bg-primary: #ffffff;                 /* Main background */
--bg-secondary: #f9fafb;               /* Alternate sections */
--border-color: #e5e7eb;               /* Borders, dividers */
```

### Usage Guidelines

| Color | Use For | Don't Use For |
|-------|---------|---------------|
| **Yellow** | Gradients, accents, highlights | Text, backgrounds |
| **Green** | Gradients, success states | Primary buttons |
| **Orange** | Gradients, warm accents | Error states |
| **Black** | Headings, body text | Backgrounds |
| **White** | Backgrounds, buttons | Body text on white |
| **Chat Green** | Primary CTAs, links | Gradients |

---

## 2. Typography

### Font Family

```css
font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
             "Helvetica Neue", Arial, sans-serif;
```

**Why:** System fonts for optimal performance and native feel across all platforms.

### Type Scale

#### Hero Titles (H1)

```css
.hero-title {
    font-size: 2.25rem;      /* 36px - Mobile */
    font-size: 3rem;         /* 48px - Tablet */
    font-size: 4.5rem;       /* 72px - Desktop */
    font-weight: 800;        /* Extra bold */
    line-height: 0.9;        /* Tight for impact */
    letter-spacing: -0.020em; /* Tighter tracking */
    color: var(--black);
}
```

**Use for:** Main hero headlines only

#### Section Titles (H2)

```css
.section-title {
    font-size: 1.875rem;     /* 30px - Mobile */
    font-size: 3rem;         /* 48px - Desktop */
    font-weight: 700;
    line-height: 1.1;
    letter-spacing: -0.015em;
    color: var(--black);
}
```

**Use for:** Section headings, major divisions

#### Card Titles (H3)

```css
.card-title {
    font-size: 1.25rem;      /* 20px - Mobile */
    font-size: 1.5rem;       /* 24px - Desktop */
    font-weight: 600;
    line-height: 1.3;
    color: var(--black);
}
```

**Use for:** Card headings, feature titles

#### Body Text

```css
body {
    font-size: 1rem;         /* 16px - Mobile */
    font-size: 1.125rem;     /* 18px - Desktop */
    font-weight: 400;
    line-height: 1.6;
    color: var(--text-primary);
}
```

**Use for:** Paragraphs, descriptions, general content

#### Small Text

```css
.small-text {
    font-size: 0.875rem;     /* 14px */
    font-weight: 400;
    line-height: 1.5;
    color: var(--text-secondary);
}
```

**Use for:** Meta information, captions, disclaimers

### Typography Examples

```html
<!-- Hero Title -->
<h1 class="hero-title">Your AI-Powered Partner for UK Innovator Visa Success</h1>

<!-- Section Title -->
<h2 class="section-title">Everything You Need to Succeed</h2>

<!-- Card Title -->
<h3 class="card-title">Instant Eligibility Check</h3>

<!-- Body Text -->
<p>Navigate the complex visa process with AI that works 24/7</p>

<!-- Small Text -->
<span class="small-text">Last updated: November 2025</span>
```

---

## 3. Spacing System

### Base Unit: 0.25rem (4px)

```css
/* Spacing Scale */
--space-1: 0.25rem;   /* 4px */
--space-2: 0.5rem;    /* 8px */
--space-3: 0.75rem;   /* 12px */
--space-4: 1rem;      /* 16px */
--space-5: 1.25rem;   /* 20px */
--space-6: 1.5rem;    /* 24px */
--space-8: 2rem;      /* 32px */
--space-10: 2.5rem;   /* 40px */
--space-12: 3rem;     /* 48px */
--space-16: 4rem;     /* 64px */
--space-20: 5rem;     /* 80px */
--space-24: 6rem;     /* 96px */
```

### Spacing Usage

| Spacing | Use Case | CSS Value |
|---------|----------|-----------|
| **space-1** | Icon gaps, badge padding | 4px |
| **space-2** | Button padding (vertical) | 8px |
| **space-3** | Small gaps between elements | 12px |
| **space-4** | Default padding, small margins | 16px |
| **space-6** | Card padding, medium gaps | 24px |
| **space-8** | Large card padding | 32px |
| **space-12** | Section padding (mobile) | 48px |
| **space-16** | Section padding (desktop) | 64px |
| **space-24** | Large section spacing | 96px |

### Container & Layout

```css
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;    /* Mobile */
    padding: 0 2rem;      /* Desktop */
}

.section {
    padding: 3rem 0;      /* Mobile: 48px */
    padding: 4rem 0;      /* Desktop: 64px */
}

.section-large {
    padding: 5rem 0;      /* Mobile: 80px */
    padding: 7rem 0;      /* Desktop: 112px */
}
```

---

## 4. Components

### 4.1 Buttons

#### Primary Button (Glass-Morphism)

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

.btn-primary:active {
    transform: scale(0.98) translateZ(0);
}
```

**HTML:**
```html
<a href="#cta" class="btn-primary">Start Free Assessment</a>
```

#### Secondary Button

```css
.btn-secondary {
    background: transparent;
    border: 2px solid var(--black);
    border-radius: 9999px;
    padding: 0.75rem 2rem;
    font-size: 1rem;
    font-weight: 600;
    color: var(--black);
    cursor: pointer;
    transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-secondary:hover {
    background: var(--black);
    color: var(--white);
    transform: scale(1.02);
}
```

**HTML:**
```html
<a href="#features" class="btn-secondary">Explore Our Services</a>
```

#### Dark CTA Button

```css
.btn-dark {
    background: var(--black);
    color: var(--white);
    border-radius: 9999px;
    padding: 1rem 2.5rem;
    font-size: 1.125rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
}

.btn-dark:hover {
    background: rgba(0, 0, 0, 0.85);
    transform: scale(1.02);
}
```

### 4.2 Cards

#### Feature Card

```css
.feature-card {
    background: var(--white);
    border: 1px solid var(--border-color);
    border-radius: 1rem;          /* 16px */
    padding: 2rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    opacity: 0;
    transform: translateY(20px);
}

.feature-card.visible {
    opacity: 1;
    transform: translateY(0);
}

.feature-card:hover {
    transform: translateY(-5px) scale(1.01);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border-color: rgba(0, 0, 0, 0.1);
}
```

**HTML:**
```html
<div class="feature-card">
    <div class="feature-icon">
        <!-- Icon SVG -->
    </div>
    <h3 class="feature-title">Instant Eligibility Check</h3>
    <p class="feature-description">
        Our AI analyzes your business against Home Office criteria...
    </p>
</div>
```

#### Blog Card

```css
.blog-card {
    background: var(--white);
    border-radius: 1rem;
    overflow: hidden;
    border: 1px solid var(--border-color);
    transition: all 0.3s;
}

.blog-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.blog-thumbnail {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.blog-content {
    padding: 1.5rem;
}

.blog-title {
    font-size: 1.25rem;
    font-weight: 600;
    line-height: 1.4;
    margin-bottom: 0.75rem;
}
```

### 4.3 Icons

#### Feature Icon (Gradient Background)

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

.feature-icon svg {
    width: 32px;
    height: 32px;
    color: var(--black);
}
```

**HTML:**
```html
<div class="feature-icon">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
</div>
```

### 4.4 Testimonial Card

```css
.testimonial-card {
    background: var(--white);
    border: 1px solid var(--border-color);
    border-radius: 1rem;
    padding: 2rem;
    text-align: left;
}

.testimonial-photo {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    margin-bottom: 1rem;
    /* Gradient backgrounds for placeholders */
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

.testimonial-quote {
    font-size: 1rem;
    line-height: 1.6;
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
    font-style: italic;
}

.testimonial-author {
    font-weight: 600;
    color: var(--black);
}

.testimonial-title {
    font-size: 0.875rem;
    color: var(--text-tertiary);
}
```

### 4.5 Stats Counter

```css
.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 3rem;           /* 48px - Mobile */
    font-size: 4rem;           /* 64px - Desktop */
    font-weight: 800;
    background: linear-gradient(135deg,
        var(--color-yellow),
        var(--color-green));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1rem;
    color: var(--text-secondary);
    font-weight: 500;
}
```

**HTML:**
```html
<div class="stat-item">
    <div class="stat-number">95%</div>
    <div class="stat-label">Success Rate</div>
</div>
```

---

## 5. Layout Patterns

### 5.1 Hero Section

```css
.hero-section {
    padding: 7rem 0 3rem;
    background: var(--white);
    background-image:
        radial-gradient(at 53% 78%, hsla(60,100%,50%,0.3) 0px, transparent 50%),
        radial-gradient(at 71% 91%, hsla(108,100%,50%,0.3) 0px, transparent 50%),
        radial-gradient(at 31% 91%, hsla(30,100%,50%,0.17) 0px, transparent 50%);
    min-height: 100vh;
    display: flex;
    align-items: center;
}

.hero-content {
    text-align: center;
    max-width: 900px;
    margin: 0 auto;
}

.hero-title {
    font-size: 2.25rem;        /* Mobile */
    font-size: 4.5rem;         /* Desktop */
    font-weight: 800;
    line-height: 0.9;
    letter-spacing: -0.020em;
    margin-bottom: 1.5rem;
}

.hero-subtitle {
    font-size: 1.25rem;
    font-size: 1.5rem;         /* Desktop */
    color: var(--text-secondary);
    margin-bottom: 1rem;
    font-weight: 500;
}

.hero-description {
    font-size: 1.125rem;
    color: var(--text-secondary);
    max-width: 700px;
    margin: 0 auto 2rem;
    line-height: 1.6;
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}
```

**HTML:**
```html
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Your AI-Powered Partner for UK Innovator Visa Success</h1>
            <p class="hero-subtitle">Navigate the complex visa process with AI that works 24/7</p>
            <p class="hero-description">
                TorlyAI leverages cutting-edge artificial intelligence to guide you through every step...
            </p>
            <div class="hero-buttons">
                <a href="#assessment" class="btn-primary">Start Free Assessment</a>
                <a href="#features" class="btn-secondary">Explore Our Services</a>
            </div>
        </div>
    </div>
</section>
```

### 5.2 Features Grid

```css
.features-section {
    padding: 4rem 0;
    background: var(--white);
}

.features-grid {
    display: grid;
    grid-template-columns: 1fr;           /* Mobile: 1 column */
    gap: 2rem;
    margin-top: 3rem;
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

### 5.3 Two-Column Layout

```css
.two-column-section {
    display: grid;
    grid-template-columns: 1fr;           /* Mobile: Stack */
    gap: 3rem;
    align-items: center;
}

@media (min-width: 1024px) {
    .two-column-section {
        grid-template-columns: 1fr 1fr;   /* Desktop: Side by side */
    }
}
```

### 5.4 CTA Section

```css
.cta-section {
    padding: 5rem 0;
    background: var(--black);
    background-image:
        radial-gradient(at 30% 50%, hsla(60,100%,50%,0.2) 0px, transparent 50%),
        radial-gradient(at 70% 50%, hsla(108,100%,50%,0.2) 0px, transparent 50%);
    color: var(--white);
    text-align: center;
}

.cta-section h2 {
    color: var(--white);
    font-size: 2.5rem;
    font-size: 4rem;               /* Desktop */
    margin-bottom: 1rem;
}

.cta-section p {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1.25rem;
    margin-bottom: 2rem;
}
```

---

## 6. Animations

### 6.1 Scroll Fade-In

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

**JavaScript (Intersection Observer):**
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

### 6.2 Stats Counter Animation

```javascript
function animateNumber(element) {
    const text = element.textContent;
    const targetValue = parseInt(text.replace(/[^0-9]/g, ''));
    const duration = 2000; // 2 seconds
    const frameDuration = 1000 / 60; // 60 FPS
    const totalFrames = Math.round(duration / frameDuration);
    let frame = 0;

    const counter = setInterval(() => {
        frame++;
        const progress = frame / totalFrames;
        const currentValue = Math.round(targetValue * easeOutQuad(progress));

        let displayText = currentValue.toLocaleString();
        if (text.includes('%')) displayText += '%';
        if (text.includes('+')) displayText += '+';

        element.textContent = displayText;

        if (frame === totalFrames) {
            clearInterval(counter);
            element.textContent = text;
        }
    }, frameDuration);
}

function easeOutQuad(t) {
    return t * (2 - t);
}
```

### 6.3 Hover Transitions

```css
/* Standard hover lift */
.hover-lift {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

/* Button scale */
.hover-scale {
    transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-scale:hover {
    transform: scale(1.02);
}
```

### 6.4 Smooth Scroll

```javascript
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href === '#') return;

        const target = document.querySelector(href);
        if (target) {
            e.preventDefault();
            const headerOffset = 80;
            const elementPosition = target.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    });
});
```

---

## 7. Forms

### 7.1 Input Fields

```css
.form-input {
    width: 100%;
    padding: 1rem 1.25rem;
    font-size: 1rem;
    border: 2px solid var(--border-color);
    border-radius: 0.75rem;
    background: var(--white);
    color: var(--black);
    transition: all 0.2s;
}

.form-input:focus {
    outline: none;
    border-color: var(--color-chat-green);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.form-input::placeholder {
    color: var(--text-tertiary);
}

.form-input.invalid {
    border-color: #ef4444;
}

.form-input.valid {
    border-color: var(--color-chat-green);
}
```

### 7.2 Text Area

```css
.form-textarea {
    width: 100%;
    padding: 1rem 1.25rem;
    font-size: 1rem;
    border: 2px solid var(--border-color);
    border-radius: 0.75rem;
    background: var(--white);
    min-height: 150px;
    resize: vertical;
    font-family: inherit;
    transition: all 0.2s;
}

.form-textarea:focus {
    outline: none;
    border-color: var(--color-chat-green);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}
```

### 7.3 Form Labels

```css
.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--black);
    margin-bottom: 0.5rem;
}

.form-label.required::after {
    content: " *";
    color: #ef4444;
}
```

### 7.4 Complete Form Example

```html
<form class="contact-form">
    <div class="form-group">
        <label class="form-label required" for="name">Your Name</label>
        <input type="text" id="name" class="form-input" placeholder="John Doe" required>
    </div>

    <div class="form-group">
        <label class="form-label required" for="email">Email Address</label>
        <input type="email" id="email" class="form-input" placeholder="john@example.com" required>
    </div>

    <div class="form-group">
        <label class="form-label" for="message">Message</label>
        <textarea id="message" class="form-textarea" placeholder="Tell us about your project..."></textarea>
    </div>

    <button type="submit" class="btn-primary">Send Message</button>
</form>
```

```css
.form-group {
    margin-bottom: 1.5rem;
}

.contact-form {
    max-width: 600px;
    margin: 0 auto;
}
```

### 7.5 Form Validation States

```css
.form-error {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: none;
}

.form-input.invalid ~ .form-error {
    display: block;
}

.form-success {
    background: #d1fae5;
    border: 1px solid #10b981;
    color: #065f46;
    padding: 1rem;
    border-radius: 0.75rem;
    margin-top: 1rem;
}
```

---

## 8. Responsive Design

### Breakpoints

```css
/* Mobile First Approach */

/* Small devices (landscape phones, 640px and up) */
@media (min-width: 640px) {
    /* Styles */
}

/* Medium devices (tablets, 768px and up) */
@media (min-width: 768px) {
    /* Styles */
}

/* Large devices (desktops, 1024px and up) */
@media (min-width: 1024px) {
    /* Styles */
}

/* Extra large devices (large desktops, 1280px and up) */
@media (min-width: 1280px) {
    /* Styles */
}
```

### Responsive Typography

```css
/* Fluid Typography Formula */
.fluid-text {
    font-size: clamp(1rem, 2.5vw, 1.5rem);
}

/* Example: Hero Title */
.hero-title {
    font-size: clamp(2.25rem, 5vw, 4.5rem);
}
```

### Mobile-First Grid

```css
.responsive-grid {
    display: grid;
    grid-template-columns: 1fr;           /* Mobile: 1 column */
    gap: 1.5rem;
}

@media (min-width: 768px) {
    .responsive-grid {
        grid-template-columns: repeat(2, 1fr);  /* Tablet: 2 columns */
        gap: 2rem;
    }
}

@media (min-width: 1024px) {
    .responsive-grid {
        grid-template-columns: repeat(3, 1fr);  /* Desktop: 3 columns */
        gap: 2.5rem;
    }
}
```

### Mobile Navigation

```css
.mobile-menu {
    display: block;                       /* Show on mobile */
}

.desktop-menu {
    display: none;                        /* Hide on mobile */
}

@media (min-width: 1024px) {
    .mobile-menu {
        display: none;                    /* Hide on desktop */
    }

    .desktop-menu {
        display: flex;                    /* Show on desktop */
    }
}
```

---

## 9. Accessibility

### Focus States

```css
/* All Interactive Elements */
*:focus-visible {
    outline: 2px solid var(--color-chat-green);
    outline-offset: 2px;
}

/* Buttons */
button:focus-visible,
.btn-primary:focus-visible,
.btn-secondary:focus-visible {
    outline: 2px solid var(--color-chat-green);
    outline-offset: 4px;
}

/* Form Inputs */
input:focus-visible,
textarea:focus-visible,
select:focus-visible {
    border-color: var(--color-chat-green);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}
```

### Color Contrast

All text meets WCAG 2.1 AA standards:
- **Black on White:** 21:1 (AAA)
- **Text Secondary (70% opacity):** 7:1 (AA)
- **Text Tertiary (50% opacity):** 4.5:1 (AA minimum)

### Screen Reader Support

```html
<!-- Skip to main content -->
<a href="#main-content" class="skip-link">Skip to main content</a>

<!-- Accessible button -->
<button aria-label="Close menu" aria-expanded="false">
    <svg aria-hidden="true">...</svg>
</button>

<!-- Image alt text -->
<img src="logo.png" alt="TorlyAI - UK Innovator Visa AI Assistant">

<!-- Form labels -->
<label for="email">Email Address</label>
<input type="email" id="email" aria-required="true" aria-invalid="false">
```

### Keyboard Navigation

```css
/* Skip Link (for keyboard users) */
.skip-link {
    position: absolute;
    top: -40px;
    left: 0;
    background: var(--black);
    color: var(--white);
    padding: 8px;
    text-decoration: none;
    z-index: 100;
}

.skip-link:focus {
    top: 0;
}
```

---

## 10. Gradient Formulas

### Hero Section Gradients

```css
/* Yellow (Bottom Left) */
radial-gradient(at 53% 78%, hsla(60,100%,50%,0.3) 0px, transparent 50%)

/* Green (Bottom Right) */
radial-gradient(at 71% 91%, hsla(108,100%,50%,0.3) 0px, transparent 50%)

/* Orange (Bottom Center) */
radial-gradient(at 31% 91%, hsla(30,100%,50%,0.17) 0px, transparent 50%)
```

**Usage:**
```css
background-image:
    radial-gradient(at 53% 78%, hsla(60,100%,50%,0.3) 0px, transparent 50%),
    radial-gradient(at 71% 91%, hsla(108,100%,50%,0.3) 0px, transparent 50%),
    radial-gradient(at 31% 91%, hsla(30,100%,50%,0.17) 0px, transparent 50%);
```

### Feature Section Gradients (Subtle)

```css
/* Light Green/Orange Mix */
background-image:
    radial-gradient(at 20% 50%, hsla(108,100%,50%,0.2) 0px, transparent 50%),
    radial-gradient(at 80% 50%, hsla(30,100%,50%,0.15) 0px, transparent 50%);
```

### CTA Section Gradients (Dark Background)

```css
background: var(--black);
background-image:
    radial-gradient(at 30% 50%, hsla(60,100%,50%,0.2) 0px, transparent 50%),
    radial-gradient(at 70% 50%, hsla(108,100%,50%,0.2) 0px, transparent 50%);
```

### Text Gradient (Stats Numbers)

```css
background: linear-gradient(135deg,
    var(--color-yellow),
    var(--color-green));
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;
background-clip: text;
```

### Icon Background Gradient

```css
background: linear-gradient(135deg,
    hsla(60,100%,50%,0.2) 0%,
    hsla(108,100%,50%,0.2) 100%);
```

---

## 11. Glass-Morphism Effects

### Primary Glass Button

```css
.glass-button {
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.8);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.glass-button:hover {
    background: rgba(255, 255, 255, 0.8);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}
```

### Glass Card

```css
.glass-card {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.9);
    border-radius: 1rem;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}
```

### Dark Glass

```css
.glass-dark {
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: var(--white);
}
```

---

## 12. Usage Examples

### Complete Page Template

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorlyAI - Page Title</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Main Headline Goes Here</h1>
                <p class="hero-subtitle">Supporting subtitle text</p>
                <p class="hero-description">
                    Longer description paragraph explaining the value proposition...
                </p>
                <div class="hero-buttons">
                    <a href="#cta" class="btn-primary">Primary Action</a>
                    <a href="#features" class="btn-secondary">Secondary Action</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title">Section Title</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg>...</svg>
                    </div>
                    <h3 class="feature-title">Feature Title</h3>
                    <p class="feature-description">Feature description...</p>
                </div>
                <!-- Repeat feature cards -->
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Get Started?</h2>
                <p>Join thousands who've achieved their goals</p>
                <div class="cta-buttons">
                    <a href="#signup" class="btn-primary">Get Started</a>
                    <a href="#contact" class="btn-secondary">Contact Us</a>
                </div>
            </div>
        </div>
    </section>

    <script src="main.js"></script>
</body>
</html>
```

---

## 13. Quick Reference

### Common Combinations

#### Card with Icon
```html
<div class="feature-card">
    <div class="feature-icon">
        <svg>...</svg>
    </div>
    <h3 class="card-title">Title</h3>
    <p>Description</p>
</div>
```

#### CTA Block
```html
<div class="cta-block">
    <h2>Headline</h2>
    <p>Supporting text</p>
    <a href="#" class="btn-primary">Call to Action</a>
</div>
```

#### Stats Display
```html
<div class="stats-grid">
    <div class="stat-item">
        <div class="stat-number">95%</div>
        <div class="stat-label">Success Rate</div>
    </div>
</div>
```

---

## 14. Do's and Don'ts

### ✅ Do

- Use radial gradients sparingly (hero, CTA sections only)
- Maintain high contrast for text readability
- Use glass-morphism for primary CTAs
- Apply hover effects to interactive elements
- Use the spacing system consistently
- Keep animations subtle and purposeful
- Ensure all colors meet WCAG AA standards
- Test on mobile devices regularly

### ❌ Don't

- Overuse gradients (avoid on every section)
- Use yellow/green/orange for body text
- Create buttons without hover states
- Ignore responsive breakpoints
- Use custom font sizes (stick to the scale)
- Animate too many elements at once
- Forget focus states for keyboard users
- Use backdrop-filter without fallbacks

---

## 15. Performance Optimization

### CSS Best Practices

```css
/* Use transform for animations (GPU accelerated) */
.animated {
    transform: translateZ(0);  /* Force GPU acceleration */
    will-change: transform;    /* Hint to browser */
}

/* Avoid animating expensive properties */
/* ❌ Don't animate: width, height, margin, padding */
/* ✅ Do animate: transform, opacity */
```

### Loading Performance

```html
<!-- Preload critical fonts -->
<link rel="preload" href="fonts/system.woff2" as="font" type="font/woff2" crossorigin>

<!-- Lazy load images -->
<img src="placeholder.jpg" data-src="actual-image.jpg" loading="lazy" alt="Description">
```

### JavaScript Performance

```javascript
// Debounce scroll events
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func(...args), wait);
    };
}

window.addEventListener('scroll', debounce(() => {
    // Scroll handler
}, 100));
```

---

## 16. Browser Support

### Supported Browsers

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- iOS Safari 14+
- Chrome Mobile

### Fallbacks

```css
/* Backdrop filter fallback */
.glass-button {
    background: rgba(255, 255, 255, 0.9);  /* Fallback */
    backdrop-filter: blur(16px);            /* Modern browsers */
}

@supports not (backdrop-filter: blur(16px)) {
    .glass-button {
        background: rgba(255, 255, 255, 0.95);
    }
}
```

---

## 17. Version History

- **v2.0.0** (2025-11-17): Complete Granola.ai-inspired redesign
- **v1.0.0** (Initial): Original blue SaaS design

---

## 18. Resources

### Design Files
- Color Palette: See Section 1
- Typography Scale: See Section 2
- Component Library: See Section 4

### External References
- Inspiration: https://www.granola.ai/
- Icons: https://heroicons.com/
- Gradients: See Section 10

### Support
- Email: jasonxu05@gmail.com
- Documentation: IMPLEMENTATION_SUMMARY.md
- Security: SECURITY_FIXES.md

---

**Last Updated:** 2025-11-17
**Maintained by:** TorlyAI Development Team
**License:** GPL v2 or later

---

## Copy-Paste Code Snippets

### Quick Start HTML

```html
<!-- Minimal Page Structure -->
<section class="hero-section">
    <div class="container">
        <h1 class="hero-title">Your Headline</h1>
        <p class="hero-subtitle">Subtitle text</p>
        <div class="hero-buttons">
            <a href="#" class="btn-primary">Get Started</a>
        </div>
    </div>
</section>
```

### Quick Start CSS

```css
/* Essential Styles */
:root {
    --white: #ffffff;
    --black: #000000;
    --color-yellow: hsl(60, 100%, 50%);
    --color-green: hsl(108, 100%, 50%);
    --color-orange: hsl(30, 100%, 50%);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

.btn-primary {
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(16px);
    border-radius: 9999px;
    padding: 0.75rem 2rem;
    font-weight: 600;
}
```

---

**🎨 End of TorlyAI Design System Guide**
