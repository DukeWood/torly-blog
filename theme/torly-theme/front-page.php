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
                <button onclick="openWaitlistModal()" class="btn-primary">Join Waitlist</button>
                <a href="#features" class="btn-secondary">Explore Our Services</a>
            </div>
        </div>
    </div>
</section>

<!-- Social Proof Section -->
<section class="social-proof-section">
    <div class="container">
        <p class="social-proof-title">Trusted by entrepreneurs worldwide</p>
    </div>

    <div class="logo-carousel">
        <!-- Endorsing Bodies (4 currently authorized) -->
        <div class="logo-item" style="min-width: 200px; text-align: center; font-weight: 600; font-size: 14px;">UK Endorsing Services</div>
        <div class="logo-item" style="min-width: 200px; text-align: center; font-weight: 600; font-size: 14px;">Innovator International</div>
        <div class="logo-item" style="min-width: 150px; text-align: center; font-weight: 600; font-size: 14px;">Envestors Limited</div>
        <div class="logo-item" style="min-width: 250px; text-align: center; font-weight: 600; font-size: 14px;">Global Entrepreneurs Programme</div>
        <!-- Duplicate for seamless loop -->
        <div class="logo-item" style="min-width: 200px; text-align: center; font-weight: 600; font-size: 14px;">UK Endorsing Services</div>
        <div class="logo-item" style="min-width: 200px; text-align: center; font-weight: 600; font-size: 14px;">Innovator International</div>
        <div class="logo-item" style="min-width: 150px; text-align: center; font-weight: 600; font-size: 14px;">Envestors Limited</div>
        <div class="logo-item" style="min-width: 250px; text-align: center; font-weight: 600; font-size: 14px;">Global Entrepreneurs Programme</div>
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
                <div class="testimonial-photo" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);"></div>
                <p class="testimonial-quote">
                    "TorlyAI made the complex visa process so much simpler. The AI-generated business plan was exactly what UKES was looking for. Highly recommended!"
                </p>
                <p class="testimonial-author">Sarah Chen</p>
                <p class="testimonial-title">Tech Startup Founder • 🇬🇧 UK Based</p>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-photo" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);"></div>
                <p class="testimonial-quote">
                    "Got my endorsement from Innovator International in just 6 weeks thanks to the comprehensive business plan and financial models. Incredible service!"
                </p>
                <p class="testimonial-author">David Okonkwo</p>
                <p class="testimonial-title">FinTech Founder • 🇬🇧 UK Based</p>
            </div>

            <div class="testimonial-card">
                <div class="testimonial-photo" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);"></div>
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
                <button onclick="openWaitlistModal()" class="btn-primary">Join Waitlist</button>
                <a href="<?php echo home_url('/contact'); ?>" class="btn-secondary">Schedule a Consultation</a>
            </div>
        </div>
    </div>
</section>

<!-- Waitlist Modal -->
<div id="waitlistModal" class="waitlist-modal">
    <div class="waitlist-modal-content">
        <button class="waitlist-modal-close" onclick="closeWaitlistModal()">&times;</button>

        <div class="waitlist-modal-header">
            <h2>Join the Waitlist</h2>
            <p>Be the first to know when TorlyAI launches. Get exclusive early access and special launch pricing!</p>
        </div>

        <form id="waitlistForm" class="waitlist-form">
            <div class="form-group">
                <label for="waitlistEmail">Email Address</label>
                <input
                    type="email"
                    id="waitlistEmail"
                    name="email"
                    placeholder="your.email@example.com"
                    required
                    autocomplete="email"
                />
            </div>

            <button type="submit" class="btn-primary-full" id="waitlistSubmitBtn">
                <span class="btn-text">Join Waitlist</span>
                <span class="btn-loading" style="display: none;">
                    <svg class="spinner" viewBox="0 0 24 24">
                        <circle class="spinner-circle" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                    </svg>
                    Joining...
                </span>
            </button>

            <p class="form-disclaimer">
                We respect your privacy. Unsubscribe at any time.
                See our <a href="<?php echo home_url('/privacy'); ?>">Privacy Policy</a>.
            </p>
        </form>

        <!-- Step 1: Success + Questions -->
        <div id="waitlistSuccess" class="waitlist-success" style="display: none;">
            <div class="success-icon">✓</div>
            <h3>You're on the list!</h3>
            <p>Check your email for confirmation and next steps.</p>

            <div class="profile-questions">
                <h4>Help us personalize your experience (optional)</h4>

                <div class="form-group">
                    <label for="profileCountry">Where are you currently based?</label>
                    <select id="profileCountry" name="country" class="form-select">
                        <option value="">Select country...</option>
                        <option value="GB">🇬🇧 United Kingdom</option>
                        <option value="US">🇺🇸 United States</option>
                        <option value="IN">🇮🇳 India</option>
                        <option value="CN">🇨🇳 China</option>
                        <option value="NG">🇳🇬 Nigeria</option>
                        <option disabled>──────────</option>
                        <option value="AF">Afghanistan</option>
                        <option value="AL">Albania</option>
                        <option value="DZ">Algeria</option>
                        <option value="AR">Argentina</option>
                        <option value="AU">Australia</option>
                        <option value="AT">Austria</option>
                        <option value="BD">Bangladesh</option>
                        <option value="BE">Belgium</option>
                        <option value="BR">Brazil</option>
                        <option value="CA">Canada</option>
                        <option value="CL">Chile</option>
                        <option value="CO">Colombia</option>
                        <option value="EG">Egypt</option>
                        <option value="FR">France</option>
                        <option value="DE">Germany</option>
                        <option value="GH">Ghana</option>
                        <option value="HK">Hong Kong</option>
                        <option value="ID">Indonesia</option>
                        <option value="IE">Ireland</option>
                        <option value="IL">Israel</option>
                        <option value="IT">Italy</option>
                        <option value="JP">Japan</option>
                        <option value="KE">Kenya</option>
                        <option value="MY">Malaysia</option>
                        <option value="MX">Mexico</option>
                        <option value="NL">Netherlands</option>
                        <option value="NZ">New Zealand</option>
                        <option value="PK">Pakistan</option>
                        <option value="PH">Philippines</option>
                        <option value="PL">Poland</option>
                        <option value="RU">Russia</option>
                        <option value="SA">Saudi Arabia</option>
                        <option value="SG">Singapore</option>
                        <option value="ZA">South Africa</option>
                        <option value="KR">South Korea</option>
                        <option value="ES">Spain</option>
                        <option value="SE">Sweden</option>
                        <option value="CH">Switzerland</option>
                        <option value="TH">Thailand</option>
                        <option value="TR">Turkey</option>
                        <option value="AE">United Arab Emirates</option>
                        <option value="VN">Vietnam</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>What stage is your business?</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="business_stage" value="idea">
                            <span>Just an idea</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="business_stage" value="mvp">
                            <span>Building MVP</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="business_stage" value="revenue">
                            <span>Have customers/revenue</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="business_stage" value="scale">
                            <span>Ready to scale</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>When are you planning to apply?</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="timeline" value="0-3months">
                            <span>Next 3 months</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="timeline" value="3-6months">
                            <span>3-6 months</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="timeline" value="6-12months">
                            <span>6-12 months</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="timeline" value="researching">
                            <span>Just researching</span>
                        </label>
                    </div>
                </div>

                <div class="question-buttons">
                    <button onclick="submitProfile()" class="btn-primary-full">Submit Answers</button>
                    <button onclick="skipProfile()" class="btn-secondary-full">Skip</button>
                </div>

                <p class="form-disclaimer">Your answers help us provide better guidance. All optional.</p>
            </div>
        </div>

        <!-- Step 2: Patreon Invitation -->
        <div id="waitlistPatreon" class="waitlist-patreon" style="display: none;">
            <div class="community-icon">👥</div>
            <h3>Join Innovatorly Tribe</h3>
            <p class="community-tagline">Connect with 100+ founders navigating the UK visa journey</p>

            <ul class="community-benefits">
                <li>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.667 9.167v.833a6.667 6.667 0 11-3.95-6.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.667 3.333L10 10.008l-2-2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Weekly live Q&A with visa experts
                </li>
                <li>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.667 9.167v.833a6.667 6.667 0 11-3.95-6.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.667 3.333L10 10.008l-2-2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Exclusive resources & templates
                </li>
                <li>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.667 9.167v.833a6.667 6.667 0 11-3.95-6.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.667 3.333L10 10.008l-2-2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Private community forum
                </li>
                <li>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.667 9.167v.833a6.667 6.667 0 11-3.95-6.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.667 3.333L10 10.008l-2-2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Founder success stories
                </li>
            </ul>

            <div class="patreon-buttons">
                <a href="https://patreon.com/innovatorly?utm_medium=waitlist&utm_source=torlyai&utm_campaign=waitlist_success&utm_content=modal_cta"
                   target="_blank"
                   rel="noopener noreferrer"
                   onclick="trackPatreonClick()"
                   class="btn-patreon">
                    Join the Community
                </a>
                <button onclick="closeWaitlistModal()" class="btn-secondary-full">Close</button>
            </div>
        </div>

        <div id="waitlistError" class="waitlist-error" style="display: none;">
            <p class="error-message"></p>
            <button onclick="resetWaitlistForm()" class="btn-secondary">Try Again</button>
        </div>
    </div>
</div>

<style>
/* Waitlist Modal Styles - Following TorlyAI Design System */
.waitlist-modal {
    display: none;
    position: fixed;
    z-index: 10000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(4px);
    animation: fadeIn 0.2s ease;
}

.waitlist-modal.active {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.waitlist-modal-content {
    background: white;
    border-radius: 1rem;
    max-width: 500px;
    width: 100%;
    position: relative;
    padding: 2.5rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
}

.waitlist-modal-close {
    position: absolute;
    right: 1.5rem;
    top: 1.5rem;
    background: transparent;
    border: none;
    font-size: 2rem;
    line-height: 1;
    cursor: pointer;
    color: var(--text-tertiary);
    transition: color 0.15s;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.waitlist-modal-close:hover {
    color: var(--text-primary);
}

.waitlist-modal-header {
    text-align: center;
    margin-bottom: 2rem;
}

.waitlist-modal-header h2 {
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 700;
    margin-bottom: 0.75rem;
    color: var(--black);
}

.waitlist-modal-header p {
    font-size: 1rem;
    color: var(--text-secondary);
    line-height: 1.5;
}

.waitlist-form .form-group {
    margin-bottom: 1.5rem;
}

.waitlist-form label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
    font-size: 0.95rem;
}

.waitlist-form input[type="email"] {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: 0.5rem;
    font-size: 1rem;
    font-family: inherit;
    transition: all 0.15s;
    background: white;
}

.waitlist-form input[type="email"]:focus {
    outline: none;
    border-color: var(--color-chat-green);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.btn-primary-full {
    width: 100%;
    padding: 1rem;
    background: var(--color-chat-green);
    color: white;
    border: none;
    border-radius: 9999px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
    font-family: inherit;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-primary-full:hover:not(:disabled) {
    background: #0ea472;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-primary-full:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.spinner {
    width: 20px;
    height: 20px;
    animation: spin 1s linear infinite;
}

.spinner-circle {
    stroke-dasharray: 63;
    stroke-dashoffset: 32;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.form-disclaimer {
    text-align: center;
    font-size: 0.85rem;
    color: var(--text-tertiary);
    margin-top: 1rem;
    line-height: 1.5;
}

.form-disclaimer a {
    color: var(--color-chat-green);
    text-decoration: none;
}

.form-disclaimer a:hover {
    text-decoration: underline;
}

.waitlist-success, .waitlist-error {
    text-align: center;
}

.success-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--color-green), var(--color-chat-green));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: white;
    margin: 0 auto 1.5rem;
    animation: successPop 0.5s ease;
}

@keyframes successPop {
    0% { transform: scale(0); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.waitlist-success h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    color: var(--black);
}

.waitlist-success p {
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
    font-size: 1rem;
}

.waitlist-error {
    padding: 1.5rem;
    background: #fef2f2;
    border-radius: 0.5rem;
    border: 2px solid #fecaca;
}

.error-message {
    color: #dc2626;
    font-weight: 600;
    margin-bottom: 1rem;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Profile Questions Styling */
.profile-questions {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-color);
}

.profile-questions h4 {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--black);
    margin-bottom: 1.5rem;
    text-align: center;
}

.form-select {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: 0.5rem;
    font-size: 1rem;
    font-family: inherit;
    background: white;
    cursor: pointer;
    transition: all 0.15s;
}

.form-select:focus {
    outline: none;
    border-color: var(--color-chat-green);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.radio-group {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.radio-option {
    display: flex;
    align-items: center;
    padding: 0.875rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.15s;
    font-size: 0.95rem;
}

.radio-option:hover {
    border-color: var(--color-chat-green);
    background: rgba(16, 185, 129, 0.05);
}

.radio-option input[type="radio"] {
    margin-right: 0.75rem;
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--color-chat-green);
}

.radio-option input[type="radio"]:checked + span {
    font-weight: 600;
    color: var(--color-chat-green);
}

.question-buttons {
    display: flex;
    gap: 0.75rem;
    margin-top: 1.5rem;
}

.btn-secondary-full {
    flex: 1;
    padding: 0.875rem 1rem;
    background: white;
    color: var(--text-primary);
    border: 2px solid var(--border-color);
    border-radius: 9999px;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
    font-family: inherit;
}

.btn-secondary-full:hover {
    border-color: var(--text-primary);
    background: var(--bg-secondary);
}

/* Patreon Invitation Styling */
.waitlist-patreon {
    text-align: center;
    padding: 1rem 0;
}

.community-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--color-yellow), var(--color-green));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    margin: 0 auto 1.5rem;
    animation: successPop 0.5s ease;
}

.waitlist-patreon h3 {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--black);
}

.community-tagline {
    font-size: 1rem;
    color: var(--text-secondary);
    margin-bottom: 2rem;
}

.community-benefits {
    list-style: none;
    padding: 0;
    margin: 0 0 2rem 0;
    text-align: left;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.community-benefits li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.75rem 0;
    font-size: 0.95rem;
    color: var(--text-primary);
}

.community-benefits li svg {
    flex-shrink: 0;
    margin-top: 2px;
    color: var(--color-chat-green);
}

.patreon-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-top: 2rem;
}

.btn-patreon {
    width: 100%;
    padding: 1rem;
    background: var(--color-chat-green);
    color: white;
    border: none;
    border-radius: 9999px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s;
    font-family: inherit;
    text-decoration: none;
    display: inline-block;
}

.btn-patreon:hover {
    background: #0ea472;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

@media (max-width: 640px) {
    .waitlist-modal-content {
        padding: 2rem 1.5rem;
    }

    .waitlist-modal-header h2 {
        font-size: 1.5rem;
    }

    .question-buttons {
        flex-direction: column;
    }

    .radio-option {
        padding: 0.75rem;
        font-size: 0.875rem;
    }
}
</style>

<script>
// Waitlist Modal Functions
function openWaitlistModal() {
    const modal = document.getElementById('waitlistModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';

    // Focus on email input
    setTimeout(() => {
        document.getElementById('waitlistEmail').focus();
    }, 100);
}

function closeWaitlistModal() {
    const modal = document.getElementById('waitlistModal');
    modal.classList.remove('active');
    document.body.style.overflow = '';

    // Reset form after a delay
    setTimeout(() => {
        resetWaitlistForm();
    }, 300);
}

function resetWaitlistForm() {
    document.getElementById('waitlistForm').style.display = 'block';
    document.getElementById('waitlistSuccess').style.display = 'none';
    document.getElementById('waitlistPatreon').style.display = 'none';
    document.getElementById('waitlistError').style.display = 'none';
    document.getElementById('waitlistForm').reset();

    // Reset profile questions
    document.getElementById('profileCountry').value = '';
    document.querySelectorAll('input[name="business_stage"]').forEach(r => r.checked = false);
    document.querySelectorAll('input[name="timeline"]').forEach(r => r.checked = false);
}

// Store user email for profile submission
let userWaitlistEmail = '';

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
        // Collect additional data
        const payload = {
            email: email,
            device_type: getDeviceType(),
            referrer: document.referrer || window.location.href
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
