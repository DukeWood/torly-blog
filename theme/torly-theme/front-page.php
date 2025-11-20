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
            <div class="hero-badge">
                <span class="badge-icon">🏅</span>#1 Visa Assessment Tool
            </div>
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

<!-- Product Showcase Section -->
<section class="product-showcase-section">
    <div class="container">
        <div class="showcase-header">
            <h2 class="section-title">Experience TorlyAI in Action</h2>
            <p class="section-subtitle">Explore our AI-powered platform designed to streamline your UK Innovator Visa journey</p>
        </div>

        <!-- Feature Category Tabs -->
        <div class="showcase-tabs">
            <button class="tab-btn active" data-category="all">
                <span class="tab-icon">✨</span>
                All Features
            </button>
            <button class="tab-btn" data-category="skills">
                <span class="tab-icon">🎯</span>
                AI Skills Library
            </button>
            <button class="tab-btn" data-category="learning">
                <span class="tab-icon">📚</span>
                Learning Hub
            </button>
            <button class="tab-btn" data-category="chat">
                <span class="tab-icon">💬</span>
                AI Assistant
            </button>
        </div>

        <!-- Main Display Viewport -->
        <div class="showcase-viewport">
            <div class="viewport-frame">
                <div class="frame-chrome">
                    <div class="chrome-dots">
                        <span class="dot dot-red"></span>
                        <span class="dot dot-yellow"></span>
                        <span class="dot dot-green"></span>
                    </div>
                    <span class="chrome-title">torly.ai</span>
                </div>
                <div class="viewport-display">
                    <img
                        id="featuredImage"
                        src="<?php echo get_template_directory_uri(); ?>/assets/torlyai_product_demo_optimized/screenshot-1.webp"
                        alt="TorlyAI Product Screenshot"
                        class="showcase-image"
                        loading="lazy"
                    />
                    <div class="viewport-gradient-overlay"></div>
                </div>
            </div>
        </div>

        <!-- Thumbnail Navigation Strip -->
        <div class="showcase-thumbnails">
            <button class="thumbnail-nav nav-prev" aria-label="Previous screenshot">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div class="thumbnails-container">
                <div class="thumbnails-track">
                    <div class="thumbnail-item active" data-category="skills" data-image="screenshot-1.webp">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/torlyai_product_demo_optimized/screenshot-1.webp" alt="Skills Library - SWOT & Market Analysis" loading="lazy">
                        <div class="thumbnail-label">Skills Library</div>
                    </div>
                    <div class="thumbnail-item" data-category="skills" data-image="screenshot-2.webp">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/torlyai_product_demo_optimized/screenshot-2.webp" alt="Skills Library - Compliance Tools" loading="lazy">
                        <div class="thumbnail-label">Compliance Tools</div>
                    </div>
                    <div class="thumbnail-item" data-category="skills" data-image="screenshot-3.webp">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/torlyai_product_demo_optimized/screenshot-3.webp" alt="Skills Library - Financial Modeling" loading="lazy">
                        <div class="thumbnail-label">Financial Models</div>
                    </div>
                    <div class="thumbnail-item" data-category="skills" data-image="screenshot-4.webp">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/torlyai_product_demo_optimized/screenshot-4.webp" alt="Skills Library - Business Tools" loading="lazy">
                        <div class="thumbnail-label">Business Tools</div>
                    </div>
                    <div class="thumbnail-item" data-category="skills" data-image="screenshot-5.webp">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/torlyai_product_demo_optimized/screenshot-5.webp" alt="Skills Library - Market Analysis" loading="lazy">
                        <div class="thumbnail-label">Market Analysis</div>
                    </div>
                    <div class="thumbnail-item" data-category="learning" data-image="screenshot-6.webp">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/torlyai_product_demo_optimized/screenshot-6.webp" alt="Learning Hub - Training Modules" loading="lazy">
                        <div class="thumbnail-label">Learning Hub</div>
                    </div>
                    <div class="thumbnail-item" data-category="learning" data-image="screenshot-7.webp">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/torlyai_product_demo_optimized/screenshot-7.webp" alt="Learning Hub - Articles & Resources" loading="lazy">
                        <div class="thumbnail-label">Articles & Guides</div>
                    </div>
                    <div class="thumbnail-item" data-category="chat" data-image="screenshot-8.webp">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/torlyai_product_demo_optimized/screenshot-8.webp" alt="AI Chat Assistant - Visa Guidance" loading="lazy">
                        <div class="thumbnail-label">AI Assistant</div>
                    </div>
                </div>
            </div>

            <button class="thumbnail-nav nav-next" aria-label="Next screenshot">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <!-- Feature Indicators -->
        <div class="showcase-indicators">
            <span class="indicator active" data-index="0"></span>
            <span class="indicator" data-index="1"></span>
            <span class="indicator" data-index="2"></span>
            <span class="indicator" data-index="3"></span>
            <span class="indicator" data-index="4"></span>
            <span class="indicator" data-index="5"></span>
            <span class="indicator" data-index="6"></span>
            <span class="indicator" data-index="7"></span>
        </div>
    </div>
</section>

<!-- Assessment Results Section -->
<section class="assessment-results-section">
    <div class="container">
        <div class="results-header">
            <h2 class="section-title">See What You'll Get from Your Assessment</h2>
            <p class="section-subtitle">Comprehensive AI analysis with actionable insights to strengthen your application</p>
        </div>

        <div class="results-grid">
            <!-- Card 1: Executive Summary -->
            <div class="result-card" data-modal="executive-summary">
                <div class="card-image-wrapper">
                    <img
                        src="<?php echo get_template_directory_uri(); ?>/assets/torlyai_product_demo_optimized/screenshot-9.webp"
                        alt="Executive Summary - Critical Issues and Strengths Analysis"
                        class="result-screenshot"
                        loading="lazy"
                    />
                    <div class="zoom-overlay">
                        <span class="zoom-icon">🔍</span>
                        <span class="zoom-text">Click to Zoom</span>
                    </div>
                </div>
                <div class="card-content">
                    <h3 class="card-title">📊 Executive Summary</h3>
                    <p class="card-description">Identifies critical issues, improvement areas, and strengths across all 29 assessment questions</p>
                    <ul class="card-features">
                        <li>Critical issues flagged</li>
                        <li>Areas needing improvement</li>
                        <li>Strengths to emphasize</li>
                    </ul>
                </div>
            </div>

            <!-- Card 2: TRL Readiness Score -->
            <div class="result-card" data-modal="trl-score">
                <div class="card-image-wrapper">
                    <img
                        src="<?php echo get_template_directory_uri(); ?>/assets/torlyai_product_demo_optimized/screenshot-10.webp"
                        alt="TRL Readiness Score with Optimization Plan"
                        class="result-screenshot"
                        loading="lazy"
                    />
                    <div class="zoom-overlay">
                        <span class="zoom-icon">🔍</span>
                        <span class="zoom-text">Click to Zoom</span>
                    </div>
                </div>
                <div class="card-content">
                    <h3 class="card-title">🎯 TRL Readiness Score</h3>
                    <p class="card-description">Technology Readiness Level assessment with specific optimization roadmap for your business</p>
                    <ul class="card-features">
                        <li>Current TRL level (1-9)</li>
                        <li>Gap analysis</li>
                        <li>Actionable optimization plan</li>
                    </ul>
                </div>
            </div>

            <!-- Card 3: Business Plan Validation -->
            <div class="result-card" data-modal="structure-validation">
                <div class="card-image-wrapper">
                    <img
                        src="<?php echo get_template_directory_uri(); ?>/assets/torlyai_product_demo_optimized/screenshot-11.webp"
                        alt="Business Plan Structure Validation - 7 Elements Check"
                        class="result-screenshot"
                        loading="lazy"
                    />
                    <div class="zoom-overlay">
                        <span class="zoom-icon">🔍</span>
                        <span class="zoom-text">Click to Zoom</span>
                    </div>
                </div>
                <div class="card-content">
                    <h3 class="card-title">✅ Structure Validation</h3>
                    <p class="card-description">7-element business plan completeness check ensuring you meet all Home Office requirements</p>
                    <ul class="card-features">
                        <li>Element-by-element status</li>
                        <li>Completion percentage</li>
                        <li>Missing components flagged</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lightbox Modal for Screenshots -->
<div id="screenshotModal" class="screenshot-modal">
    <div class="modal-backdrop"></div>
    <div class="modal-content">
        <button class="modal-close" aria-label="Close">&times;</button>
        <div class="modal-image-container">
            <img id="modalImage" src="" alt="Screenshot Preview" />
        </div>
    </div>
</div>

<!-- Application Journey Section -->
<section class="application-journey-section">
    <div class="container">
        <div class="journey-header">
            <h2 class="section-title">Your Complete Application Journey</h2>
            <p class="section-subtitle">AI-powered tools guide you through every step—from assessment to approval in 25-40 hours</p>
        </div>

        <!-- Journey Stages Grid -->
        <div class="journey-stages-grid">
            <!-- Stage 1: Assessment -->
            <div class="journey-stage-card active" data-stage="assessment" data-image="screenshot-12.webp">
                <div class="stage-number">01</div>
                <div class="stage-icon">📊</div>
                <div class="stage-content">
                    <h3 class="stage-title">Assessment</h3>
                    <p class="stage-description">Evaluate eligibility against UK visa criteria</p>
                </div>
                <div class="stage-arrow">→</div>
            </div>

            <!-- Stage 2: Business Plan -->
            <div class="journey-stage-card" data-stage="business-plan" data-image="screenshot-13.webp">
                <div class="stage-number">02</div>
                <div class="stage-icon">📝</div>
                <div class="stage-content">
                    <h3 class="stage-title">Business Plan</h3>
                    <p class="stage-description">Generate comprehensive 20-30 page plan</p>
                </div>
                <div class="stage-arrow">→</div>
            </div>

            <!-- Stage 3: Financial Model -->
            <div class="journey-stage-card" data-stage="financial" data-image="screenshot-14.webp">
                <div class="stage-number">03</div>
                <div class="stage-icon">💰</div>
                <div class="stage-content">
                    <h3 class="stage-title">Financial Model</h3>
                    <p class="stage-description">Build Excel model with 5 sheets and scenarios</p>
                </div>
                <div class="stage-arrow">→</div>
            </div>

            <!-- Stage 4: Compliance -->
            <div class="journey-stage-card" data-stage="compliance" data-image="screenshot-15.webp">
                <div class="stage-number">04</div>
                <div class="stage-icon">✅</div>
                <div class="stage-content">
                    <h3 class="stage-title">Compliance</h3>
                    <p class="stage-description">Validate against Home Office requirements</p>
                </div>
                <div class="stage-arrow">→</div>
            </div>

            <!-- Stage 5: Document Checklist -->
            <div class="journey-stage-card" data-stage="submit" data-image="screenshot-17.webp">
                <div class="stage-number">05</div>
                <div class="stage-icon">📦</div>
                <div class="stage-content">
                    <h3 class="stage-title">Document Checklist</h3>
                    <p class="stage-description">Organize all materials for submission</p>
                </div>
                <div class="stage-arrow">→</div>
            </div>

            <!-- Stage 6: Pitch Deck -->
            <div class="journey-stage-card" data-stage="pitch-deck" data-image="screenshot-16.webp">
                <div class="stage-number">06</div>
                <div class="stage-icon">🎯</div>
                <div class="stage-content">
                    <h3 class="stage-title">Pitch Deck</h3>
                    <p class="stage-description">Create professional PowerPoint presentation</p>
                </div>
            </div>
        </div>

        <!-- Featured Display Viewport -->
        <div class="journey-viewport">
            <div class="viewport-header">
                <h3 id="journeyViewportTitle">Assessment Stage</h3>
                <span id="journeyViewportDuration">30-60 minutes</span>
            </div>
            <div class="journey-display-frame">
                <img
                    id="journeyFeaturedImage"
                    src="<?php echo get_template_directory_uri(); ?>/assets/torlyai_product_demo_optimized/screenshot-12.webp"
                    alt="Assessment Stage - UK Innovator Visa"
                    class="journey-screenshot"
                    loading="lazy"
                />
            </div>
        </div>

        <!-- Dashboard Section -->
        <div class="journey-dashboard">
            <h3 class="dashboard-title">Track Your Progress</h3>
            <p class="dashboard-subtitle">Interactive dashboard with endorsing body selection and real-time progress tracking</p>
            <div class="dashboard-frame">
                <img
                    src="<?php echo get_template_directory_uri(); ?>/assets/torlyai_product_demo_optimized/screenshot-19.webp"
                    alt="Application Dashboard - Progress Tracking"
                    class="dashboard-screenshot"
                    loading="lazy"
                />
            </div>
        </div>

        <!-- Timeline Overview -->
        <div class="timeline-overview">
            <h3 class="overview-title">Recommended 4-Week Timeline</h3>
            <div class="timeline-frame">
                <img
                    src="<?php echo get_template_directory_uri(); ?>/assets/torlyai_product_demo_optimized/screenshot-18.webp"
                    alt="4-Week Timeline - Week by Week Breakdown"
                    class="timeline-screenshot"
                    loading="lazy"
                />
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
// Global variable for waitlist email
let userWaitlistEmail = '';

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
    if (modal && event.target === modal) {
        closeWaitlistModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        if (typeof closeWaitlistModal === 'function') {
            closeWaitlistModal();
        }
    }
});

// Handle form submission
const waitlistForm = document.getElementById('waitlistForm');
if (waitlistForm) {
    waitlistForm.addEventListener('submit', async function(e) {
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
}

// ============================================
// PRODUCT SHOWCASE INTERACTIONS
// ============================================

(function() {
    // State management
    let currentIndex = 0;
    let filteredThumbnails = [];
    let activeCategory = 'all';
    const templateDir = '<?php echo get_template_directory_uri(); ?>';

    // Get all elements
    const featuredImage = document.getElementById('featuredImage');
    const thumbnailItems = Array.from(document.querySelectorAll('.thumbnail-item'));
    const tabButtons = document.querySelectorAll('.tab-btn');
    const indicators = document.querySelectorAll('.indicator');
    const prevBtn = document.querySelector('.nav-prev');
    const nextBtn = document.querySelector('.nav-next');

    // Initialize filteredThumbnails with all thumbnails
    filteredThumbnails = thumbnailItems;

    // Update featured image with fade effect
    function updateFeaturedImage(imageName, index) {
        const imageUrl = `${templateDir}/assets/torlyai_product_demo_optimized/${imageName}`;

        // Fade out
        featuredImage.classList.add('fade-out');

        // Change image after fade out
        setTimeout(() => {
            featuredImage.src = imageUrl;
            currentIndex = index;

            // Update active states
            updateActiveStates();

            // Fade in
            featuredImage.classList.remove('fade-out');

            // Track analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'product_showcase_view', {
                    'event_category': 'engagement',
                    'image_index': index,
                    'category': activeCategory
                });
            }
        }, 250);
    }

    // Update all active states
    function updateActiveStates() {
        // Update thumbnails
        thumbnailItems.forEach((item, i) => {
            item.classList.toggle('active', i === currentIndex);
        });

        // Update indicators
        indicators.forEach((indicator, i) => {
            indicator.classList.toggle('active', i === currentIndex);
        });
    }

    // Filter thumbnails by category
    function filterByCategory(category) {
        activeCategory = category;

        // Update tab buttons
        tabButtons.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.category === category);
        });

        // Filter thumbnails
        if (category === 'all') {
            filteredThumbnails = thumbnailItems;
            thumbnailItems.forEach(item => item.style.display = 'block');
        } else {
            filteredThumbnails = thumbnailItems.filter(item => item.dataset.category === category);
            thumbnailItems.forEach(item => {
                item.style.display = item.dataset.category === category ? 'block' : 'none';
            });
        }

        // Show first item in filtered results
        if (filteredThumbnails.length > 0) {
            const firstVisibleIndex = thumbnailItems.indexOf(filteredThumbnails[0]);
            const imageName = filteredThumbnails[0].dataset.image;
            updateFeaturedImage(imageName, firstVisibleIndex);
        }
    }

    // Navigate to next image
    function navigateNext() {
        const visibleIndices = filteredThumbnails.map(item => thumbnailItems.indexOf(item));
        const currentVisibleIndex = visibleIndices.indexOf(currentIndex);

        if (currentVisibleIndex < visibleIndices.length - 1) {
            const nextIndex = visibleIndices[currentVisibleIndex + 1];
            const imageName = thumbnailItems[nextIndex].dataset.image;
            updateFeaturedImage(imageName, nextIndex);
        } else {
            // Loop back to first
            const firstIndex = visibleIndices[0];
            const imageName = thumbnailItems[firstIndex].dataset.image;
            updateFeaturedImage(imageName, firstIndex);
        }
    }

    // Navigate to previous image
    function navigatePrev() {
        const visibleIndices = filteredThumbnails.map(item => thumbnailItems.indexOf(item));
        const currentVisibleIndex = visibleIndices.indexOf(currentIndex);

        if (currentVisibleIndex > 0) {
            const prevIndex = visibleIndices[currentVisibleIndex - 1];
            const imageName = thumbnailItems[prevIndex].dataset.image;
            updateFeaturedImage(imageName, prevIndex);
        } else {
            // Loop back to last
            const lastIndex = visibleIndices[visibleIndices.length - 1];
            const imageName = thumbnailItems[lastIndex].dataset.image;
            updateFeaturedImage(imageName, lastIndex);
        }
    }

    // Event Listeners

    // Tab button clicks
    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            filterByCategory(btn.dataset.category);
        });
    });

    // Thumbnail clicks
    thumbnailItems.forEach((item, index) => {
        item.addEventListener('click', () => {
            const imageName = item.dataset.image;
            updateFeaturedImage(imageName, index);
        });
    });

    // Indicator clicks
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            const imageName = thumbnailItems[index].dataset.image;
            updateFeaturedImage(imageName, index);
        });
    });

    // Navigation button clicks
    if (prevBtn) {
        prevBtn.addEventListener('click', navigatePrev);
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', navigateNext);
    }

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        // Only activate if showcase is in viewport
        const showcase = document.querySelector('.product-showcase-section');
        if (!showcase) return;

        const rect = showcase.getBoundingClientRect();
        const isInViewport = rect.top < window.innerHeight && rect.bottom > 0;

        if (!isInViewport) return;

        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            navigatePrev();
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            navigateNext();
        }
    });

    // Optional: Auto-play carousel (uncomment to enable)
    /*
    let autoplayInterval = setInterval(() => {
        navigateNext();
    }, 5000); // Change every 5 seconds

    // Pause autoplay on hover
    const showcase = document.querySelector('.product-showcase-section');
    if (showcase) {
        showcase.addEventListener('mouseenter', () => {
            clearInterval(autoplayInterval);
        });

        showcase.addEventListener('mouseleave', () => {
            autoplayInterval = setInterval(() => {
                navigateNext();
            }, 5000);
        });
    }
    */

    // Smooth scroll indicators into view
    function scrollToThumbnail(index) {
        const thumbnail = thumbnailItems[index];
        if (!thumbnail) return;

        const container = document.querySelector('.thumbnails-container');
        const track = document.querySelector('.thumbnails-track');

        if (!container || !track) return;

        const containerWidth = container.offsetWidth;
        const thumbnailLeft = thumbnail.offsetLeft;
        const thumbnailWidth = thumbnail.offsetWidth;
        const scrollLeft = thumbnailLeft - (containerWidth / 2) + (thumbnailWidth / 2);

        track.style.transform = `translateX(-${Math.max(0, scrollLeft)}px)`;
    }

    // Initialize
    updateActiveStates();

})();

// ============================================
// ASSESSMENT RESULTS LIGHTBOX MODAL
// ============================================

(function() {
    const modal = document.getElementById('screenshotModal');
    const modalImage = document.getElementById('modalImage');
    const modalClose = document.querySelector('.modal-close');
    const modalBackdrop = document.querySelector('.modal-backdrop');
    const resultCards = document.querySelectorAll('.result-card');

    // Open modal with screenshot
    resultCards.forEach(card => {
        card.addEventListener('click', () => {
            const img = card.querySelector('.result-screenshot');
            if (img && modal && modalImage) {
                modalImage.src = img.src;
                modalImage.alt = img.alt;
                modal.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent scrolling

                // Track analytics
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'screenshot_zoom', {
                        'event_category': 'engagement',
                        'screenshot': img.alt
                    });
                }
            }
        });
    });

    // Close modal
    function closeModal() {
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = ''; // Re-enable scrolling
        }
    }

    if (modalClose) {
        modalClose.addEventListener('click', closeModal);
    }

    if (modalBackdrop) {
        modalBackdrop.addEventListener('click', closeModal);
    }

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal && modal.classList.contains('active')) {
            closeModal();
        }
    });
})();

// ============================================
// APPLICATION JOURNEY INTERACTIONS
// ============================================

(function() {
    const journeyFeaturedImage = document.getElementById('journeyFeaturedImage');
    const journeyTitle = document.getElementById('journeyViewportTitle');
    const journeyDuration = document.getElementById('journeyViewportDuration');
    const stageCards = document.querySelectorAll('.journey-stage-card');
    const timelineButtons = document.querySelectorAll('.timeline-btn');
    const templateDir = '<?php echo get_template_directory_uri(); ?>';

    // Stage data with titles and durations
    const stageData = {
        'assessment': { title: 'Assessment Stage', duration: '30-60 minutes' },
        'business-plan': { title: 'Business Plan Writer', duration: '4-8 hours' },
        'financial': { title: 'Financial Model Builder', duration: '2-4 hours' },
        'compliance': { title: 'Compliance Checker', duration: '15-30 minutes' },
        'pitch-deck': { title: 'Pitch Deck Creator', duration: '1-2 hours' },
        'submit': { title: 'Document Checklist', duration: '2-4 hours' }
    };

    // Update journey viewport with fade effect
    function updateJourneyDisplay(stageName, imageName) {
        if (!journeyFeaturedImage) return;

        const imageUrl = `${templateDir}/assets/torlyai_product_demo_optimized/${imageName}`;
        const data = stageData[stageName];

        // Fade out
        journeyFeaturedImage.classList.add('fade-out');

        setTimeout(() => {
            journeyFeaturedImage.src = imageUrl;
            if (journeyTitle && data) journeyTitle.textContent = data.title;
            if (journeyDuration && data) journeyDuration.textContent = data.duration;

            // Fade in
            journeyFeaturedImage.classList.remove('fade-out');

            // Track analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'journey_stage_view', {
                    'event_category': 'engagement',
                    'stage': stageName
                });
            }
        }, 250);
    }

    // Update active stage card
    function updateActiveStage(clickedCard) {
        stageCards.forEach(card => card.classList.remove('active'));
        clickedCard.classList.add('active');
    }

    // Stage card click handlers
    stageCards.forEach(card => {
        card.addEventListener('click', () => {
            const stageName = card.dataset.stage;
            const imageName = card.dataset.image;

            updateActiveStage(card);
            updateJourneyDisplay(stageName, imageName);
        });
    });

    // Timeline button click handlers (optional functionality)
    timelineButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active timeline button
            timelineButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const week = btn.dataset.week;

            // Track analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'timeline_week_view', {
                    'event_category': 'engagement',
                    'week': week
                });
            }

            // You can add week-specific filtering logic here if needed
            // For now, it just provides visual feedback
        });
    });

    // Keyboard navigation for journey stages
    document.addEventListener('keydown', (e) => {
        const journeySection = document.querySelector('.application-journey-section');
        if (!journeySection) return;

        const rect = journeySection.getBoundingClientRect();
        const isInViewport = rect.top < window.innerHeight && rect.bottom > 0;

        if (!isInViewport) return;

        const stageArray = Array.from(stageCards);
        const activeIndex = stageArray.findIndex(card => card.classList.contains('active'));

        if (e.key === 'ArrowRight' && activeIndex < stageArray.length - 1) {
            e.preventDefault();
            const nextCard = stageArray[activeIndex + 1];
            const stageName = nextCard.dataset.stage;
            const imageName = nextCard.dataset.image;
            updateActiveStage(nextCard);
            updateJourneyDisplay(stageName, imageName);
        } else if (e.key === 'ArrowLeft' && activeIndex > 0) {
            e.preventDefault();
            const prevCard = stageArray[activeIndex - 1];
            const stageName = prevCard.dataset.stage;
            const imageName = prevCard.dataset.image;
            updateActiveStage(prevCard);
            updateJourneyDisplay(stageName, imageName);
        }
    });
})();

// ============================================
// PREMIUM CUSTOM CURSOR
// ============================================

(function() {
    // Only run on desktop devices
    if (window.matchMedia("(hover: none) and (pointer: coarse)").matches) {
        return;
    }

    // Create cursor elements
    const cursor = document.createElement('div');
    cursor.className = 'custom-cursor';
    document.body.appendChild(cursor);

    const cursorRing = document.createElement('div');
    cursorRing.className = 'custom-cursor-ring';
    document.body.appendChild(cursorRing);

    let mouseX = 0, mouseY = 0;
    let cursorX = 0, cursorY = 0;
    let ringX = 0, ringY = 0;

    // Track mouse movement
    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    // Smooth cursor follow with spring animation
    function animateCursor() {
        // Cursor dot follows immediately
        cursorX += (mouseX - cursorX) * 0.25;
        cursorY += (mouseY - cursorY) * 0.25;

        // Ring follows with delay (spring effect)
        ringX += (mouseX - ringX) * 0.15;
        ringY += (mouseY - ringY) * 0.15;

        cursor.style.transform = `translate(${cursorX - 6}px, ${cursorY - 6}px)`;
        cursorRing.style.transform = `translate(${ringX - 20}px, ${ringY - 20}px)`;

        requestAnimationFrame(animateCursor);
    }

    animateCursor();

    // Add hover class when over interactive elements
    const interactiveElements = document.querySelectorAll('a, button, .btn-primary, .btn-secondary, .thumbnail-item, .result-card, .tab-btn, .timeline-btn, .journey-stage-card');

    interactiveElements.forEach(el => {
        el.addEventListener('mouseenter', () => {
            cursorRing.classList.add('hover');
            cursor.style.transform += ' scale(0.5)';
        });

        el.addEventListener('mouseleave', () => {
            cursorRing.classList.remove('hover');
            cursor.style.transform = cursor.style.transform.replace(' scale(0.5)', '');
        });
    });

    // Hide cursor when leaving window
    document.addEventListener('mouseleave', () => {
        cursor.style.opacity = '0';
        cursorRing.style.opacity = '0';
    });

    document.addEventListener('mouseenter', () => {
        cursor.style.opacity = '1';
        cursorRing.style.opacity = '0.6';
    });

    console.log('✨ Premium custom cursor initialized');
})();

</script>

<?php get_footer(); ?>
