# Product Requirements Document (PRD)
# TorlyAI Landing Page Redesign - Granola.ai Inspired

**Version:** 1.0
**Date:** 2025-11-17
**Owner:** TorlyAI Development Team
**Status:** Draft - Awaiting Approval

---

## 1. Executive Summary

### 1.1 Overview
Redesign the TorlyAI landing page (torly.ai) to adopt a modern, vibrant aesthetic inspired by granola.ai, transforming from a traditional blue SaaS design to a visually striking, conversion-focused experience.

### 1.2 Goals
- **Primary:** Increase landing page conversion rate by 40%
- **Secondary:** Improve brand perception as modern and innovative
- **Tertiary:** Reduce bounce rate by 25%

### 1.3 Success Metrics
- Conversion rate (assessment starts)
- Time on page
- Scroll depth
- Click-through rate on CTAs
- Mobile engagement metrics

---

## 2. User Stories

**As a** potential UK visa applicant
**I want** an engaging, trustworthy landing page
**So that** I feel confident starting my visa assessment

**As a** entrepreneur researching visa options
**I want** clear information hierarchy with visual appeal
**So that** I can quickly understand TorlyAI's value proposition

**As a** mobile user
**I want** a responsive, fast-loading design
**So that** I can explore services on any device

---

## 3. Current vs. Proposed Design

### 3.1 Current State
- Traditional blue color scheme (#2563eb)
- Standard SaaS layout
- Moderate typography (3.5rem hero)
- Basic gradients and shadows
- Static design elements

### 3.2 Proposed State (Granola.ai Inspired)
- Vibrant multi-color palette (yellow, green, orange gradients)
- Modern glass-morphism design
- MASSIVE typography (7rem hero)
- Animated interactive elements
- Radial gradient backgrounds

---

## 4. Design System Specifications

### 4.1 Color Palette

```
PRIMARY COLORS:
├─ White:         #ffffff
├─ Black:         #000000
├─ Neutral-50:    #f9fafb
├─ Neutral-100:   #f3f4f6
└─ Neutral-200:   #e5e7eb

ACCENT COLORS (HSL for gradients):
├─ Yellow:        hsl(60, 100%, 50%)   /* #ffff00 */
├─ Green:         hsl(108, 100%, 50%)  /* #66ff00 */
├─ Orange:        hsl(30, 100%, 50%)   /* #ff9900 */
└─ Chat Green:    #10b981

GRADIENT OVERLAYS:
├─ Yellow:  radial-gradient(at 53% 78%, hsla(60,100%,50%,0.3) 0px, transparent 50%)
├─ Green:   radial-gradient(at 71% 91%, hsla(108,100%,50%,0.3) 0px, transparent 50%)
└─ Orange:  radial-gradient(at 31% 91%, hsla(30,100%,50%,0.17) 0px, transparent 50%)
```

### 4.2 Typography Scale

```
Hero H1:
├─ Mobile:    2.25rem (36px), font-weight: 800
├─ Tablet:    3rem (48px), font-weight: 800
└─ Desktop:   4.5rem (72px), font-weight: 800
   Letter-spacing: -0.020em
   Line-height: 0.9

Section H2:
├─ Mobile:    1.875rem (30px)
└─ Desktop:   3rem (48px)
   Font-weight: 700
   Letter-spacing: -0.015em

Body Text:
├─ Mobile:    1.125rem (18px)
└─ Desktop:   1.5rem (24px)
   Font-weight: 400
   Line-height: 1.6
```

### 4.3 Spacing System

```
Section Padding:
├─ Mobile:    4rem (64px) vertical
├─ Tablet:    6rem (96px) vertical
└─ Desktop:   5rem (80px) vertical

Container:
├─ Max-width: 72rem (1152px)
├─ Padding:   1rem (mobile), 2rem (desktop)

Gap System:
├─ Small:     1rem (16px)
├─ Medium:    2rem (32px)
└─ Large:     4rem (64px)
```

### 4.4 Component Styles

**Primary CTA Button:**
```css
Background: rgba(255, 255, 255, 0.6)
Backdrop-filter: blur(16px)
Padding: 0 2rem
Height: 3.5rem
Border-radius: 9999px (fully rounded)
Font-weight: 600
Shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05)
Transition: all 75ms
Hover: scale(1.01), background: rgba(255, 255, 255, 0.8)
```

**Feature Card:**
```css
Background: #ffffff
Padding: 2rem
Border-radius: 1rem
Shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05)
Hover: scale(1.01), shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1)
```

---

## 5. Page Wireframes

### 5.1 Hero Section (Above Fold)

```
┌─────────────────────────────────────────────────────────────────┐
│                         NAVIGATION BAR                          │
│  [TorlyAI Logo]                    [Services] [Blog] [Sign In]  │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                                                                 │
│          *** RADIAL GRADIENT BACKGROUND (Yellow/Green) ***      │
│                                                                 │
│                    Your AI-Powered Partner for                  │
│                   UK Innovator Visa Success                     │
│              [MASSIVE 7rem TEXT, BOLD, TIGHT SPACING]           │
│                                                                 │
│     Navigate the complex visa process with AI that works 24/7  │
│                   [1.5rem, medium gray text]                    │
│                                                                 │
│                                                                 │
│   ┌────────────────────────┐  ┌──────────────────────────┐     │
│   │  Start Free Assessment │  │  Explore Our Services    │     │
│   │  [Glass button, white] │  │  [Outline glass button]  │     │
│   └────────────────────────┘  └──────────────────────────┘     │
│                                                                 │
│              ⬇ Scroll to discover how we help ⬇                 │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

**Specifications:**
- Height: 100vh (full viewport)
- Background: White with 3 radial gradients (yellow, green, orange)
- Center-aligned content
- Max-width: 900px for text content
- Hero title: Gradient text effect (optional)
- Buttons: Glass-morphism with backdrop blur

---

### 5.2 Social Proof Section

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                 │
│              Trusted by entrepreneurs worldwide                 │
│                     [Small heading, centered]                   │
│                                                                 │
│  ← ← [Logo 1] [Logo 2] [Logo 3] [Logo 4] [Logo 5] [Logo 6] → → │
│         [ANIMATED MARQUEE - Scrolls automatically left]         │
│                                                                 │
│  ← ← [Logo 7] [Logo 8] [Logo 9] [Logo 10] [Logo 11] → →        │
│         [SECOND ROW - Scrolls automatically right]              │
│                                                                 │
│                    Endorsing Bodies We Support:                 │
│                                                                 │
│     [UKES]    [Innovator Int'l]    [Envestors]    [TechNation] │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

**Specifications:**
- Background: Neutral-50 (#f9fafb)
- Padding: 4rem vertical
- Logo size: 120px width, grayscale with opacity 0.6
- Hover: opacity 1.0, scale 1.05
- Marquee speed: 30s for full scroll
- Two rows: opposite scroll directions

---

### 5.3 Features Grid Section

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                 │
│         *** RADIAL GRADIENT BACKGROUND (Green/Orange) ***       │
│                                                                 │
│                  Everything You Need to Succeed                 │
│                      [3rem heading, bold]                       │
│                                                                 │
│      Comprehensive AI-powered tools for your visa journey       │
│                                                                 │
│                                                                 │
│  ┌───────────────┐  ┌───────────────┐  ┌───────────────┐      │
│  │   [Icon 🎯]   │  │   [Icon 📊]   │  │   [Icon 💼]   │      │
│  │               │  │               │  │               │      │
│  │   Instant     │  │   Business    │  │  Financial    │      │
│  │  Eligibility  │  │     Plan      │  │   Modeling    │      │
│  │    Check      │  │   Generator   │  │               │      │
│  │               │  │               │  │               │      │
│  │  AI analyzes  │  │  Generate     │  │  Build Excel  │      │
│  │  your business│  │ comprehensive │  │  models with  │      │
│  │  in minutes   │  │  12-section   │  │  endorsing    │      │
│  │               │  │  business plan│  │  body focus   │      │
│  │               │  │               │  │               │      │
│  └───────────────┘  └───────────────┘  └───────────────┘      │
│                                                                 │
│  ┌───────────────┐  ┌───────────────┐  ┌───────────────┐      │
│  │   [Icon 📋]   │  │   [Icon 🎤]   │  │   [Icon ✅]   │      │
│  │               │  │               │  │               │      │
│  │   Document    │  │  Pitch Deck   │  │  Compliance   │      │
│  │ Organization  │  │   Creation    │  │  Validation   │      │
│  │               │  │               │  │               │      │
│  │ Comprehensive │  │  Generate     │  │  Validate     │      │
│  │  checklist    │  │  PowerPoint   │  │  against Home │      │
│  │  for complete │  │  optimized    │  │  Office &     │      │
│  │   packages    │  │  for endorser │  │  endorser     │      │
│  │               │  │               │  │  criteria     │      │
│  └───────────────┘  └───────────────┘  └───────────────┘      │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

**Specifications:**
- Grid: 3 columns desktop, 1 column mobile
- Card: White background, shadow-sm, rounded-lg (1rem)
- Icon: 60px circle, gradient background
- Card hover: scale(1.01), lift with shadow
- Gap: 2rem between cards
- Fade-in animation on scroll

---

### 5.4 How It Works Section

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                 │
│                  Simple 4-Step Process                          │
│                  [3rem heading, bold]                           │
│                                                                 │
│                                                                 │
│    ⓵────────────⓶────────────⓷────────────⓸                    │
│    │            │            │            │                    │
│    │            │            │            │                    │
│  ┌─────┐      ┌─────┐      ┌─────┐      ┌─────┐               │
│  │  1  │      │  2  │      │  3  │      │  4  │               │
│  │ [●] │      │ [●] │      │ [●] │      │ [●] │               │
│  └─────┘      └─────┘      └─────┘      └─────┘               │
│                                                                 │
│    Take          Choose       Generate     Submit &            │
│  Assessment      Endorser     Documents    Succeed             │
│                                                                 │
│  Complete our   Get matched   AI creates   Submit your         │
│  AI-powered     with the      all required complete            │
│  assessment     best endorser documents    package with        │
│  to evaluate    for your      tailored to  confidence          │
│  eligibility    business      your endorser                    │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

**Specifications:**
- Background: Linear gradient (neutral-100 to white)
- Step circles: 60px diameter, gradient fill
- Connecting line: 2px solid, neutral-200
- Numbers: 1.5rem, bold, white text
- Desktop: Horizontal layout
- Mobile: Vertical stack (hide connecting line)

---

### 5.5 Interactive Demo Section (NEW)

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                 │
│         *** RADIAL GRADIENT BACKGROUND (Yellow/Green) ***       │
│                                                                 │
│              See TorlyAI in Action                              │
│              [3rem heading, bold]                               │
│                                                                 │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  ┌──────────────────────────────────────────────┐       │   │
│  │  │  [Browser Chrome - torly.ai/visa-assessment]│       │   │
│  │  └──────────────────────────────────────────────┘       │   │
│  │                                                          │   │
│  │  What's your business idea?                             │   │
│  │  ┌────────────────────────────────────────────────┐     │   │
│  │  │ AI-powered meeting notes assistant for...▌    │     │   │
│  │  │ [BLINKING CURSOR ANIMATION]                    │     │   │
│  │  └────────────────────────────────────────────────┘     │   │
│  │                                                          │   │
│  │  ┌──────────────────────────────────┐                   │   │
│  │  │  ✓ Analyzing innovation factors  │                   │   │
│  │  │  ✓ Checking Home Office criteria │                   │   │
│  │  │  ✓ Matching with endorsers       │                   │   │
│  │  │  ⏳ Generating recommendations... │                   │   │
│  │  └──────────────────────────────────┘                   │   │
│  │                                                          │   │
│  │  [Glass-morphism card with inset white shadow]          │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

**Specifications:**
- Container: Max-width 800px, centered
- Browser mockup: White background, rounded top corners
- Input field: Glass effect with inset shadow
- Typing animation: 60 characters/minute
- Loading animation: Progress bar or spinner
- Background: Multiple radial gradients

---

### 5.6 Testimonials Section

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                 │
│           What Our Successful Clients Say                       │
│           [3rem heading, bold, centered]                        │
│                                                                 │
│                                                                 │
│  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────┐ │
│  │  ┌────────┐      │  │  ┌────────┐      │  │  ┌────────┐  │ │
│  │  │ [Photo]│      │  │  │ [Photo]│      │  │  │ [Photo]│  │ │
│  │  └────────┘      │  │  └────────┘      │  │  └────────┘  │ │
│  │                  │  │                  │  │              │ │
│  │ "TorlyAI made the│  │ "Got my endorsem-│  │ "The AI save-│ │
│  │  complex visa    │  │  ent from UKES   │  │  d me months │ │
│  │  process so much │  │  in just 6 weeks │  │  of work on  │ │
│  │  simpler!"       │  │  thanks to the   │  │  my business │ │
│  │                  │  │  comprehensive   │  │  plan."      │ │
│  │                  │  │  business plan." │  │              │ │
│  │                  │  │                  │  │              │ │
│  │  Sarah Chen      │  │  David Okonkwo   │  │  Priya Patel │ │
│  │  Tech Startup    │  │  FinTech Founder │  │  SaaS CEO    │ │
│  │  🇬🇧 UK Based    │  │  🇬🇧 UK Based    │  │  🇬🇧 UK Based│ │
│  └──────────────────┘  └──────────────────┘  └──────────────┘ │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

**Specifications:**
- Background: White
- Grid: 3 columns desktop, 1 column mobile
- Photo: 80px circle, neutral background if no image
- Card: Light border, rounded-lg, padding 2rem
- Quote: 1.125rem, line-height 1.6
- Name: Bold, 1rem
- Title: 0.875rem, gray
- Flag emoji: For visual interest

---

### 5.7 Stats Section

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                 │
│         *** RADIAL GRADIENT BACKGROUND (Orange/Green) ***       │
│                                                                 │
│                                                                 │
│    ┌─────────┐    ┌─────────┐    ┌─────────┐    ┌─────────┐   │
│    │         │    │         │    │         │    │         │   │
│    │   95%   │    │ 2,000+  │    │  48hrs  │    │  24/7   │   │
│    │         │    │         │    │         │    │         │   │
│    │ Success │    │Applicat-│    │ Average │    │   AI    │   │
│    │  Rate   │    │  ions   │    │Processing│   │ Support │   │
│    │         │    │Processed│    │  Time   │    │Available│   │
│    │         │    │         │    │         │    │         │   │
│    └─────────┘    └─────────┘    └─────────┘    └─────────┘   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

**Specifications:**
- Background: Gradient overlay
- Grid: 4 columns desktop, 2 columns mobile
- Number: 3rem font-size, bold, gradient text
- Label: 1.125rem, centered
- Animation: Count-up on scroll into view

---

### 5.8 Blog Preview Section

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                 │
│            Latest Visa Insights & Guides                        │
│            [3rem heading, bold, centered]                       │
│                                                                 │
│                                                                 │
│  ┌────────────────┐  ┌────────────────┐  ┌────────────────┐   │
│  │ ┌────────────┐ │  │ ┌────────────┐ │  │ ┌────────────┐ │   │
│  │ │ [Featured] │ │  │ │ [Featured] │ │  │ │ [Featured] │ │   │
│  │ │   Image    │ │  │ │   Image    │ │  │ │   Image    │ │   │
│  │ └────────────┘ │  │ └────────────┘ │  │ └────────────┘ │   │
│  │                │  │                │  │                │   │
│  │ Nov 17, 2025   │  │ Nov 15, 2025   │  │ Nov 12, 2025   │   │
│  │ UK Visa Guide  │  │ Innovator Visa │  │ Business Immig.│   │
│  │                │  │                │  │                │   │
│  │ UK Innovator   │  │ How to Prepare │  │ Top 5 UK       │   │
│  │ Visa 2025:     │  │ a Winning      │  │ Endorsing      │   │
│  │ Complete Guide │  │ Business Plan  │  │ Bodies for...  │   │
│  │                │  │                │  │                │   │
│  │ The ultimate   │  │ Step-by-step   │  │ Compare the    │   │
│  │ guide for      │  │ guide to       │  │ leading        │   │
│  │ entrepreneurs  │  │ creating a     │  │ endorsing...   │   │
│  │                │  │                │  │                │   │
│  │ Read More →    │  │ Read More →    │  │ Read More →    │   │
│  └────────────────┘  └────────────────┘  └────────────────┘   │
│                                                                 │
│                    ┌──────────────────────┐                     │
│                    │  View All Blog Posts │                     │
│                    └──────────────────────┘                     │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

**Specifications:**
- Background: Neutral-50
- Grid: 3 columns desktop, 1 column mobile
- Card: White, shadow-sm, hover lift
- Image: 16:9 aspect ratio, object-fit: cover
- Category badge: Small pill, colored background
- Date: Small, gray text
- Excerpt: 2 lines max, ellipsis
- "View All" button: Glass style

---

### 5.9 Final CTA Section

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                 │
│         *** RADIAL GRADIENT BACKGROUND (All 3 colors) ***       │
│                                                                 │
│                                                                 │
│             Ready to Start Your UK Innovation Journey?          │
│             [4rem heading, bold, white text]                    │
│                                                                 │
│     Join thousands of entrepreneurs who've successfully         │
│         navigated the visa process with TorlyAI                 │
│                  [1.5rem, white, opacity 0.9]                   │
│                                                                 │
│                                                                 │
│   ┌──────────────────────┐    ┌──────────────────────────┐     │
│   │  Start Free Today    │    │  Schedule a Consultation │     │
│   │  [Large glass button]│    │  [Outline glass button]  │     │
│   └──────────────────────┘    └──────────────────────────┘     │
│                                                                 │
│                                                                 │
│              No credit card required • 14-day free trial        │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

**Specifications:**
- Height: 60vh minimum
- Background: Dark overlay (rgba(0,0,0,0.3)) over gradients
- Text: White, center-aligned
- Buttons: Extra large (h-16), glass effect
- Subtext: Small, opacity 0.7
- Max-width: 800px centered

---

### 5.10 Footer

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                 │
│  ┌──────────┐   ┌──────────┐   ┌──────────┐   ┌──────────┐    │
│  │ Product  │   │ Company  │   │ Resources│   │ Legal    │    │
│  │          │   │          │   │          │   │          │    │
│  │ Features │   │ About Us │   │ Blog     │   │ Privacy  │    │
│  │ Pricing  │   │ Careers  │   │ Help Ctr │   │ Terms    │    │
│  │ API      │   │ Contact  │   │ Guides   │   │ Cookies  │    │
│  └──────────┘   └──────────┘   └──────────┘   └──────────┘    │
│                                                                 │
│  ──────────────────────────────────────────────────────────     │
│                                                                 │
│  © 2025 TorlyAI. All rights reserved.                          │
│                                          [Twitter] [LinkedIn]   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

**Specifications:**
- Background: Neutral-50
- Grid: 4 columns desktop, 1 column mobile
- Links: Gray, hover blue
- Padding: 4rem top, 2rem bottom
- Font-size: 0.875rem
- Social icons: 24px, circular backgrounds

---

## 6. Technical Requirements

### 6.1 File Structure
```
theme/torly-theme/
├── style.css (main stylesheet - COMPLETE REWRITE)
├── front-page.php (landing page template - MAJOR UPDATES)
├── assets/
│   ├── js/
│   │   └── main.js (NEW - animations & interactions)
│   └── images/
│       ├── logos/ (endorsing body logos)
│       └── testimonials/ (client photos)
├── functions.php (existing - no changes)
├── header.php (minor updates for new nav style)
└── footer.php (minor updates for new footer layout)
```

### 6.2 Browser Support
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari 14+, Chrome Mobile)

### 6.3 Performance Requirements
- Lighthouse Performance Score: 90+
- First Contentful Paint: < 1.5s
- Time to Interactive: < 3.5s
- Cumulative Layout Shift: < 0.1

### 6.4 Accessibility Requirements
- WCAG 2.1 AA compliance
- Keyboard navigation support
- Screen reader compatibility
- Minimum contrast ratio: 4.5:1
- Focus indicators on all interactive elements

### 6.5 Responsive Breakpoints
```
Mobile:     < 640px
Tablet:     640px - 1024px
Desktop:    1024px+

Tailwind equivalents:
sm:  640px
md:  768px
lg:  1024px
xl:  1280px
```

---

## 7. Functional Requirements

### 7.1 Animations

**Scroll Animations (Intersection Observer)**
- Fade in sections as they enter viewport
- Threshold: 0.1 (trigger when 10% visible)
- Duration: 600ms
- Easing: cubic-bezier(0.4, 0, 0.2, 1)

**Hover Animations**
- Buttons: scale(1.01) in 75ms
- Cards: scale(1.01) + shadow change in 200ms
- Links: color transition in 150ms

**Logo Carousel**
- Continuous horizontal scroll
- Speed: 30 seconds per full loop
- Pause on hover
- Seamless infinite loop

**Count-up Stats**
- Trigger when section enters viewport
- Duration: 2 seconds
- Easing: ease-out

### 7.2 Interactive Elements

**CTA Buttons**
- Primary: Glass effect with backdrop-blur
- Hover: Brightness increase + scale
- Click: Slight scale-down (active state)
- Loading state: Spinner animation

**Feature Cards**
- Hover: Lift (translateY -5px)
- Click: Navigate or expand
- Icon: Subtle pulse on hover

**Form Inputs (if applicable)**
- Glass effect background
- Inset white shadow
- Focus: Border color change
- Validation: Real-time feedback

---

## 8. Content Requirements

### 8.1 Hero Section
- **Headline:** "Your AI-Powered Partner for UK Innovator Visa Success"
- **Subheadline:** "Navigate the complex visa process with AI that works 24/7"
- **CTA 1:** "Start Free Assessment"
- **CTA 2:** "Explore Our Services"

### 8.2 Social Proof
- 6-8 client company logos (if available)
- 4 endorsing body logos (UKES, Innovator International, Envestors, TechNation)
- Marquee animation with 2 rows

### 8.3 Features (6 cards)
1. Instant Eligibility Check
2. Business Plan Generator
3. Financial Modeling
4. Document Organization
5. Pitch Deck Creation
6. Compliance Validation

### 8.4 How It Works (4 steps)
1. Take Assessment
2. Choose Endorser
3. Generate Documents
4. Submit & Succeed

### 8.5 Stats (4 metrics)
- 95% Success Rate
- 2,000+ Applications Processed
- 48hrs Average Processing Time
- 24/7 AI Support Available

### 8.6 Testimonials (3 minimum)
- Client name, photo, company, and quote
- Emphasis on success stories
- Real data if available (otherwise use representative examples)

### 8.7 Blog Posts (3 latest)
- Auto-populated from WordPress
- Featured image, title, excerpt, date, category

---

## 9. Development Phases

### Phase 1: Foundation (Week 1)
- [ ] Set up new CSS color system
- [ ] Update typography scales
- [ ] Implement spacing system
- [ ] Create button components

### Phase 2: Hero & Navigation (Week 1-2)
- [ ] Redesign hero section with gradients
- [ ] Update navigation bar
- [ ] Implement glass-morphism buttons
- [ ] Add responsive hero layout

### Phase 3: Feature Sections (Week 2)
- [ ] Build feature card grid
- [ ] Add radial gradient backgrounds
- [ ] Implement hover animations
- [ ] Create icon components

### Phase 4: Interactive Elements (Week 2-3)
- [ ] Develop scroll animations (Intersection Observer)
- [ ] Build logo marquee carousel
- [ ] Add count-up stats animation
- [ ] Implement smooth scroll

### Phase 5: New Sections (Week 3)
- [ ] Social proof carousel
- [ ] Testimonials grid
- [ ] How it works timeline
- [ ] Interactive demo mockup

### Phase 6: Polish & Optimization (Week 3-4)
- [ ] Performance optimization
- [ ] Accessibility audit
- [ ] Cross-browser testing
- [ ] Mobile responsiveness check
- [ ] SEO optimization

### Phase 7: Testing & Launch (Week 4)
- [ ] User acceptance testing
- [ ] A/B test setup (if applicable)
- [ ] Analytics integration
- [ ] Production deployment

---

## 10. Success Metrics & KPIs

### 10.1 Primary Metrics
- **Conversion Rate:** Track "Start Free Assessment" clicks
  - Baseline: TBD
  - Target: +40% increase

- **Bounce Rate:** Measure immediate exits
  - Baseline: TBD
  - Target: -25% reduction

- **Time on Page:** Average session duration
  - Baseline: TBD
  - Target: +50% increase

### 10.2 Secondary Metrics
- Scroll depth (% reaching bottom)
- CTA click-through rate
- Mobile vs desktop engagement
- Social proof section views
- Feature card interactions

### 10.3 Technical Metrics
- Page load time (< 2s target)
- Lighthouse scores (90+ target)
- Core Web Vitals (all green)

---

## 11. Risks & Mitigation

### 11.1 Design Risks
**Risk:** Design too flashy for professional audience
**Mitigation:** A/B test with control group, gather user feedback

**Risk:** Color gradients reduce readability
**Mitigation:** Ensure WCAG AA contrast ratios, test with accessibility tools

### 11.2 Technical Risks
**Risk:** Animations impact performance
**Mitigation:** Use CSS transforms (GPU accelerated), lazy load sections

**Risk:** Browser compatibility issues
**Mitigation:** Progressive enhancement, fallbacks for older browsers

### 11.3 Business Risks
**Risk:** Redesign confuses existing users
**Mitigation:** Phased rollout, keep core navigation consistent

**Risk:** Conversion rate decreases
**Mitigation:** Easy rollback plan, monitor metrics closely

---

## 12. Appendices

### 12.1 Inspiration References
- **Primary:** https://www.granola.ai/
- **Color palette:** HSL-based gradients
- **Typography:** Bold, large-scale headings
- **Animations:** Subtle, purposeful

### 12.2 Design Assets Needed
- [ ] Endorsing body logos (SVG, 200x80px)
- [ ] Client testimonial photos (300x300px, circular crop)
- [ ] Company logos for social proof (SVG, 150x60px)
- [ ] Feature icons (SVG, 60x60px)
- [ ] Blog featured images (1200x630px)

### 12.3 Code References
```css
/* Example: Radial gradient background */
.hero-gradient {
  background: white;
  background-image:
    radial-gradient(at 53% 78%, hsla(60,100%,50%,0.3) 0px, transparent 50%),
    radial-gradient(at 71% 91%, hsla(108,100%,50%,0.3) 0px, transparent 50%),
    radial-gradient(at 31% 91%, hsla(30,100%,50%,0.17) 0px, transparent 50%);
}

/* Example: Glass-morphism button */
.btn-glass {
  background: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(16px);
  border-radius: 9999px;
  transition: all 75ms;
  transform: translateZ(0);
}

.btn-glass:hover {
  background: rgba(255, 255, 255, 0.8);
  transform: scale(1.01);
}
```

---

## 13. Approval & Sign-off

**Stakeholders:**
- [ ] Product Owner
- [ ] Design Lead
- [ ] Engineering Lead
- [ ] Marketing Lead

**Approval Date:** _____________

**Next Steps:**
1. Review and approve PRD
2. Allocate development resources
3. Kick off Phase 1 development
4. Schedule weekly check-ins

---

**Document Version History:**
- v1.0 (2025-11-17): Initial PRD with complete wireframes and specifications

**Contact:**
For questions or clarifications, contact: [Your contact info]
