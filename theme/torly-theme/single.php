<?php
/**
 * Single Post Template
 *
 * @package TorlyAI
 */

get_header(); ?>

<!-- Schema.org JSON-LD Structured Data for SEO -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "<?php echo esc_js(get_the_title()); ?>",
  "description": "<?php echo esc_js(get_the_excerpt()); ?>",
  "image": "<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>",
  "datePublished": "<?php echo get_the_date('c'); ?>",
  "dateModified": "<?php echo get_the_modified_date('c'); ?>",
  "author": {
    "@type": "Organization",
    "name": "TorlyAI",
    "url": "https://torly.ai"
  },
  "publisher": {
    "@type": "Organization",
    "name": "TorlyAI",
    "url": "https://torly.ai",
    "logo": {
      "@type": "ImageObject",
      "url": "<?php echo get_template_directory_uri(); ?>/assets/torlyai-logo.png"
    }
  },
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "<?php echo esc_url(get_permalink()); ?>"
  },
  "keywords": "<?php echo esc_js(implode(', ', wp_get_post_tags(get_the_ID(), array('fields' => 'names')))); ?>, UK Innovator Visa, UK Immigration",
  "articleSection": "<?php echo esc_js(implode(', ', wp_get_post_categories(get_the_ID(), array('fields' => 'names')))); ?>",
  "inLanguage": "en-GB",
  "about": {
    "@type": "Thing",
    "name": "UK Innovator Visa"
  },
  "mentions": [
    {
      "@type": "Place",
      "name": "United Kingdom",
      "address": {
        "@type": "PostalAddress",
        "addressCountry": "GB"
      }
    }
  ]
}
</script>

<main class="single-post-content">
    <div class="container">
        <?php
        while (have_posts()) : the_post();
        ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>

                    <div class="entry-meta">
                        <span class="posted-on">
                            <time class="entry-date published" datetime="<?php echo get_the_date('c'); ?>">
                                <?php echo get_the_date(); ?>
                            </time>
                        </span>

                        <span class="categories-list">
                            <?php the_category(', '); ?>
                        </span>

                        <?php if (get_the_tags()) : ?>
                            <span class="tags-list">
                                <?php the_tags('Tags: ', ', ', ''); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <footer class="entry-footer">
                    <div class="share-buttons">
                        <h3>Share this article:</h3>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share-twitter">
                            Share on Twitter
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share-linkedin">
                            Share on LinkedIn
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="share-facebook">
                            Share on Facebook
                        </a>
                    </div>
                </footer>
            </article>

            <?php
            // Related posts
            $categories = get_the_category();
            if ($categories) {
                $category_ids = array();
                foreach($categories as $category) {
                    $category_ids[] = $category->term_id;
                }

                $related_args = array(
                    'category__in' => $category_ids,
                    'post__not_in' => array($post->ID),
                    'posts_per_page' => 3,
                    'orderby' => 'rand'
                );

                $related_query = new WP_Query($related_args);

                if ($related_query->have_posts()) :
            ?>
                <section class="related-posts">
                    <h2>Related Articles</h2>
                    <div class="related-posts-grid">
                        <?php
                        while ($related_query->have_posts()) : $related_query->the_post();
                        ?>
                            <article class="related-post-card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                <?php endif; ?>

                                <h3>
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>

                                <div class="excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                </div>

                                <a href="<?php the_permalink(); ?>" class="read-more">Read More →</a>
                            </article>
                        <?php
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                </section>
            <?php
                endif;
            }
            ?>

            <div class="post-navigation">
                <div class="nav-previous">
                    <?php previous_post_link('%link', '← %title'); ?>
                </div>
                <div class="nav-next">
                    <?php next_post_link('%link', '%title →'); ?>
                </div>
            </div>

        <?php endwhile; ?>
    </div>
</main>

<style>
/* Single Post Styles */
.single-post-content {
    padding: clamp(3rem, 6vw, 5rem) 0;
    background: var(--white);
    background-image:
        radial-gradient(at 80% 20%, hsla(60,100%,50%,0.15) 0px, transparent 50%),
        radial-gradient(at 20% 80%, hsla(108,100%,50%,0.12) 0px, transparent 50%);
}

.single-post-content .container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

.entry-header {
    margin-bottom: 2.5rem;
}

.entry-title {
    font-size: clamp(2rem, 4vw, 3rem);
    line-height: 1.1;
    color: var(--text-primary);
    font-weight: 800;
    letter-spacing: -0.015em;
    margin-bottom: 1.5rem;
}

.entry-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    color: var(--text-tertiary);
    font-size: 0.9375rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border-color);
}

.entry-meta span {
    display: flex;
    align-items: center;
}

.entry-meta a {
    color: var(--color-chat-green);
    text-decoration: none;
    transition: color 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.entry-meta a:hover {
    color: var(--text-primary);
}

.post-thumbnail {
    margin: 2rem 0;
    border-radius: 1rem;
    overflow: hidden;
}

.post-thumbnail img {
    width: 100%;
    height: auto;
    display: block;
}

.entry-content {
    font-size: clamp(1rem, 1.5vw, 1.125rem);
    line-height: 1.8;
    color: var(--text-primary);
}

.entry-content h2 {
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 700;
    margin: 2.5rem 0 1rem;
    color: var(--text-primary);
    letter-spacing: -0.01em;
}

.entry-content h3 {
    font-size: clamp(1.25rem, 2.5vw, 1.5rem);
    font-weight: 700;
    margin: 2rem 0 1rem;
    color: var(--text-primary);
}

.entry-content p {
    margin-bottom: 1.5rem;
}

.entry-content ul,
.entry-content ol {
    margin: 1.5rem 0;
    padding-left: 2rem;
}

.entry-content li {
    margin-bottom: 0.75rem;
}

.entry-content blockquote {
    margin: 2rem 0;
    padding: 1.5rem;
    background: var(--neutral-50);
    border-left: 4px solid var(--color-chat-green);
    border-radius: 0 0.75rem 0.75rem 0;
    font-style: italic;
}

.entry-content blockquote p {
    margin: 0;
}

.entry-content strong {
    font-weight: 600;
    color: var(--text-primary);
}

.entry-footer {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-color);
}

.share-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: center;
}

.share-buttons h3 {
    font-size: 1.125rem;
    margin: 0;
    margin-right: 1rem;
}

.share-buttons a {
    padding: 0.625rem 1.25rem;
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    color: var(--text-primary);
    text-decoration: none;
    border-radius: 9999px;
    border: 1px solid rgba(0, 0, 0, 0.1);
    font-size: 0.9375rem;
    font-weight: 600;
    transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateZ(0);
}

.share-buttons a:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: scale(1.02) translateZ(0);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.share-twitter:hover { background: rgba(29, 161, 242, 0.1) !important; color: #1DA1F2 !important; }
.share-linkedin:hover { background: rgba(10, 102, 194, 0.1) !important; color: #0A66C2 !important; }
.share-facebook:hover { background: rgba(24, 119, 242, 0.1) !important; color: #1877F2 !important; }

.related-posts {
    margin-top: 4rem;
    padding-top: 3rem;
    border-top: 1px solid var(--border-color);
}

.related-posts h2 {
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 700;
    margin-bottom: 2rem;
    color: var(--text-primary);
}

.related-posts-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
}

@media (min-width: 768px) {
    .related-posts-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .related-posts-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.related-post-card {
    background: var(--white);
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 1rem;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.related-post-card:hover {
    transform: translateY(-5px) scale(1.01);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    border-color: rgba(0, 0, 0, 0.1);
}

.related-post-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.related-post-card h3 {
    font-size: 1.25rem;
    margin: 1rem;
    line-height: 1.4;
}

.related-post-card h3 a {
    color: var(--text-primary);
    text-decoration: none;
    transition: color 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.related-post-card h3 a:hover {
    color: var(--color-chat-green);
}

.related-post-card .excerpt {
    margin: 0 1rem 1rem;
    color: var(--text-secondary);
    font-size: 0.9375rem;
    line-height: 1.6;
}

.related-post-card .read-more {
    display: inline-block;
    margin: 0 1rem 1rem;
    color: var(--text-primary);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.875rem;
    transition: color 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.related-post-card .read-more:hover {
    color: var(--color-chat-green);
}

.post-navigation {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    gap: 2rem;
}

.post-navigation a {
    color: var(--text-primary);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.post-navigation a:hover {
    color: var(--color-chat-green);
}

.nav-previous {
    text-align: left;
}

.nav-next {
    text-align: right;
}

@media (max-width: 768px) {
    .share-buttons {
        flex-direction: column;
        align-items: flex-start;
    }

    .share-buttons h3 {
        margin-right: 0;
        margin-bottom: 0.75rem;
    }

    .share-buttons a {
        width: 100%;
        text-align: center;
        justify-content: center;
    }

    .post-navigation {
        flex-direction: column;
    }

    .nav-next {
        text-align: left;
    }
}
</style>

<script>
// Smooth scroll animations for related posts
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '0';
                entry.target.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    entry.target.style.transition = 'opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), transform 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, 50);
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    document.querySelectorAll('.related-post-card').forEach(card => {
        observer.observe(card);
    });
});
</script>

<?php get_footer(); ?>
