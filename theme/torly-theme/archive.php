<?php
/**
 * Archive Template (Category, Tag, Author, Date archives)
 * Used for displaying blog post archives
 *
 * @package TorlyAI
 */

get_header(); ?>

<main class="site-main archive-page">
    <div class="container">
        <!-- Archive Header -->
        <header class="archive-header">
            <?php
            if (is_category()) :
                $category = get_queried_object();
                ?>
                <span class="archive-type">Category</span>
                <h1 class="archive-title"><?php single_cat_title(); ?></h1>
                <?php if ($category->description) : ?>
                    <p class="archive-description"><?php echo esc_html($category->description); ?></p>
                <?php endif;
            elseif (is_tag()) :
                $tag = get_queried_object();
                ?>
                <span class="archive-type">Tag</span>
                <h1 class="archive-title"><?php single_tag_title(); ?></h1>
                <?php if ($tag->description) : ?>
                    <p class="archive-description"><?php echo esc_html($tag->description); ?></p>
                <?php endif;
            elseif (is_author()) :
                ?>
                <span class="archive-type">Author</span>
                <h1 class="archive-title"><?php the_author(); ?></h1>
                <p class="archive-description">Posts by <?php the_author(); ?></p>
                <?php
            elseif (is_date()) :
                ?>
                <span class="archive-type">Archive</span>
                <h1 class="archive-title"><?php echo get_the_date('F Y'); ?></h1>
                <?php
            else :
                ?>
                <span class="archive-type">Archive</span>
                <h1 class="archive-title">Blog Archive</h1>
                <?php
            endif;
            ?>
        </header>

        <!-- Archive Posts Grid -->
        <div class="archive-posts-container">
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
                                if ($categories && !is_category()) :
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
                    <p>There are no posts in this archive yet.</p>
                    <a href="<?php echo home_url('/blog/'); ?>" class="btn-primary">View All Posts</a>
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
/* Archive Page - Clean, content-focused styles */
.archive-page {
    padding: 0;
    background: var(--bg-primary, #ffffff);
}

.archive-header {
    text-align: center;
    margin-bottom: 3rem;
    padding: clamp(3rem, 6vw, 5rem) 0 clamp(2rem, 4vw, 3rem);
}

.archive-type {
    display: inline-block;
    font-size: 0.8125rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--color-chat-green, #10b981);
    background: rgba(16, 185, 129, 0.08);
    padding: 0.375rem 0.875rem;
    border-radius: 9999px;
    margin-bottom: 1rem;
}

.archive-title {
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 800;
    margin-bottom: 0.75rem;
    color: var(--text-primary, #000000);
    line-height: 1.1;
    letter-spacing: -0.02em;
}

.archive-description {
    font-size: clamp(1rem, 2vw, 1.125rem);
    color: var(--text-secondary, #6b7280);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.archive-posts-container {
    padding-bottom: 2rem;
}

.blog-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
    margin-bottom: 3rem;
}

@media (min-width: 768px) {
    .blog-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
}

@media (min-width: 1024px) {
    .blog-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
}

.blog-card {
    background: var(--white, #ffffff);
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}

.blog-card.animate-in {
    opacity: 0;
    transform: translateY(20px);
}

.blog-card.animate-in.visible {
    opacity: 1;
    transform: translateY(0);
}

.blog-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
    border-color: rgba(0, 0, 0, 0.12);
}

.blog-thumbnail-link {
    display: block;
    overflow: hidden;
    height: 200px;
}

.blog-thumbnail {
    width: 100% !important;
    height: 100% !important;
    max-width: none !important;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.blog-card:hover .blog-thumbnail {
    transform: scale(1.03);
}

.blog-content {
    padding: 1.25rem 1.5rem 1.5rem;
}

.blog-meta {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    color: var(--text-secondary, #6b7280);
    font-size: 0.8125rem;
}

.blog-meta span {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.blog-meta svg {
    width: 14px;
    height: 14px;
    opacity: 0.6;
}

.blog-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    line-height: 1.3;
    color: var(--text-primary, #000000) !important;
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
    font-size: 0.9375rem;
    line-height: 1.6;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.read-more {
    color: var(--color-chat-green, #10b981);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    transition: gap 0.2s ease;
}

.read-more:hover {
    gap: 0.5rem;
}

.read-more svg {
    width: 16px;
    height: 16px;
}

.blog-pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 3rem;
    margin-bottom: 1rem;
}

.blog-pagination .nav-links {
    display: flex;
    gap: 0.5rem;
}

.blog-pagination a,
.blog-pagination span {
    padding: 0.5rem 1rem;
    background: var(--white, #ffffff);
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    text-decoration: none;
    color: var(--text-primary, #000000);
    font-size: 0.875rem;
    font-weight: 500;
    transition: background 0.15s ease, border-color 0.15s ease;
}

.blog-pagination a:hover {
    background: #f9fafb;
    border-color: #d1d5db;
}

.blog-pagination .current {
    background: var(--color-chat-green, #10b981);
    color: #ffffff;
    border-color: var(--color-chat-green, #10b981);
}

.no-posts {
    text-align: center;
    padding: 4rem 2rem;
    max-width: 500px;
    margin: 0 auto;
}

.no-posts h2 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    color: var(--text-primary, #000000);
}

.no-posts p {
    color: var(--text-secondary, #6b7280);
    margin-bottom: 1.5rem;
    font-size: 1rem;
}

.no-posts .btn-primary {
    display: inline-flex;
    align-items: center;
    padding: 0.625rem 1.5rem;
    background: var(--color-chat-green, #10b981);
    color: #ffffff;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9375rem;
    transition: background 0.15s ease;
}

.no-posts .btn-primary:hover {
    background: #059669;
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
