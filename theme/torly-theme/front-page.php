<?php
/**
 * Front Page Template
 * 
 * @package TorlyAI
 */

get_header(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Your AI-Powered UK Innovator Visa Assistant</h1>
            <p class="hero-subtitle">Streamline Your Visa Application with Intelligent Automation</p>
            <p class="hero-description">
                TorlyAI leverages cutting-edge artificial intelligence to guide you through every step of the UK Innovator Founder Visa process. 
                From initial assessment to final submission, our AI agents work 24/7 to ensure your application meets all requirements.
            </p>
            <div class="hero-buttons">
                <a href="<?php echo home_url('/visa-assessment'); ?>" class="btn-primary">Start Free Assessment</a>
                <a href="<?php echo home_url('/blog'); ?>" class="btn-secondary">Read Our Blog</a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <h2 class="section-title">Comprehensive Visa Support</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="feature-title">Instant Eligibility Check</h3>
                <p class="feature-description">
                    Our AI analyzes your business against Home Office criteria and endorsing body requirements in minutes.
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
                    Generate comprehensive 12-section business plans tailored to your chosen endorsing body's requirements.
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
                    Build Excel models with endorsing body-specific emphasis on R&D, scenarios, or revenue projections.
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
                    Comprehensive checklist with endorsing body-specific requirements for complete submission packages.
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
                    Generate PowerPoint presentations optimized for your endorsing body's interview requirements.
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
                    Validate against Home Office criteria AND endorsing body-specific requirements to maximize approval.
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

<!-- Recent Blog Posts -->
<section class="blog-section">
    <div class="container">
        <h2 class="section-title">Latest Visa Insights</h2>
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
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/default-blog.jpg" alt="Blog post" class="blog-thumbnail">
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
                <p>No blog posts found. Start creating content!</p>
            <?php endif; ?>
        </div>
        
        <div style="text-align: center; margin-top: 3rem;">
            <a href="<?php echo home_url('/blog'); ?>" class="btn-primary">View All Blog Posts</a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Start Your UK Innovation Journey?</h2>
            <p>Join thousands of entrepreneurs who've successfully navigated the visa process with TorlyAI</p>
            <div class="cta-buttons">
                <a href="<?php echo home_url('/get-started'); ?>" class="btn-primary">Get Started Free</a>
                <a href="<?php echo home_url('/contact'); ?>" class="btn-secondary">Talk to an Expert</a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">95%</div>
                <div class="stat-label">Success Rate</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">2,000+</div>
                <div class="stat-label">Applications Processed</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">48hrs</div>
                <div class="stat-label">Average Processing Time</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">AI Support Available</div>
            </div>
        </div>
    </div>
</section>

<!-- Additional Styles for this page -->
<style>
/* How It Works Section */
.how-it-works-section {
    padding: 5rem 0;
    background: linear-gradient(135deg, #f3f4f6 0%, #ffffff 100%);
}

.process-timeline {
    display: flex;
    justify-content: space-between;
    max-width: 900px;
    margin: 0 auto;
    position: relative;
}

.process-timeline::before {
    content: '';
    position: absolute;
    top: 30px;
    left: 10%;
    right: 10%;
    height: 2px;
    background: #e5e7eb;
    z-index: 1;
}

.process-step {
    text-align: center;
    flex: 1;
    position: relative;
    z-index: 2;
}

.step-number {
    width: 60px;
    height: 60px;
    background: var(--primary-color);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 auto 1rem;
}

.process-step h3 {
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
}

.process-step p {
    color: var(--text-secondary);
    font-size: 0.95rem;
}

/* CTA Section */
.cta-section {
    padding: 5rem 0;
    background: var(--primary-color);
    color: white;
    text-align: center;
}

.cta-content h2 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.cta-content p {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.cta-section .btn-primary {
    background: white;
    color: var(--primary-color);
}

.cta-section .btn-secondary {
    border-color: white;
    color: white;
}

/* Stats Section */
.stats-section {
    padding: 4rem 0;
    background: var(--bg-secondary);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    text-align: center;
}

.stat-number {
    font-size: 3rem;
    font-weight: 700;
    color: var(--primary-color);
}

.stat-label {
    font-size: 1.125rem;
    color: var(--text-secondary);
}

@media (max-width: 768px) {
    .process-timeline {
        flex-direction: column;
        gap: 2rem;
    }
    
    .process-timeline::before {
        display: none;
    }
    
    .cta-buttons {
        flex-direction: column;
    }
}
</style>

<?php get_footer(); ?>