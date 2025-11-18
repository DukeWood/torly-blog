# Form Examples - TorlyAI Design System

## 1. Complete Contact Form

```html
<form id="contact-form" class="modern-form">
    <div class="form-group">
        <label class="form-label required" for="name">Your Name</label>
        <input type="text" id="name" name="name" class="form-input" placeholder="John Doe" required>
        <span class="form-error">Please enter your name</span>
    </div>

    <div class="form-group">
        <label class="form-label required" for="email">Email Address</label>
        <input type="email" id="email" name="email" class="form-input" placeholder="john@example.com" required>
        <span class="form-error">Please enter a valid email</span>
    </div>

    <div class="form-group">
        <label class="form-label" for="phone">Phone Number</label>
        <input type="tel" id="phone" name="phone" class="form-input" placeholder="+44 20 1234 5678">
    </div>

    <div class="form-group">
        <label class="form-label required" for="message">Message</label>
        <textarea id="message" name="message" class="form-textarea" placeholder="Tell us about your project..." required></textarea>
        <span class="form-error">Please enter a message</span>
    </div>

    <button type="submit" class="btn-primary btn-submit">Send Message</button>
</form>
```

---

## 2. Form Styles (Complete CSS)

```css
/* Form Container */
.modern-form {
    max-width: 600px;
    margin: 0 auto;
}

/* Form Group */
.form-group {
    margin-bottom: 1.5rem;
}

/* Form Labels */
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

/* Form Inputs */
.form-input,
.form-textarea {
    width: 100%;
    padding: 1rem 1.25rem;
    font-size: 1rem;
    border: 2px solid var(--border-color);
    border-radius: 0.75rem;       /* 12px - design system compliant */
    background: var(--white);
    color: var(--black);
    transition: all 0.2s;
    font-family: inherit;
}

.form-input::placeholder,
.form-textarea::placeholder {
    color: var(--text-tertiary);
}

/* Focus State */
.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--color-chat-green);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

/* Textarea Specific */
.form-textarea {
    min-height: 150px;
    resize: vertical;
}

/* Validation States */
.form-input.invalid,
.form-textarea.invalid {
    border-color: #ef4444;
}

.form-input.valid,
.form-textarea.valid {
    border-color: var(--color-chat-green);
}

/* Error Messages */
.form-error {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: none;
}

.form-input.invalid ~ .form-error,
.form-textarea.invalid ~ .form-error {
    display: block;
}

/* Success Message */
.form-success {
    background: #d1fae5;
    border: 1px solid #10b981;
    color: #065f46;
    padding: 1rem;
    border-radius: 0.75rem;
    margin-top: 1rem;
    text-align: center;
}

/* Submit Button */
.btn-submit {
    width: 100%;
    padding: 1rem 2rem;
    font-size: 1.125rem;
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.8);
    border-radius: 9999px;
    font-weight: 600;
    color: var(--black);
    cursor: pointer;
    transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-submit:hover {
    background: rgba(255, 255, 255, 0.8);
    transform: scale(1.02);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.btn-submit:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
```

---

## 3. Form Validation JavaScript

```javascript
document.getElementById('contact-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    // Clear previous errors
    document.querySelectorAll('.form-input, .form-textarea').forEach(el => {
        el.classList.remove('invalid', 'valid');
    });

    // Validate fields
    const name = document.getElementById('name');
    const email = document.getElementById('email');
    const message = document.getElementById('message');

    let isValid = true;

    // Name validation
    if (name.value.trim() === '') {
        name.classList.add('invalid');
        isValid = false;
    } else {
        name.classList.add('valid');
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.value)) {
        email.classList.add('invalid');
        isValid = false;
    } else {
        email.classList.add('valid');
    }

    // Message validation
    if (message.value.trim() === '') {
        message.classList.add('invalid');
        isValid = false;
    } else {
        message.classList.add('valid');
    }

    // If valid, submit
    if (isValid) {
        const submitBtn = e.target.querySelector('.btn-submit');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending...';

        try {
            // Submit form data
            const formData = new FormData(e.target);
            const response = await fetch('/wp-json/torlyai/v1/contact-form', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                // Show success message
                const successMsg = document.createElement('div');
                successMsg.className = 'form-success';
                successMsg.textContent = 'Thank you! Your message has been sent successfully.';
                e.target.appendChild(successMsg);

                // Reset form
                e.target.reset();
                document.querySelectorAll('.form-input, .form-textarea').forEach(el => {
                    el.classList.remove('valid');
                });
            } else {
                throw new Error('Submission failed');
            }
        } catch (error) {
            alert('Sorry, there was an error submitting your message. Please try again.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Send Message';
        }
    }
});
```

---

## 4. Select Dropdown

```html
<div class="form-group">
    <label class="form-label required" for="visa-type">Visa Type</label>
    <select id="visa-type" name="visa_type" class="form-select" required>
        <option value="">Select visa type...</option>
        <option value="innovator">Innovator Visa</option>
        <option value="scale-up">Scale-up Visa</option>
        <option value="startup">Start-up Visa</option>
    </select>
</div>
```

```css
.form-select {
    width: 100%;
    padding: 1rem 1.25rem;
    font-size: 1rem;
    border: 2px solid var(--border-color);
    border-radius: 0.75rem;
    background: var(--white);
    color: var(--black);
    transition: all 0.2s;
    font-family: inherit;
    cursor: pointer;
}

.form-select:focus {
    outline: none;
    border-color: var(--color-chat-green);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}
```

---

## 5. Checkbox & Radio Buttons

```html
<!-- Checkbox -->
<div class="form-group">
    <label class="form-checkbox">
        <input type="checkbox" name="terms" required>
        <span class="checkbox-label">I agree to the terms and conditions</span>
    </label>
</div>

<!-- Radio Buttons -->
<div class="form-group">
    <label class="form-label">Business Stage</label>
    <label class="form-radio">
        <input type="radio" name="stage" value="idea">
        <span class="radio-label">Idea Stage</span>
    </label>
    <label class="form-radio">
        <input type="radio" name="stage" value="mvp">
        <span class="radio-label">MVP Built</span>
    </label>
    <label class="form-radio">
        <input type="radio" name="stage" value="revenue">
        <span class="radio-label">Generating Revenue</span>
    </label>
</div>
```

```css
/* Checkbox */
.form-checkbox {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
}

.form-checkbox input[type="checkbox"] {
    width: 1.25rem;
    height: 1.25rem;
    cursor: pointer;
    accent-color: var(--color-chat-green);
}

.checkbox-label {
    font-size: 0.875rem;
    color: var(--text-secondary);
}

/* Radio Buttons */
.form-radio {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    margin-bottom: 0.75rem;
}

.form-radio input[type="radio"] {
    width: 1.25rem;
    height: 1.25rem;
    cursor: pointer;
    accent-color: var(--color-chat-green);
}

.radio-label {
    font-size: 1rem;
    color: var(--text-primary);
}
```

---

## 6. Multi-Step Form Progress

```html
<div class="form-progress">
    <div class="progress-step active">
        <div class="step-number">1</div>
        <div class="step-label">Personal Info</div>
    </div>
    <div class="progress-line"></div>
    <div class="progress-step">
        <div class="step-number">2</div>
        <div class="step-label">Business Details</div>
    </div>
    <div class="progress-line"></div>
    <div class="progress-step">
        <div class="step-number">3</div>
        <div class="step-label">Review</div>
    </div>
</div>
```

```css
.form-progress {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 3rem;
}

.progress-step {
    text-align: center;
}

.step-number {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: var(--border-color);
    color: var(--text-tertiary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin: 0 auto 0.5rem;
    transition: all 0.3s;
}

.progress-step.active .step-number {
    background: var(--color-chat-green);
    color: var(--white);
}

.step-label {
    font-size: 0.875rem;
    color: var(--text-tertiary);
}

.progress-step.active .step-label {
    color: var(--black);
    font-weight: 600;
}

.progress-line {
    width: 80px;
    height: 2px;
    background: var(--border-color);
    margin: 0 1rem;
}
```
