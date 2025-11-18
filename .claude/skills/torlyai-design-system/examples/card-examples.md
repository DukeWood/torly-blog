# Card Examples - TorlyAI Design System

## 1. Feature Card with Icon

```html
<div class="feature-card fade-in-element">
    <div class="feature-icon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <h3 class="feature-title">Instant Eligibility Check</h3>
    <p class="feature-description">
        Our AI analyzes your business against Home Office criteria in seconds.
    </p>
</div>
```

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

.feature-title {
    font-size: clamp(1.25rem, 2.5vw, 1.5rem);
    font-weight: 600;
    line-height: 1.3;
    color: var(--black);
    margin-bottom: 0.75rem;
}

.feature-description {
    font-size: clamp(1rem, 1.5vw, 1.125rem);
    line-height: 1.6;
    color: var(--text-secondary);
}
```

---

## 2. Blog Card

```html
<div class="blog-card">
    <img src="featured-image.jpg" alt="Blog post title" class="blog-thumbnail">
    <div class="blog-content">
        <h3 class="blog-title">UK Innovator Visa 2026: Complete Guide</h3>
        <p class="blog-excerpt">Navigate the complex visa process with confidence...</p>
        <a href="/blog/post-slug/" class="blog-read-more">Read More →</a>
    </div>
</div>
```

```css
.blog-card {
    background: var(--white);
    border-radius: 1rem;
    overflow: hidden;
    border: 1px solid var(--border-color);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
    font-size: clamp(1.25rem, 2vw, 1.5rem);
    font-weight: 600;
    line-height: 1.4;
    margin-bottom: 0.75rem;
    color: var(--black);
}

.blog-excerpt {
    font-size: 1rem;
    line-height: 1.6;
    color: var(--text-secondary);
    margin-bottom: 1rem;
}

.blog-read-more {
    color: var(--color-chat-green);
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s;
}

.blog-read-more:hover {
    color: #059669;
    transform: translateX(4px);
}
```

---

## 3. Testimonial Card

```html
<div class="testimonial-card">
    <div class="testimonial-photo"></div>
    <p class="testimonial-quote">
        "TorlyAI made the UK visa process so much easier. Highly recommended!"
    </p>
    <div class="testimonial-author">Sarah Johnson</div>
    <div class="testimonial-title">Tech Entrepreneur</div>
</div>
```

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
    margin-bottom: 0.25rem;
}

.testimonial-title {
    font-size: 0.875rem;
    color: var(--text-tertiary);
}
```

---

## 4. Stats Card

```html
<div class="stat-item">
    <div class="stat-number">95%</div>
    <div class="stat-label">Success Rate</div>
</div>
```

```css
.stat-item {
    text-align: center;
}

.stat-number {
    font-size: clamp(3rem, 8vw, 4rem);
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

---

## 5. Features Grid Layout

```html
<section class="features-section">
    <div class="container">
        <h2 class="section-title">Everything You Need to Succeed</h2>
        <div class="features-grid">
            <div class="feature-card"><!-- Card 1 --></div>
            <div class="feature-card"><!-- Card 2 --></div>
            <div class="feature-card"><!-- Card 3 --></div>
        </div>
    </div>
</section>
```

```css
.features-section {
    padding: 4rem 0;
    background: var(--white);
}

.section-title {
    font-size: clamp(1.875rem, 4vw, 3rem);
    font-weight: 700;
    line-height: 1.1;
    letter-spacing: -0.015em;
    color: var(--black);
    text-align: center;
    margin-bottom: 3rem;
}

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
