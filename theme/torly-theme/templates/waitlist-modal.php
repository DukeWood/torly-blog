<!-- TorlyAI Waitlist Modal - Optimized Multi-Step Flow -->

<!-- Modal Backdrop -->
<div id="waitlist-backdrop" class="waitlist-backdrop" style="display: none;"></div>

<!-- Modal Container -->
<div id="waitlist-modal" class="waitlist-modal" style="display: none;">
    <!-- Close Button -->
    <button class="waitlist-close" aria-label="Close modal" onclick="closeWaitlistModal()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>

    <!-- Step 1: Email Signup -->
    <div id="waitlist-step-1" class="waitlist-step active">
        <h2 class="waitlist-title">Join the Waitlist</h2>
        <p class="waitlist-subtitle">Be the first to know when TorlyAI launches. Get exclusive early access and special launch pricing!</p>

        <form id="waitlist-email-form" class="waitlist-form">
            <div class="form-group">
                <label class="form-label" for="waitlist-email">Email Address</label>
                <input
                    type="email"
                    id="waitlist-email"
                    name="email"
                    class="form-input"
                    placeholder="you@example.com"
                    required
                    autocomplete="email"
                >
                <span class="form-error" id="email-error"></span>
            </div>

            <button type="submit" class="btn-primary btn-submit">
                <span class="btn-text">Join Waitlist</span>
                <span class="btn-loading" style="display: none;">
                    <svg class="spinner" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <circle class="spinner-circle" cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </span>
            </button>
        </form>

        <p class="waitlist-privacy">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            We respect your privacy. Unsubscribe anytime.
        </p>
    </div>

    <!-- Step 2: Success + Optional Survey CTA -->
    <div id="waitlist-step-2" class="waitlist-step">
        <div class="success-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>

        <h2 class="waitlist-title">You're on the list!</h2>
        <p class="waitlist-subtitle">Check your email for confirmation and next steps.</p>

        <div class="optional-cta">
            <div class="optional-badge">Optional • 30 seconds</div>
            <h3 class="optional-title">Help us personalize your experience</h3>
            <p class="optional-description">Answer 3 quick questions so we can provide better guidance tailored to your business stage.</p>

            <div class="cta-buttons">
                <button class="btn-primary" onclick="showSurveyStep()">Answer Quick Questions</button>
                <a href="https://www.patreon.com/innovatorly" class="btn-text">Skip for Now</a>
            </div>
        </div>
    </div>

    <!-- Step 3: Survey Questions -->
    <div id="waitlist-step-3" class="waitlist-step">
        <button class="back-button" onclick="showSuccessStep()">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
            Back
        </button>

        <h2 class="waitlist-title">Tell Us About Your Business</h2>
        <p class="waitlist-subtitle">Your answers help us provide better guidance (all optional)</p>

        <form id="waitlist-survey-form" class="waitlist-form survey-form">
            <!-- Question 1: Location -->
            <div class="form-group">
                <label class="form-label" for="location">Where are you currently based?</label>
                <select id="location" name="location" class="form-select">
                    <option value="">Select your location</option>
                    <option value="uk">🇬🇧 United Kingdom</option>
                    <option value="us">🇺🇸 United States</option>
                    <option value="eu">🇪🇺 European Union</option>
                    <option value="india">🇮🇳 India</option>
                    <option value="china">🇨🇳 China</option>
                    <option value="other-asia">🌏 Other Asia</option>
                    <option value="other">🌍 Other</option>
                </select>
            </div>

            <!-- Question 2: Business Stage -->
            <div class="form-group">
                <label class="form-label">What stage is your business?</label>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="stage" value="idea">
                        <span class="radio-label">Just an idea</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="stage" value="mvp">
                        <span class="radio-label">Building MVP</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="stage" value="customers">
                        <span class="radio-label">Have customers/revenue</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="stage" value="scaling">
                        <span class="radio-label">Ready to scale</span>
                    </label>
                </div>
            </div>

            <!-- Question 3: Timeline -->
            <div class="form-group">
                <label class="form-label">When are you planning to apply?</label>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="timeline" value="0-3">
                        <span class="radio-label">Next 3 months</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="timeline" value="3-6">
                        <span class="radio-label">3-6 months</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="timeline" value="6-12">
                        <span class="radio-label">6-12 months</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="timeline" value="research">
                        <span class="radio-label">Just researching</span>
                    </label>
                </div>
            </div>

            <div class="survey-actions">
                <button type="submit" class="btn-primary">
                    <span class="btn-text">Submit Answers</span>
                    <span class="btn-loading" style="display: none;">
                        <svg class="spinner" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <circle class="spinner-circle" cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </span>
                </button>
                <button type="button" class="btn-text" onclick="closeWaitlistModal()">Skip</button>
            </div>

            <p class="survey-note">
                Your answers help us provide better guidance. All optional.
            </p>
        </form>
    </div>

    <!-- Step 4: Final Thank You -->
    <div id="waitlist-step-4" class="waitlist-step">
        <div class="success-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>

        <h2 class="waitlist-title">Welcome to the Tribe!</h2>
        <p class="waitlist-subtitle">You're now part of the Innovatorly community. Join our exclusive tribe for insider tips, resources, and support on your UK visa journey.</p>

        <div class="final-cta">
            <p class="final-message">Ready to connect with fellow innovators?</p>
            <div class="cta-buttons">
                <a href="https://www.patreon.com/innovatorly" class="btn-primary">Join Innovatorly Tribe</a>
                <button class="btn-text" onclick="closeWaitlistModal()">Close</button>
            </div>
        </div>
    </div>
</div>
