# Button Examples - TorlyAI Design System

## 1. Glass-Morphism Primary Button (Main CTA)

```html
<a href="#cta" class="btn-primary">Start Free Assessment</a>
```

```css
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
    transform: translateZ(0);
}

.btn-primary:hover {
    background: rgba(255, 255, 255, 0.8);
    transform: scale(1.02) translateZ(0);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}
```

## 2. Secondary Button (Outline)

```html
<a href="#features" class="btn-secondary">Explore Services</a>
```

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
    transition: all 0.15s;
}

.btn-secondary:hover {
    background: var(--black);
    color: var(--white);
    transform: scale(1.02);
}
```

## 3. Dark CTA Button

```html
<a href="#signup" class="btn-dark">Get Started Now</a>
```

```css
.btn-dark {
    background: var(--black);
    color: var(--white);
    border-radius: 9999px;
    padding: 1rem 2.5rem;
    font-size: 1.125rem;
    font-weight: 600;
    transition: all 0.15s;
}

.btn-dark:hover {
    background: rgba(0, 0, 0, 0.85);
    transform: scale(1.02);
}
```
