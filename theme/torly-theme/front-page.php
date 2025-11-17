<?php
/**
 * Front Page Template - Granola.ai Inspired
 *
 * @package TorlyAI
 * @version 2.0.0
 */

// For blog.torly.ai subdomain, use the blog listing template
$current_domain = $_SERVER['HTTP_HOST'];
if ($current_domain === 'blog.torly.ai') {
    include(get_template_directory() . '/home.php');
    return;
}

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
                <a href="#visa-assessment" class="btn-primary">Start Free Assessment</a>
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
        <!-- Endorsing Bodies -->
        <div class="logo-item" style="min-width: 150px; text-align: center; font-weight: 600; font-size: 14px;">UKES</div>
        <div class="logo-item" style="min-width: 200px; text-align: center; font-weight: 600; font-size: 14px;">Innovator International</div>
        <div class="logo-item" style="min-width: 150px; text-align: center; font-weight: 600; font-size: 14px;">Envestors</div>
        <div class="logo-item" style="min-width: 150px; text-align: center; font-weight: 600; font-size: 14px;">TechNation</div>
        <div class="logo-item" style="min-width: 180px; text-align: center; font-weight: 600; font-size: 14px;">Global Entrepreneurs</div>
        <div class="logo-item" style="min-width: 150px; text-align: center; font-weight: 600; font-size: 14px;">The Entrepreneurs Network</div>
        <!-- Duplicate for seamless loop -->
        <div class="logo-item" style="min-width: 150px; text-align: center; font-weight: 600; font-size: 14px;">UKES</div>
        <div class="logo-item" style="min-width: 200px; text-align: center; font-weight: 600; font-size: 14px;">Innovator International</div>
        <div class="logo-item" style="min-width: 150px; text-align: center; font-weight: 600; font-size: 14px;">Envestors</div>
        <div class="logo-item" style="min-width: 150px; text-align: center; font-weight: 600; font-size: 14px;">TechNation</div>
        <div class="logo-item" style="min-width: 180px; text-align: center; font-weight: 600; font-size: 14px;">Global Entrepreneurs</div>
        <div class="logo-item" style="min-width: 150px; text-align: center; font-weight: 600; font-size: 14px;">The Entrepreneurs Network</div>
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
            <a href="https://blog.torly.ai/" class="btn-primary">View All Blog Posts</a>
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
                <a href="<?php echo home_url('/visa-assessment'); ?>" class="btn-primary">Start Free Assessment</a>
                <a href="<?php echo home_url('/contact'); ?>" class="btn-secondary">Schedule a Consultation</a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
