/**
 * TorlyAI Waitlist Modal - JavaScript Controller
 * Handles multi-step flow, form validation, and API submissions
 * Version: 2.0.0
 */

(function() {
    'use strict';

    // ============================================
    // STATE MANAGEMENT
    // ============================================

    const waitlistState = {
        email: '',
        location: '',
        stage: '',
        timeline: '',
        currentStep: 1
    };

    // ============================================
    // MODAL CONTROL FUNCTIONS
    // ============================================

    /**
     * Open waitlist modal
     */
    window.openWaitlistModal = function() {
        const backdrop = document.getElementById('waitlist-backdrop');
        const modal = document.getElementById('waitlist-modal');

        if (backdrop && modal) {
            backdrop.style.display = 'block';
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';

            // Reset to step 1
            showStep(1);

            // Focus on email input
            setTimeout(() => {
                const emailInput = document.getElementById('waitlist-email');
                if (emailInput) emailInput.focus();
            }, 300);
        }
    };

    /**
     * Close waitlist modal
     */
    window.closeWaitlistModal = function() {
        const backdrop = document.getElementById('waitlist-backdrop');
        const modal = document.getElementById('waitlist-modal');

        if (backdrop && modal) {
            backdrop.style.display = 'none';
            modal.style.display = 'none';
            document.body.style.overflow = '';

            // Reset state after animation
            setTimeout(() => {
                resetModal();
            }, 300);
        }
    };

    /**
     * Reset modal to initial state
     */
    function resetModal() {
        waitlistState.currentStep = 1;

        // Clear forms
        const emailForm = document.getElementById('waitlist-email-form');
        const surveyForm = document.getElementById('waitlist-survey-form');

        if (emailForm) emailForm.reset();
        if (surveyForm) surveyForm.reset();

        // Clear errors
        const errors = document.querySelectorAll('.form-error');
        errors.forEach(error => error.textContent = '');

        const inputs = document.querySelectorAll('.form-input.invalid');
        inputs.forEach(input => input.classList.remove('invalid'));
    }

    /**
     * Show specific step
     */
    function showStep(stepNumber) {
        // Hide all steps
        const steps = document.querySelectorAll('.waitlist-step');
        steps.forEach(step => step.classList.remove('active'));

        // Show target step
        const targetStep = document.getElementById(`waitlist-step-${stepNumber}`);
        if (targetStep) {
            targetStep.classList.add('active');
            waitlistState.currentStep = stepNumber;

            // Scroll modal to top
            const modal = document.getElementById('waitlist-modal');
            if (modal) modal.scrollTop = 0;
        }
    }

    /**
     * Show success step
     */
    window.showSuccessStep = function() {
        showStep(2);
    };

    /**
     * Show survey step
     */
    window.showSurveyStep = function() {
        showStep(3);
    };

    // ============================================
    // FORM VALIDATION
    // ============================================

    /**
     * Validate email format
     */
    function validateEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    /**
     * Show error message for input
     */
    function showError(inputId, message) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(`${inputId.replace('waitlist-', '')}-error`);

        if (input) {
            input.classList.add('invalid');
        }

        if (error) {
            error.textContent = message;
        }
    }

    /**
     * Clear error message for input
     */
    function clearError(inputId) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(`${inputId.replace('waitlist-', '')}-error`);

        if (input) {
            input.classList.remove('invalid');
        }

        if (error) {
            error.textContent = '';
        }
    }

    // ============================================
    // EMAIL SIGNUP FORM
    // ============================================

    /**
     * Handle email form submission
     */
    function handleEmailSubmit(e) {
        e.preventDefault();

        const emailInput = document.getElementById('waitlist-email');
        const submitBtn = e.target.querySelector('button[type="submit"]');

        if (!emailInput || !submitBtn) return;

        const email = emailInput.value.trim();

        // Validate email
        clearError('waitlist-email');

        if (!email) {
            showError('waitlist-email', 'Email is required');
            return;
        }

        if (!validateEmail(email)) {
            showError('waitlist-email', 'Please enter a valid email address');
            return;
        }

        // Store email in state
        waitlistState.email = email;

        // Show loading state
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;

        // Submit to API
        submitEmailToAPI(email)
            .then(() => {
                // Success - show step 2
                showStep(2);
            })
            .catch((error) => {
                console.error('Waitlist signup error:', error);
                showError('waitlist-email', 'Something went wrong. Please try again.');
            })
            .finally(() => {
                // Remove loading state
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
            });
    }

    /**
     * Submit email to API/database
     */
    async function submitEmailToAPI(email) {
        // If WordPress REST API is available
        if (typeof torlyaiWaitlist !== 'undefined' && torlyaiWaitlist.ajaxUrl) {
            const formData = new FormData();
            formData.append('action', 'torlyai_waitlist_signup');
            formData.append('nonce', torlyaiWaitlist.nonce);
            formData.append('email', email);

            const response = await fetch(torlyaiWaitlist.ajaxUrl, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.data || 'Signup failed');
            }

            return data;
        } else {
            // Fallback: Send to contact form endpoint
            const response = await fetch('/wp-json/torlyai/v1/waitlist', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email })
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            return await response.json();
        }
    }

    // ============================================
    // SURVEY FORM
    // ============================================

    /**
     * Handle survey form submission
     */
    function handleSurveySubmit(e) {
        e.preventDefault();

        const submitBtn = e.target.querySelector('button[type="submit"]');
        if (!submitBtn) return;

        // Get form data
        const formData = new FormData(e.target);
        const surveyData = {
            email: waitlistState.email,
            location: formData.get('location'),
            stage: formData.get('stage'),
            timeline: formData.get('timeline')
        };

        // Store in state
        Object.assign(waitlistState, surveyData);

        // Show loading state
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;

        // Submit to API
        submitSurveyToAPI(surveyData)
            .then(() => {
                // Success - show final thank you
                showStep(4);
            })
            .catch((error) => {
                console.error('Survey submission error:', error);
                // Still show thank you even if submission fails
                showStep(4);
            })
            .finally(() => {
                // Remove loading state
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
            });
    }

    /**
     * Submit survey data to API
     */
    async function submitSurveyToAPI(data) {
        // If WordPress REST API is available
        if (typeof torlyaiWaitlist !== 'undefined' && torlyaiWaitlist.ajaxUrl) {
            const formData = new FormData();
            formData.append('action', 'torlyai_waitlist_survey');
            formData.append('nonce', torlyaiWaitlist.nonce);
            formData.append('data', JSON.stringify(data));

            const response = await fetch(torlyaiWaitlist.ajaxUrl, {
                method: 'POST',
                body: formData
            });

            return await response.json();
        } else {
            // Fallback: Send to REST endpoint
            const response = await fetch('/wp-json/torlyai/v1/waitlist/survey', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });

            return await response.json();
        }
    }

    // ============================================
    // KEYBOARD NAVIGATION
    // ============================================

    /**
     * Handle escape key to close modal
     */
    function handleEscapeKey(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('waitlist-modal');
            if (modal && modal.style.display === 'block') {
                closeWaitlistModal();
            }
        }
    }

    /**
     * Handle backdrop click to close
     */
    function handleBackdropClick() {
        closeWaitlistModal();
    }

    // ============================================
    // INITIALIZATION
    // ============================================

    /**
     * Initialize waitlist modal
     */
    function init() {
        // Email form submission
        const emailForm = document.getElementById('waitlist-email-form');
        if (emailForm) {
            emailForm.addEventListener('submit', handleEmailSubmit);
        }

        // Survey form submission
        const surveyForm = document.getElementById('waitlist-survey-form');
        if (surveyForm) {
            surveyForm.addEventListener('submit', handleSurveySubmit);
        }

        // Keyboard navigation
        document.addEventListener('keydown', handleEscapeKey);

        // Backdrop click
        const backdrop = document.getElementById('waitlist-backdrop');
        if (backdrop) {
            backdrop.addEventListener('click', handleBackdropClick);
        }

        // Clear errors on input
        const emailInput = document.getElementById('waitlist-email');
        if (emailInput) {
            emailInput.addEventListener('input', () => clearError('waitlist-email'));
        }

        // Radio button styling
        const radioInputs = document.querySelectorAll('.radio-option input[type="radio"]');
        radioInputs.forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove checked class from siblings
                const group = this.closest('.radio-group');
                if (group) {
                    const options = group.querySelectorAll('.radio-option');
                    options.forEach(opt => opt.classList.remove('checked'));
                }

                // Add checked class to parent
                const parent = this.closest('.radio-option');
                if (parent) {
                    parent.classList.add('checked');
                }
            });
        });

        console.log('✅ TorlyAI Waitlist Modal initialized');
    }

    // Run initialization when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();

// ============================================
// GLOBAL TRIGGER FUNCTIONS
// ============================================

/**
 * Trigger waitlist modal from any element
 * Usage: <button onclick="triggerWaitlistModal()">Join Waitlist</button>
 */
window.triggerWaitlistModal = function() {
    window.openWaitlistModal();
};

/**
 * Auto-open modal on page load (optional)
 * Call this function to show modal automatically
 */
window.autoOpenWaitlistModal = function(delay = 3000) {
    setTimeout(() => {
        // Check if user has already signed up (stored in localStorage)
        const hasSignedUp = localStorage.getItem('torlyai_waitlist_signed_up');

        if (!hasSignedUp) {
            window.openWaitlistModal();
        }
    }, delay);
};

/**
 * Mark user as signed up (prevent auto-open)
 */
window.markWaitlistSignedUp = function() {
    localStorage.setItem('torlyai_waitlist_signed_up', 'true');
    localStorage.setItem('torlyai_waitlist_date', new Date().toISOString());
};
