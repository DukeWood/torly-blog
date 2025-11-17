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
      "url": "<?php echo get_template_directory_uri(); ?>/assets/torly-logo.svg"
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
    padding: 4rem 0;
    background: #ffffff;
}

.single-post-content .container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

.entry-header {
    margin-bottom: 2rem;
}

.entry-title {
    font-size: 2.5rem;
    line-height: 1.2;
    color: #1F2937;
    margin-bottom: 1rem;
}

.entry-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    color: #6B7280;
    font-size: 0.95rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #E5E7EB;
}

.entry-meta span {
    display: flex;
    align-items: center;
}

.entry-meta a {
    color: var(--primary-color);
    text-decoration: none;
}

.entry-meta a:hover {
    text-decoration: underline;
}

.post-thumbnail {
    margin: 2rem 0;
    border-radius: 8px;
    overflow: hidden;
}

.post-thumbnail img {
    width: 100%;
    height: auto;
    display: block;
}

.entry-content {
    font-size: 1.125rem;
    line-height: 1.8;
    color: #374151;
}

.entry-content h2 {
    font-size: 2rem;
    margin: 2.5rem 0 1rem;
    color: #1F2937;
}

.entry-content h3 {
    font-size: 1.5rem;
    margin: 2rem 0 1rem;
    color: #1F2937;
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
    background: #F3F4F6;
    border-left: 4px solid var(--primary-color);
    font-style: italic;
}

.entry-content blockquote p {
    margin: 0;
}

.entry-content strong {
    font-weight: 600;
    color: #1F2937;
}

.entry-footer {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 2px solid #E5E7EB;
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
    padding: 0.5rem 1rem;
    background: var(--primary-color);
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 0.95rem;
    transition: background 0.3s ease;
}

.share-buttons a:hover {
    background: #E55A2A;
}

.share-twitter { background: #1DA1F2 !important; }
.share-linkedin { background: #0A66C2 !important; }
.share-facebook { background: #1877F2 !important; }

.related-posts {
    margin-top: 4rem;
    padding-top: 3rem;
    border-top: 2px solid #E5E7EB;
}

.related-posts h2 {
    font-size: 2rem;
    margin-bottom: 2rem;
    color: #1F2937;
}

.related-posts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.related-post-card {
    background: #F9FAFB;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.related-post-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
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
    color: #1F2937;
    text-decoration: none;
}

.related-post-card h3 a:hover {
    color: var(--primary-color);
}

.related-post-card .excerpt {
    margin: 0 1rem 1rem;
    color: #6B7280;
    font-size: 0.95rem;
}

.related-post-card .read-more {
    display: inline-block;
    margin: 0 1rem 1rem;
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
}

.related-post-card .read-more:hover {
    text-decoration: underline;
}

.post-navigation {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 2px solid #E5E7EB;
    display: flex;
    justify-content: space-between;
    gap: 2rem;
}

.post-navigation a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
}

.post-navigation a:hover {
    text-decoration: underline;
}

.nav-previous {
    text-align: left;
}

.nav-next {
    text-align: right;
}

@media (max-width: 768px) {
    .entry-title {
        font-size: 2rem;
    }

    .entry-content {
        font-size: 1rem;
    }

    .entry-content h2 {
        font-size: 1.5rem;
    }

    .entry-content h3 {
        font-size: 1.25rem;
    }

    .share-buttons {
        flex-direction: column;
        align-items: flex-start;
    }

    .share-buttons h3 {
        margin-right: 0;
        margin-bottom: 0.5rem;
    }

    .post-navigation {
        flex-direction: column;
    }

    .nav-next {
        text-align: left;
    }
}
</style>

<?php get_footer(); ?>
