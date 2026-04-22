<?php
/**
 * Blog Posts Index Template
 * Used for displaying blog posts at /blog/
 *
 * @package TorlyAI
 */

get_header(); ?>

<main class="site-main blog-index">
    <div class="container">
        <!-- Blog Header -->
        <div class="blog-header">
            <h1 class="page-title">TorlyAI Blog</h1>
            <p class="page-description">Expert insights on UK Innovator Visa, business immigration, and entrepreneurship</p>
        </div>

        <!-- Blog Posts Grid -->
        <div class="blog-posts-container">
            <?php
            if (have_posts()) :
                echo '<div class="blog-grid">';

                while (have_posts()) : the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('blog-card'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="blog-thumbnail-link">
                                <?php the_post_thumbnail('large', array('class' => 'blog-thumbnail')); ?>
                            </a>
                        <?php endif; ?>

                        <div class="blog-content">
                            <div class="blog-meta">
                                <span class="blog-date">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    <?php echo get_the_date(); ?>
                                </span>
                                <?php
                                $categories = get_the_category();
                                if ($categories) :
                                ?>
                                    <span class="blog-category">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                        </svg>
                                        <?php echo esc_html($categories[0]->name); ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <h2 class="blog-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>

                            <div class="blog-excerpt">
                                <?php
                                if (has_excerpt()) {
                                    echo wp_trim_words(get_the_excerpt(), 25);
                                } else {
                                    echo wp_trim_words(get_the_content(), 25);
                                }
                                ?>
                            </div>

                            <a href="<?php the_permalink(); ?>" class="read-more">
                                Read More
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </a>
                        </div>
                    </article>
                    <?php
                endwhile;

                echo '</div>';

                // Pagination
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => '← Previous',
                    'next_text' => 'Next →',
                    'class' => 'blog-pagination'
                ));

            else :
                ?>
                <div class="no-posts">
                    <h2>No posts found</h2>
                    <p>Check back soon for new content!</p>
                </div>
                <?php
            endif;
            ?>
        </div>
    </div>
</main>

<?php // Enhanced blog-redesign styles are enqueued via torlyai_enqueue_scripts()
      // (functions.php, conditional on is_home()/is_archive()). Keeps asset
      // cache-busting consistent with $theme_version. ?>

<style>
/* ============================================
   BLOG INDEX — Editorial Design
   Inspired by Linear/Stripe blogs
   ============================================ */

.blog-index {
    padding: 0;
    background: var(--bg-primary, #ffffff);
    min-height: 100vh;
}

/* --- Header --- */
.blog-header {
    text-align: center;
    margin-bottom: 2rem;
    padding: clamp(2.5rem, 6vw, 4.5rem) 0 clamp(1.5rem, 3vw, 2.5rem);
    position: relative;
}

.blog-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, var(--color-chat-green, #10b981), #34d399);
    border-radius: 2px;
}

.blog-header .page-title {
    font-family: var(--font-body) !important;
    font-size: clamp(2rem, 4.5vw, 3rem);
    font-weight: 800;
    margin-bottom: 0.75rem;
    color: var(--text-primary, #000000);
    line-height: 1.1;
    letter-spacing: -0.03em;
}

.blog-header .page-description {
    font-size: clamp(0.9375rem, 1.8vw, 1.0625rem);
    color: var(--text-tertiary, #9ca3af);
    max-width: 480px;
    margin: 0 auto;
    line-height: 1.6;
}

/* --- Grid --- */
.blog-posts-container {
    margin-top: 1rem;
    padding-bottom: 3rem;
}

.blog-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
    margin-bottom: 3rem;
}

@media (min-width: 640px) {
    .blog-grid { grid-template-columns: repeat(2, 1fr); gap: 1.25rem; }
}

@media (min-width: 1024px) {
    .blog-grid { grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
}

/* --- Cards --- */
.blog-card {
    background: var(--bg-primary, #ffffff);
    border: 1px solid var(--border-color, #e5e7eb);
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    display: flex;
    flex-direction: column;
}

.blog-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.1), 0 2px 6px rgba(0, 0, 0, 0.04);
    border-color: var(--color-chat-green, #10b981);
}

/* Card image */
.blog-thumbnail-link {
    display: block;
    overflow: hidden;
    height: 200px;
    background: var(--neutral-100, #f3f4f6);
}

.blog-thumbnail {
    width: 100% !important;
    height: 100% !important;
    max-width: none !important;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.blog-card:hover .blog-thumbnail {
    transform: scale(1.04);
}

/* Card content */
.blog-content {
    padding: 1.25rem 1.5rem 1.5rem;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.blog-meta {
    display: flex;
    gap: 0.625rem;
    margin-bottom: 0.625rem;
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 0.01em;
}

.blog-meta span {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: var(--text-tertiary, #9ca3af);
}

.blog-meta svg {
    width: 13px;
    height: 13px;
    opacity: 0.5;
}

.blog-category {
    color: var(--color-chat-green, #10b981) !important;
    background: rgba(16, 185, 129, 0.08);
    padding: 0.15rem 0.5rem;
    border-radius: 4px;
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.blog-title {
    font-family: var(--font-body) !important;
    font-size: 1.125rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    line-height: 1.35;
    color: var(--text-primary, #000000) !important;
    letter-spacing: -0.01em;
}

.blog-title a {
    color: inherit !important;
    text-decoration: none;
    transition: color 0.15s ease;
}

.blog-title a:hover {
    color: var(--color-chat-green, #10b981) !important;
}

.blog-excerpt {
    color: var(--text-secondary, #6b7280) !important;
    font-size: 0.875rem;
    line-height: 1.6;
    margin-bottom: 1.25rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}

.read-more {
    color: var(--color-chat-green, #10b981);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.8125rem;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    transition: gap 0.2s ease, opacity 0.2s ease;
    margin-top: auto;
}

.read-more:hover { gap: 0.5rem; }

.read-more svg { width: 15px; height: 15px; }

/* --- Pagination --- */
.blog-pagination {
    display: flex;
    justify-content: center;
    gap: 0.375rem;
    margin-top: 3rem;
    margin-bottom: 1rem;
}

.blog-pagination .nav-links {
    display: flex;
    gap: 0.375rem;
}

.blog-pagination a,
.blog-pagination span {
    padding: 0.5rem 0.875rem;
    background: var(--bg-primary, #ffffff);
    border: 1px solid var(--border-color, #e5e7eb);
    border-radius: 8px;
    text-decoration: none;
    color: var(--text-primary, #000000);
    font-size: 0.8125rem;
    font-weight: 500;
    transition: all 0.15s ease;
}

.blog-pagination a:hover {
    border-color: var(--color-chat-green, #10b981);
    color: var(--color-chat-green, #10b981);
}

.blog-pagination .current {
    background: var(--color-chat-green, #10b981);
    color: #ffffff;
    border-color: var(--color-chat-green, #10b981);
}

/* --- Empty state --- */
.no-posts {
    text-align: center;
    padding: 5rem 2rem;
}

.no-posts h2 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--text-primary, #000000);
}

.no-posts p {
    color: var(--text-secondary, #6b7280);
}

/* --- Dark mode overrides (inline = high specificity) --- */
[data-theme="dark"] .blog-index {
    background: #0a0a0a;
}

[data-theme="dark"] .blog-card {
    background: #141414;
    border-color: rgba(255, 255, 255, 0.06);
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

[data-theme="dark"] .blog-card:hover {
    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(52, 211, 153, 0.15);
    border-color: rgba(52, 211, 153, 0.25);
}

[data-theme="dark"] .blog-thumbnail-link {
    background: #1a1a1a;
}

[data-theme="dark"] .blog-title,
[data-theme="dark"] .blog-title a {
    color: rgba(255, 255, 255, 0.95) !important;
}

[data-theme="dark"] .blog-title a:hover {
    color: #34d399 !important;
}

[data-theme="dark"] .blog-excerpt {
    color: rgba(255, 255, 255, 0.55) !important;
}

[data-theme="dark"] .blog-meta span {
    color: rgba(255, 255, 255, 0.35);
}

[data-theme="dark"] .blog-category {
    background: rgba(52, 211, 153, 0.12) !important;
    color: #34d399 !important;
}

[data-theme="dark"] .read-more {
    color: #34d399;
}

[data-theme="dark"] .blog-pagination a,
[data-theme="dark"] .blog-pagination span {
    background: #141414;
    border-color: rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.7);
}

[data-theme="dark"] .blog-pagination a:hover {
    border-color: rgba(52, 211, 153, 0.3);
    color: #34d399;
}

[data-theme="dark"] .blog-pagination .current {
    background: #34d399;
    color: #0a0a0a;
    border-color: #34d399;
}

[data-theme="dark"] .blog-header .page-title {
    color: #ffffff;
}

[data-theme="dark"] .blog-header .page-description {
    color: rgba(255, 255, 255, 0.4);
}

/* --- Scroll animation (JS adds .animate-in, then .visible) --- */
.blog-card.animate-in {
    opacity: 0;
    transform: translateY(16px);
    transition: opacity 0.5s ease, transform 0.5s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.blog-card.animate-in.visible {
    opacity: 1;
    transform: translateY(0);
}

/* --- Mobile --- */
@media (max-width: 639px) {
    .blog-content { padding: 1rem 1.25rem 1.25rem; }
    .blog-title { font-size: 1.0625rem; }
    .blog-excerpt { -webkit-line-clamp: 2; font-size: 0.8125rem; }
}
</style>

<script>
// Scroll animations for blog cards
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    document.querySelectorAll('.blog-card').forEach(card => {
        card.classList.add('animate-in');
        observer.observe(card);
    });
});
</script>

<?php get_footer(); ?>
