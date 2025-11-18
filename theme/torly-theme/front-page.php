<?php
/**
 * Front Page Template - Granola.ai Inspired
 *
 * @package TorlyAI
 * @version 2.0.0
 */

get_header(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Your AI-Powered Partner for UK Innovator Visa Success</h1>
            <p class="hero-subtitle">Navigate the complex visa process with AI that works 24/7</p>
            <p class="hero-description">
                TorlyAI leverages cutting-edge artificial intelligence to guide you through every step of the UK Innovator Founder Visa process.
                From initial assessment to final submission, our AI agents work around the clock to ensure your application meets all requirements.
            </p>
            <div class="hero-buttons">
                <button type="button" class="btn-primary" onclick="openWaitlistModal('hero-primary')">Join Waitlist</button>
                <a href="#features" class="btn-secondary">Explore Our Services</a>
            </div>
        </div>
    </div>
</section>

<!-- Social Proof Section -->
<section class="social-proof-section">
    <div class="container">
        <p class="social-proof-title">Authorized by all 4 UK Home Office Endorsing Bodies</p>
    </div>

    <div class="logo-carousel">
        <!-- Endorsing Bodies (4 currently authorized) -->
        <div class="logo-item">UK Endorsing Services</div>
        <div class="logo-item">Innovator International</div>
        <div class="logo-item">Envestors Limited</div>
        <div class="logo-item">Global Entrepreneurs Programme</div>
        <!-- Duplicate for seamless loop -->
        <div class="logo-item">UK Endorsing Services</div>
        <div class="logo-item">Innovator International</div>
        <div class="logo-item">Envestors Limited</div>
        <div class="logo-item">Global Entrepreneurs Programme</div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section" id="features">
    <div class="container">
        <h2 class="section-title">Everything You Need to Succeed</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="feature-title">Instant Eligibility Check</h3>
                <p class="feature-description">
                    Our AI analyzes your business against Home Office criteria and endorsing body requirements in minutes, giving you immediate feedback on your visa prospects.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="feature-title">Business Plan Generator</h3>
                <p class="feature-description">
                    Generate comprehensive 12-section business plans tailored to your chosen endorsing body's specific requirements and expectations.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                </div>
                <h3 class="feature-title">Financial Modeling</h3>
                <p class="feature-description">
                    Build Excel models with endorsing body-specific emphasis on R&D, scenarios, or revenue projections that demonstrate your business viability.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="feature-title">Document Organization</h3>
                <p class="feature-description">
                    Comprehensive checklist with endorsing body-specific requirements ensures you have complete submission packages with nothing missing.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                </div>
                <h3 class="feature-title">Pitch Deck Creation</h3>
                <p class="feature-description">
                    Generate PowerPoint presentations optimized for your endorsing body's interview requirements and presentation guidelines.
                </p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="feature-title">Compliance Validation</h3>
                <p class="feature-description">
                    Validate against Home Office criteria AND endorsing body-specific requirements to maximize your approval chances.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="how-it-works-section">
    <div class="container">
        <h2 class="section-title">Simple 4-Step Process</h2>
        <div class="process-timeline">
            <div class="process-step">
                <div class="step-number">1</div>
                <h3>Take Assessment</h3>
                <p>Complete our AI-powered assessment to evaluate your eligibility</p>
            </div>
            <div class="process-step">
                <div class="step-number">2</div>
                <h3>Choose Endorser</h3>
                <p>Get matched with the best endorsing body for your business</p>
            </div>
            <div class="process-step">
                <div class="step-number">3</div>
                <h3>Generate Documents</h3>
                <p>AI creates all required documents tailored to your endorser</p>
            </div>
            <div class="process-step">
                <div class="step-number">4</div>
                <h3>Submit & Succeed</h3>
                <p>Submit your complete package with confidence</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section">
    <div class="container">
        <h2 class="section-title">What Our Successful Clients Say</h2>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-photo testimonial-photo-1"></div>
                <p class="testimonial-quote">
                    "TorlyAI made the complex visa process so much simpler. The AI-generated business plan was exactly what UKES was looking for. Highly recommended!"
                </p>
                <p class="testimonial-author">Sarah Chen</p>
                <p class="testimonial-title">Tech Startup Founder • 🇬🇧 UK Based</p>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-photo testimonial-photo-2"></div>
                <p class="testimonial-quote">
                    "Got my endorsement from Innovator International in just 6 weeks thanks to the comprehensive business plan and financial models. Incredible service!"
                </p>
                <p class="testimonial-author">David Okonkwo</p>
                <p class="testimonial-title">FinTech Founder • 🇬🇧 UK Based</p>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-photo testimonial-photo-3"></div>
                <p class="testimonial-quote">
                    "The AI saved me months of work on my business plan and pitch deck. Everything was perfectly aligned with Envestors' requirements. Worth every penny!"
                </p>
                <p class="testimonial-author">Priya Patel</p>
                <p class="testimonial-title">SaaS CEO • 🇬🇧 UK Based</p>
            </div>
        </div>
    </div>
</section>

<!-- UK Visa Statistics Section -->
<section class="uk-visa-stats-section">
    <div class="container">
        <h2 class="section-title">UK Innovator Founder Visa by the Numbers (2026)</h2>
        <p class="section-subtitle" style="text-align: center; color: var(--text-secondary); max-width: 700px; margin: 0 auto 3rem;">
            Official statistics from the UK Home Office for the Innovator Founder route
        </p>

        <div class="visa-stats-grid">
            <div class="visa-stat-card">
                <div class="stat-number-large">85%</div>
                <div class="stat-label-primary">Success Rate</div>
                <p class="stat-description">Overall approval rate for well-prepared applications</p>
                <div class="stat-source">
                    <cite><a href="https://www.gov.uk/government/statistics/immigration-statistics-year-ending-september-2025" target="_blank" rel="noopener">
                        Home Office Statistics 2025
                    </a></cite>
                </div>
            </div>

            <div class="visa-stat-card">
                <div class="stat-number-large">18-24 weeks</div>
                <div class="stat-label-primary">Total Timeline</div>
                <p class="stat-description">From initial preparation to visa approval (6-8 weeks endorsement + 12-16 weeks processing)</p>
                <div class="stat-source">
                    <cite><a href="https://www.gov.uk/visa-processing-times" target="_blank" rel="noopener">
                        UKVI Processing Times 2026
                    </a></cite>
                </div>
            </div>

            <div class="visa-stat-card">
                <div class="stat-number-large">£50,000</div>
                <div class="stat-label-primary">Minimum Investment</div>
                <p class="stat-description">Required capital investment in your UK business</p>
                <div class="stat-source">
                    <cite><a href="https://www.gov.uk/innovator-visa" target="_blank" rel="noopener">
                        UK Home Office 2026
                    </a></cite>
                </div>
            </div>

            <div class="visa-stat-card">
                <div class="stat-number-large">3 years</div>
                <div class="stat-label-primary">Path to Settlement</div>
                <p class="stat-description">Eligible for Indefinite Leave to Remain after 3 years</p>
                <div class="stat-source">
                    <cite><a href="https://www.gov.uk/innovator-visa" target="_blank" rel="noopener">
                        UK Home Office 2026
                    </a></cite>
                </div>
            </div>
        </div>

        <div class="stats-footer" style="text-align: center; margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
            <p style="font-size: 0.95rem; color: var(--text-tertiary);">
                <strong>Total Cost:</strong> £54,796 - £55,796 minimum
                (includes visa fees, health surcharge, investment, and endorsement fees)
            </p>
        </div>
    </div>
</section>

<!-- Recent Blog Posts -->
<section class="blog-section">
    <div class="container">
        <h2 class="section-title">Latest Visa Insights & Guides</h2>
        <div class="blog-grid">
            <?php
            $recent_posts = new WP_Query(array(
                'posts_per_page' => 3,
                'post_status' => 'publish',
                'post_type' => 'post',
                'orderby' => 'date',
                'order' => 'DESC'
            ));

            if ($recent_posts->have_posts()) :
                while ($recent_posts->have_posts()) : $recent_posts->the_post();
            ?>
                <article class="blog-card">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('medium', array('class' => 'blog-thumbnail')); ?>
                    <?php else : ?>
                        <div class="blog-thumbnail" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.25rem;">
                            <?php echo substr(get_the_title(), 0, 1); ?>
                        </div>
                    <?php endif; ?>

                    <div class="blog-content">
                        <div class="blog-meta">
                            <span class="blog-date"><?php echo get_the_date(); ?></span>
                            <span class="blog-category"><?php the_category(', '); ?></span>
                        </div>
                        <h3 class="blog-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <p class="blog-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                        <a href="<?php the_permalink(); ?>" class="read-more">
                            Read More →
                        </a>
                    </div>
                </article>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
            ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                    <p style="color: var(--text-secondary); font-size: 1.125rem;">No blog posts found. Check back soon for visa insights and guides!</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center mt-3">
            <a href="https://torly.ai/blog/" class="btn-primary">View All Blog Posts</a>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="container">
        <h2 class="section-title">Frequently Asked Questions</h2>

        <div itemscope itemtype="https://schema.org/FAQPage" class="faq-list">

            <!-- Question 1 -->
            <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <h3 itemprop="name" class="faq-question">
                    <span class="faq-icon">🏛️</span>
                    What is the UK Innovator Founder Visa?
                </h3>
                <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                    <div itemprop="text" class="faq-answer">
                        <p>The UK Innovator Founder Visa (2026) is an immigration route for experienced entrepreneurs who want to establish an innovative, viable, and scalable business in the United Kingdom. It requires a minimum investment of £50,000 and endorsement from an approved body.</p>
                        <p><strong>Key Requirements:</strong></p>
                        <ul>
                            <li><strong>Investment:</strong> £50,000 minimum</li>
                            <li><strong>Endorsement:</strong> Must be approved by one of 4 authorized endorsing bodies</li>
                            <li><strong>Innovation:</strong> Business must be new to the UK market</li>
                            <li><strong>Processing Time:</strong> 12-16 weeks average</li>
                        </ul>
                        <p style="font-size: 0.9rem; color: var(--text-tertiary); margin-top: 1rem;">
                            <em>Note: Previously called "Innovator Visa" before 2023 reform.</em>
                        </p>
                        <p class="faq-source"><cite>Source: <a href="https://www.gov.uk/innovator-founder-visa" target="_blank" rel="noopener">UK Home Office (gov.uk/innovator-founder-visa)</a></cite></p>
                    </div>
                </div>
            </div>

            <!-- Question 2 -->
            <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <h3 itemprop="name" class="faq-question">
                    <span class="faq-icon">⏱️</span>
                    How long does the UK Innovator Founder Visa application take?
                </h3>
                <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                    <div itemprop="text" class="faq-answer">
                        <p>The complete UK Innovator Founder Visa application process takes 18-24 weeks on average:</p>
                        <ul>
                            <li><strong>Step 1:</strong> Endorsement application (6-8 weeks)</li>
                            <li><strong>Step 2:</strong> Visa application submission (12-16 weeks)</li>
                        </ul>
                        <p>TorlyAI can help you prepare your Innovator Founder application to minimize delays and maximize approval chances.</p>
                    </div>
                </div>
            </div>

            <!-- Question 3 -->
            <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <h3 itemprop="name" class="faq-question">
                    <span class="faq-icon">✅</span>
                    What does TorlyAI's eligibility assessment check?
                </h3>
                <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                    <div itemprop="text" class="faq-answer">
                        <p>Our AI analyzes your business idea against three key criteria:</p>
                        <ul>
                            <li><strong>Innovation:</strong> Is your business new to the UK market?</li>
                            <li><strong>Viability:</strong> Does your business plan demonstrate realistic financial projections?</li>
                            <li><strong>Scalability:</strong> Can your business create jobs and grow significantly?</li>
                        </ul>
                        <p>We provide instant feedback on your endorsement probability and areas for improvement.</p>
                    </div>
                </div>
            </div>

            <!-- Question 4 -->
            <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <h3 itemprop="name" class="faq-question">
                    <span class="faq-icon">💰</span>
                    How much does the UK Innovator Founder Visa cost?
                </h3>
                <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                    <div itemprop="text" class="faq-answer">
                        <p><strong>Total Cost Breakdown (2026):</strong></p>
                        <ul>
                            <li>Visa application fee: £1,191</li>
                            <li>Immigration Health Surcharge: £1,035/year (£3,105 for 3 years)</li>
                            <li>Minimum investment: £50,000</li>
                            <li>Endorsement body fee: £500-£1,500 (varies by body)</li>
                        </ul>
                        <p><strong>Total:</strong> £54,796 - £55,796 minimum</p>
                        <p class="faq-source"><cite>Source: <a href="https://www.gov.uk/innovator-founder-visa" target="_blank" rel="noopener">UK Home Office (Updated January 2026)</a></cite></p>
                    </div>
                </div>
            </div>

            <!-- Question 5 -->
            <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <h3 itemprop="name" class="faq-question">
                    <span class="faq-icon">🤝</span>
                    Which endorsing bodies does TorlyAI help with?
                </h3>
                <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                    <div itemprop="text" class="faq-answer">
                        <p>TorlyAI helps match you with all 4 authorized UK endorsing bodies currently accepting applications:</p>
                        <ul>
                            <li><strong>UK Endorsing Services (UKES)</strong> - General innovative businesses</li>
                            <li><strong>Innovator International</strong> - Scalable, globally-focused businesses</li>
                            <li><strong>Envestors Limited</strong> - Investment-ready businesses</li>
                            <li><strong>The Global Entrepreneurs Programme (GEP)</strong> - Tech-based, internationally mobile entrepreneurs (invitation-only)</li>
                        </ul>
                        <p>Our AI recommends the best endorsing body based on your industry, business stage, and innovation focus.</p>
                        <p class="faq-source"><cite>Source: <a href="https://www.gov.uk/government/publications/endorsing-bodies-innovator-founder-and-scale-up-visas/innovator-founder-and-scale-up-visas-endorsing-bodies" target="_blank" rel="noopener">UK Home Office - Authorized Endorsing Bodies (2026)</a></cite></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section" id="visa-assessment">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Start Your UK Innovation Journey?</h2>
            <p>Join thousands of entrepreneurs who've successfully navigated the visa process with TorlyAI</p>
            <div class="cta-buttons">
                <button onclick="openWaitlistModal('cta-bottom')" class="btn-primary">Get Your Free Assessment</button>
                <a href="#faq-section" class="btn-secondary">View FAQs</a>
            </div>
        </div>
    </div>
</section>



<script>
// Phase 2: Behavioral Tracking
const behavioralTracking = {
    pageLoadTime: Date.now(),
    maxScrollDepth: 0,
    sectionsViewed: new Set(),
    ctaSource: null
};

// Track scroll depth
function trackScrollDepth() {
    const windowHeight = window.innerHeight;
    const documentHeight = document.documentElement.scrollHeight;
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const scrollPercent = Math.round((scrollTop / (documentHeight - windowHeight)) * 100);

    if (scrollPercent > behavioralTracking.maxScrollDepth) {
        behavioralTracking.maxScrollDepth = Math.min(scrollPercent, 100);
    }
}

// Track sections viewed using Intersection Observer
function setupSectionTracking() {
    const sections = document.querySelectorAll('section[class*="section"]');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const sectionClass = entry.target.className.split(' ')[0];
                behavioralTracking.sectionsViewed.add(sectionClass);
            }
        });
    }, { threshold: 0.5 });

    sections.forEach(section => observer.observe(section));
}

// Extract UTM parameters from URL
function getUTMParams() {
    const urlParams = new URLSearchParams(window.location.search);
    return {
        utm_source: urlParams.get('utm_source'),
        utm_medium: urlParams.get('utm_medium'),
        utm_campaign: urlParams.get('utm_campaign')
    };
}

// Calculate time metrics
function getTimeMetrics() {
    const now = Date.now();
    return {
        time_on_page: Math.round((now - behavioralTracking.pageLoadTime) / 1000), // seconds
        page_load_to_signup: Math.round((now - behavioralTracking.pageLoadTime) / 1000)
    };
}

// Get behavioral data payload
function getBehavioralData() {
    const timeMetrics = getTimeMetrics();
    const utmParams = getUTMParams();

    return {
        cta_source: behavioralTracking.ctaSource,
        time_on_page: timeMetrics.time_on_page,
        scroll_depth: behavioralTracking.maxScrollDepth,
        sections_viewed: Array.from(behavioralTracking.sectionsViewed).join(','),
        page_load_to_signup: timeMetrics.page_load_to_signup,
        utm_source: utmParams.utm_source,
        utm_medium: utmParams.utm_medium,
        utm_campaign: utmParams.utm_campaign
    };
}

// Initialize tracking on page load
document.addEventListener('DOMContentLoaded', function() {
    // Setup scroll tracking
    window.addEventListener('scroll', trackScrollDepth);
    trackScrollDepth(); // Initial check

    // Setup section tracking
    setupSectionTracking();
});


// Get device type
function getDeviceType() {
    const ua = navigator.userAgent;
    if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
        return 'tablet';
    }
    if (/Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua)) {
        return 'mobile';
    }
    return 'desktop';
}

// Submit profile answers
async function submitProfile() {
    const country = document.getElementById('profileCountry').value;
    const businessStage = document.querySelector('input[name="business_stage"]:checked')?.value;
    const timeline = document.querySelector('input[name="timeline"]:checked')?.value;

    // Send update to API
    try {
        await fetch('<?php echo rest_url('torlyai/v1/waitlist'); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                email: userWaitlistEmail,
                country: country,
                business_stage: businessStage,
                timeline: timeline
            })
        });

        // Track analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'waitlist_questions_submitted', {
                'event_category': 'engagement',
                'country': country,
                'business_stage': businessStage,
                'timeline': timeline
            });
        }
    } catch (error) {
        console.error('Failed to update profile:', error);
    }

    // Show Patreon invitation (Step 2)
    showPatreonInvitation();
}

// Skip profile questions
function skipProfile() {
    // Track analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'waitlist_questions_skipped', {
            'event_category': 'engagement'
        });
    }

    // Show Patreon invitation (Step 2)
    showPatreonInvitation();
}

// Show Patreon invitation screen
function showPatreonInvitation() {
    document.getElementById('waitlistSuccess').style.display = 'none';
    document.getElementById('waitlistPatreon').style.display = 'block';

    // Track analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'patreon_invitation_shown', {
            'event_category': 'engagement'
        });
    }
}

// Track Patreon link click
function trackPatreonClick() {
    if (typeof gtag !== 'undefined') {
        gtag('event', 'patreon_link_clicked', {
            'event_category': 'engagement',
            'event_label': 'waitlist_success_modal'
        });
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('waitlistModal');
    if (event.target === modal) {
        closeWaitlistModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeWaitlistModal();
    }
});

// Handle form submission
document.getElementById('waitlistForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = this;
    const submitBtn = document.getElementById('waitlistSubmitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');
    const email = document.getElementById('waitlistEmail').value;

    // Store email for later use
    userWaitlistEmail = email;

    // Show loading state
    submitBtn.disabled = true;
    btnText.style.display = 'none';
    btnLoading.style.display = 'flex';

    try {
        // Collect all tracking data (Phase 1 + Phase 2)
        const behavioralData = getBehavioralData();
        const payload = {
            email: email,
            device_type: getDeviceType(),
            referrer: document.referrer || window.location.href,
            ...behavioralData // Spread behavioral tracking data
        };

        const response = await fetch('<?php echo rest_url('torlyai/v1/waitlist'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (response.ok && data.status === 'success') {
            // Hide form and show Step 1 (success + questions)
            form.style.display = 'none';
            document.getElementById('waitlistSuccess').style.display = 'block';

            // Track conversion (if Google Analytics is loaded)
            if (typeof gtag !== 'undefined') {
                gtag('event', 'join_waitlist', {
                    'event_category': 'engagement',
                    'event_label': 'waitlist_signup',
                    'device_type': payload.device_type,
                    'value': 1
                });
            }
        } else {
            throw new Error(data.message || 'Failed to join waitlist');
        }
    } catch (error) {
        // Show error message
        form.style.display = 'none';
        document.getElementById('waitlistError').style.display = 'block';
        document.querySelector('.error-message').textContent = error.message;
    } finally {
        // Reset button state
        submitBtn.disabled = false;
        btnText.style.display = 'inline';
        btnLoading.style.display = 'none';
    }
});
</script>

<?php get_footer(); ?>
