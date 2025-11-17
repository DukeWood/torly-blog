<?php
/**
 * The main template file
 *
 * @package TorlyAI
 */

get_header(); ?>

<main class="site-main">
    <div class="container">
        <div class="content-area">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
                    get_template_part('templates/content', get_post_type());
                endwhile;

                the_posts_navigation();
            else :
                ?>
                <section class="no-results not-found">
                    <header class="page-header">
                        <h1 class="page-title">Nothing Found</h1>
                    </header>
                    <div class="page-content">
                        <p>It seems we can't find what you're looking for. Perhaps searching can help.</p>
                        <?php get_search_form(); ?>
                    </div>
                </section>
                <?php
            endif;
            ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
