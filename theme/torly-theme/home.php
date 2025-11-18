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
                                <?php the_post_thumbnail('medium', array('class' => 'blog-thumbnail')); ?>
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

<style>
.blog-index {
    padding: 0;
    background: var(--white);
    background-image:
        radial-gradient(at 53% 20%, hsla(60,100%,50%,0.25) 0px, transparent 50%),
        radial-gradient(at 80% 60%, hsla(108,100%,50%,0.2) 0px, transparent 50%),
        radial-gradient(at 20% 80%, hsla(30,100%,50%,0.15) 0px, transparent 50%);
}

.blog-header {
    text-align: center;
    margin-bottom: 4rem;
    padding: clamp(4rem, 8vw, 6rem) 0 clamp(3rem, 6vw, 4rem);
}

.blog-header .page-title {
    font-size: clamp(2.25rem, 5vw, 3.5rem);
    font-weight: 800;
    margin-bottom: 1.5rem;
    color: var(--text-primary);
    line-height: 0.95;
    letter-spacing: -0.02em;
}

.blog-header .page-description {
    font-size: clamp(1rem, 2.5vw, 1.25rem);
    color: var(--text-secondary);
    max-width: 700px;
    margin: 0 auto;
    line-height: 1.6;
}

.blog-posts-container {
    margin-top: 2rem;
    padding-bottom: clamp(3rem, 6vw, 5rem);
}

.blog-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    margin-bottom: 3rem;
}

@media (min-width: 768px) {
    .blog-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .blog-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.blog-card {
    background: var(--white);
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 1rem;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    opacity: 0;
    transform: translateY(20px);
}

.blog-card.visible {
    opacity: 1;
    transform: translateY(0);
}

.blog-card:hover {
    transform: translateY(-8px) scale(1.01);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border-color: rgba(0, 0, 0, 0.1);
}

.blog-thumbnail-link {
    display: block;
    overflow: hidden;
}

.blog-thumbnail {
    width: 100%;
    height: 220px;
    object-fit: cover;
    transition: transform 0.3s;
}

.blog-card:hover .blog-thumbnail {
    transform: scale(1.05);
}

.blog-content {
    padding: 1.5rem;
}

.blog-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.blog-meta span {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.blog-meta svg {
    width: 16px;
    height: 16px;
}

.blog-title {
    font-size: clamp(1.125rem, 2vw, 1.375rem);
    font-weight: 700;
    margin-bottom: 0.75rem;
    line-height: 1.3;
    color: var(--text-primary);
}

.blog-title a {
    color: var(--text-primary);
    text-decoration: none;
    transition: color 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.blog-title a:hover {
    color: var(--color-chat-green);
}

.blog-excerpt {
    color: var(--text-secondary);
    line-height: 1.6;
    margin-bottom: 1rem;
}

.read-more {
    color: var(--text-primary);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9375rem;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.read-more:hover {
    gap: 0.5rem;
    color: var(--color-chat-green);
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
}

.blog-pagination .nav-links {
    display: flex;
    gap: 0.5rem;
}

.blog-pagination a,
.blog-pagination span {
    padding: 0.5rem 1rem;
    background: var(--bg-primary);
    border-radius: 0.5rem;
    text-decoration: none;
    color: var(--text-primary);
    transition: all 0.3s;
}

.blog-pagination a:hover {
    background: var(--primary-color);
    color: white;
}

.blog-pagination .current {
    background: var(--primary-color);
    color: white;
}

.no-posts {
    text-align: center;
    padding: 4rem 2rem;
}

.no-posts h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: var(--text-primary);
}

.no-posts p {
    color: var(--text-secondary);
}

@media (max-width: 768px) {
    .blog-index {
        background-image:
            radial-gradient(at 53% 30%, hsla(60,100%,50%,0.2) 0px, transparent 50%),
            radial-gradient(at 80% 70%, hsla(108,100%,50%,0.15) 0px, transparent 50%);
    }
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
        observer.observe(card);
    });
});
</script>

<?php get_footer(); ?>
