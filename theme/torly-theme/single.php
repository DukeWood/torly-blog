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
    "@type": "Person",
    "name": "<?php the_author(); ?>",
    "url": "https://torly.ai/about"
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

<?php
// Auto-generate FAQ schema from H2/H3 question-like headings
$content_raw = get_the_content();
preg_match_all('/<h[23][^>]*>(.*?)<\/h[23]>/i', $content_raw, $heading_matches);
$faq_items = array();
if (!empty($heading_matches[1])) {
    foreach ($heading_matches[1] as $heading) {
        $clean = strip_tags($heading);
        // Only include headings that look like questions
        if (preg_match('/\?$|^(how|what|why|when|where|which|can|do|does|is|are|will|should)/i', $clean)) {
            // Get the paragraph after this heading
            $pos = strpos($content_raw, $heading);
            if ($pos !== false) {
                $after = substr($content_raw, $pos + strlen($heading));
                if (preg_match('/<p[^>]*>(.*?)<\/p>/is', $after, $p_match)) {
                    $answer = strip_tags($p_match[1]);
                    if (strlen($answer) > 50) {
                        $faq_items[] = array(
                            '@type' => 'Question',
                            'name' => $clean,
                            'acceptedAnswer' => array(
                                '@type' => 'Answer',
                                'text' => wp_trim_words($answer, 80)
                            )
                        );
                    }
                }
            }
        }
    }
}
if (!empty($faq_items)) : ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": <?php echo json_encode(array_slice($faq_items, 0, 5)); ?>
}
</script>
<?php endif; ?>

<div class="torly-editorial">
<main class="torly-editorial-post single-post-content" role="main">
    <?php while (have_posts()) : the_post();
        $content = get_post_field('post_content', get_the_ID());
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / 200);
        $categories = get_the_category();
        $cat_name = !empty($categories) ? $categories[0]->name : '';
    ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('ed-post'); ?>>
            <!-- Editorial hero: eyebrow + serif title + lede + byline -->
            <div class="ed-post-inner">
                <header class="ed-post-hero">
                    <p class="ed-post-eyebrow">
                        <?php if ($cat_name) : ?>
                            <span><?php echo esc_html($cat_name); ?></span>
                            <span class="ed-dot">·</span>
                        <?php endif; ?>
                        <span class="ed-dot"><?php echo get_the_date(); ?></span>
                    </p>

                    <h1 class="ed-post-title"><?php the_title(); ?></h1>

                    <?php $excerpt = get_the_excerpt(); if ($excerpt) : ?>
                        <p class="ed-post-lede"><?php echo esc_html($excerpt); ?></p>
                    <?php endif; ?>

                    <div class="ed-post-byline">
                        <?php
                        $author_id = get_the_author_meta('ID');
                        $avatar_url = get_template_directory_uri() . '/assets/maggie-portrait.jpeg';
                        if ($author_id == 2) : ?>
                            <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php the_author(); ?>" width="40" height="40" />
                        <?php else :
                            echo get_avatar($author_id, 40, '', '', array('class' => 'ed-byline-avatar'));
                        endif; ?>
                        <span class="ed-byline-stack">
                            <span class="ed-byline-name"><?php the_author(); ?></span>
                            <span class="ed-byline-meta">
                                <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                                <span class="ed-sep">·</span>
                                <?php echo (int)$reading_time; ?> min read
                            </span>
                        </span>
                    </div>
                </header>
            </div>

            <?php if (has_post_thumbnail()) : ?>
                <div class="ed-post-featured">
                    <?php the_post_thumbnail('large', array('alt' => esc_attr(get_the_title()))); ?>
                </div>
            <?php endif; ?>

            <!-- Body — the_content() renders WP blocks; editorial CSS styles them -->
            <div class="ed-post-body">
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </div>

            <!-- Share row -->
            <div class="ed-post-share">
                <p class="ed-post-share-label">Share this article</p>
                <div class="ed-share-buttons">
                    <a href="https://x.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on X">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        X
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on LinkedIn">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452z"/></svg>
                        LinkedIn
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>" aria-label="Share via email">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        Email
                    </a>
                    <button class="share-copy" title="Copy link" onclick="copyArticleLink(event)" type="button">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                        <span>Copy link</span>
                    </button>
                </div>
            </div>
        </article>

        <?php
        // Related posts (3-card strip)
        if ($categories) {
            $category_ids = wp_list_pluck($categories, 'term_id');
            $related_query = new WP_Query(array(
                'category__in' => $category_ids,
                'post__not_in' => array($post->ID),
                'posts_per_page' => 3,
                'orderby' => 'rand',
            ));
            if ($related_query->have_posts()) : ?>
                <section class="ed-related" aria-labelledby="ed-related-heading">
                    <div class="ed-related-header">
                        <p class="ed-related-kicker">Keep reading</p>
                        <h2 id="ed-related-heading" class="ed-related-title">More on <em>UK Innovator Visa.</em></h2>
                    </div>
                    <div class="ed-related-grid">
                        <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                            <a class="ed-related-card" href="<?php the_permalink(); ?>">
                                <span class="ed-related-date"><?php echo get_the_date(); ?></span>
                                <h3><?php the_title(); ?></h3>
                            </a>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </section>
            <?php endif;
        } ?>

        <!-- Newsletter CTA (editorial dark band) -->
        <section class="ed-newsletter">
            <p class="ed-newsletter-kicker">Subscribe</p>
            <h3>Stay close to the <em>UK Innovator Visa.</em></h3>
            <p>Expert insights on UK Innovator Founder Visa delivered straight to your inbox — no fluff, no spam.</p>
            <form class="newsletter-form ed-newsletter-form" action="<?php echo esc_url(home_url('/wp-json/torlyai/v1/newsletter-signup')); ?>" method="post">
                <input type="email" name="email" placeholder="you@company.com" required />
                <button type="submit" class="newsletter-btn">
                    Subscribe
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
            </form>
            <p class="ed-newsletter-privacy">We respect your privacy · Unsubscribe anytime</p>
        </section>

        <!-- Previous / next post nav -->
        <nav class="ed-post-nav" aria-label="Post navigation">
            <div>
                <?php if (get_previous_post()) : ?>
                    <span class="ed-nav-label">← Previous</span>
                    <?php previous_post_link('%link', '%title'); ?>
                <?php endif; ?>
            </div>
            <div class="ed-post-nav-next">
                <?php if (get_next_post()) : ?>
                    <span class="ed-nav-label">Next →</span>
                    <?php next_post_link('%link', '%title'); ?>
                <?php endif; ?>
            </div>
        </nav>
    <?php endwhile; ?>
</main>
</div>


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

    document.querySelectorAll('.blog-card').forEach(card => {
        observer.observe(card);
    });
});

// Copy link functionality
function copyArticleLink(event) {
    event.preventDefault();
    const url = window.location.href;
    const button = event.currentTarget;

    navigator.clipboard.writeText(url).then(() => {
        const spanElement = button.querySelector('span');
        const originalText = spanElement.textContent;
        spanElement.textContent = 'Copied!';
        button.classList.add('copied');

        setTimeout(() => {
            spanElement.textContent = originalText;
            button.classList.remove('copied');
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}

// Newsletter form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.newsletter-form');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const emailInput = form.querySelector('input[name="email"]');
            const email = emailInput.value;
            const button = form.querySelector('.newsletter-btn');
            const buttonText = button.childNodes[0];
            const originalText = buttonText.textContent;

            buttonText.textContent = 'Subscribing...';
            button.disabled = true;

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email })
                });

                if (response.ok) {
                    buttonText.textContent = 'Subscribed! ✓';
                    emailInput.value = '';
                    setTimeout(() => {
                        buttonText.textContent = originalText;
                        button.disabled = false;
                    }, 3000);
                } else {
                    throw new Error('Subscription failed');
                }
            } catch (error) {
                buttonText.textContent = 'Try again';
                setTimeout(() => {
                    buttonText.textContent = originalText;
                    button.disabled = false;
                }, 2000);
            }
        });
    }
});
</script>

<?php get_footer(); ?>
