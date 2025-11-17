<?php
/**
 * Template Name: Blog Post Template
 * Description: SEO-optimized template for TorlyAI blog posts
 */

get_header(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class('torlyai-blog-post'); ?>>

    <!-- Schema.org structured data for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BlogPosting",
        "headline": "<?php echo esc_js(get_the_title()); ?>",
        "description": "<?php echo esc_js(get_the_excerpt()); ?>",
        "image": "<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>",
        "author": {
            "@type": "Person",
            "name": "<?php echo esc_js(get_the_author()); ?>"
        },
        "publisher": {
            "@type": "Organization",
            "name": "TorlyAI",
            "logo": {
                "@type": "ImageObject",
                "url": "<?php echo esc_url(get_site_icon_url()); ?>"
            }
        },
        "datePublished": "<?php echo get_the_date('c'); ?>",
        "dateModified": "<?php echo get_the_modified_date('c'); ?>",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "<?php echo esc_url(get_permalink()); ?>"
        }
    }
    </script>

    <!-- Featured Image -->
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-featured-image">
            <?php the_post_thumbnail('large', array('alt' => get_the_title())); ?>
        </div>
    <?php endif; ?>

    <!-- Post Header -->
    <header class="post-header">
        <h1 class="post-title"><?php the_title(); ?></h1>

        <div class="post-meta">
            <span class="post-date">
                <time datetime="<?php echo get_the_date('c'); ?>">
                    <?php echo get_the_date(); ?>
                </time>
            </span>

            <span class="post-author">
                By <?php the_author(); ?>
            </span>

            <?php if (get_the_category()) : ?>
                <span class="post-categories">
                    <?php the_category(', '); ?>
                </span>
            <?php endif; ?>
        </div>
    </header>

    <!-- Post Content -->
    <div class="post-content">
        <?php the_content(); ?>

        <?php
        wp_link_pages(array(
            'before' => '<div class="page-links">Pages:',
            'after'  => '</div>',
        ));
        ?>
    </div>

    <!-- Post Tags -->
    <?php if (has_tag()) : ?>
        <div class="post-tags">
            <strong>Tags:</strong> <?php the_tags('', ', ', ''); ?>
        </div>
    <?php endif; ?>

    <!-- Author Bio -->
    <div class="author-bio">
        <div class="author-avatar">
            <?php echo get_avatar(get_the_author_meta('ID'), 80); ?>
        </div>
        <div class="author-info">
            <h3>About <?php the_author(); ?></h3>
            <p><?php echo get_the_author_meta('description'); ?></p>
        </div>
    </div>

    <!-- Related Posts -->
    <?php
    $categories = get_the_category();
    if ($categories) {
        $category_ids = array();
        foreach ($categories as $category) {
            $category_ids[] = $category->term_id;
        }

        $args = array(
            'category__in' => $category_ids,
            'post__not_in' => array(get_the_ID()),
            'posts_per_page' => 3,
            'orderby' => 'rand',
        );

        $related_posts = new WP_Query($args);

        if ($related_posts->have_posts()) :
    ?>
        <div class="related-posts">
            <h3>Related Articles</h3>
            <div class="related-posts-grid">
                <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                    <div class="related-post-item">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium'); ?>
                            </a>
                        <?php endif; ?>
                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php
        endif;
        wp_reset_postdata();
    }
    ?>

    <!-- Social Sharing -->
    <div class="social-sharing">
        <h4>Share this article:</h4>
        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>"
           target="_blank"
           rel="noopener noreferrer"
           class="share-twitter">
            Share on Twitter
        </a>
        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>"
           target="_blank"
           rel="noopener noreferrer"
           class="share-linkedin">
            Share on LinkedIn
        </a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
           target="_blank"
           rel="noopener noreferrer"
           class="share-facebook">
            Share on Facebook
        </a>
    </div>

    <!-- Comments -->
    <?php
    if (comments_open() || get_comments_number()) :
        comments_template();
    endif;
    ?>

</article>

<style>
/* Blog Post Styling */
.torlyai-blog-post {
    max-width: 800px;
    margin: 0 auto;
    padding: 40px 20px;
}

.post-featured-image {
    margin-bottom: 30px;
}

.post-featured-image img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}

.post-header {
    margin-bottom: 40px;
}

.post-title {
    font-size: 2.5em;
    line-height: 1.2;
    margin-bottom: 20px;
    color: #1a1a1a;
}

.post-meta {
    font-size: 0.9em;
    color: #666;
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.post-content {
    font-size: 1.1em;
    line-height: 1.8;
    color: #333;
    margin-bottom: 40px;
}

.post-content h2, .post-content h3 {
    margin-top: 40px;
    margin-bottom: 20px;
}

.post-content img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
}

.post-tags {
    margin: 30px 0;
    padding: 20px;
    background: #f5f5f5;
    border-radius: 4px;
}

.author-bio {
    display: flex;
    gap: 20px;
    padding: 30px;
    background: #f9f9f9;
    border-radius: 8px;
    margin: 40px 0;
}

.author-avatar img {
    border-radius: 50%;
}

.author-info h3 {
    margin-top: 0;
}

.related-posts {
    margin: 50px 0;
}

.related-posts h3 {
    margin-bottom: 20px;
}

.related-posts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.related-post-item {
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s;
}

.related-post-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.related-post-item img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.related-post-item h4 {
    padding: 15px;
    margin: 0;
}

.related-post-item p {
    padding: 0 15px 15px;
    margin: 0;
    color: #666;
}

.social-sharing {
    margin: 40px 0;
    padding: 20px;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
}

.social-sharing h4 {
    margin-bottom: 15px;
}

.social-sharing a {
    display: inline-block;
    margin-right: 10px;
    padding: 10px 20px;
    background: #0073aa;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background 0.3s;
}

.social-sharing a:hover {
    background: #005a87;
}

.share-twitter { background: #1DA1F2; }
.share-linkedin { background: #0077B5; }
.share-facebook { background: #1877F2; }

@media (max-width: 768px) {
    .post-title {
        font-size: 2em;
    }

    .author-bio {
        flex-direction: column;
        text-align: center;
    }

    .social-sharing a {
        display: block;
        margin: 10px 0;
    }
}
</style>

<?php get_footer(); ?>
