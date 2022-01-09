<?php 
/**
 * The template for rendering the site’s front page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#front-page-display
 */
get_header();?>


<!-- Banner -->
<section id="banner">
    <div class="inner">
        <h1><?php the_field('banner-header-text');?></h1>
        <div class="content">
            <p><?php the_field('banner-slogan-text');?></p>
            <ul class="actions special">
                <li>
                    <a href="<?php echo get_field('banner-button-link'); ?>" class="button large next">
                        <?php the_field('banner-button-text') ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</section>



<!-- 新着情報 -->
<?php
    //  query the last five new posts

    $the_query = new WP_Query([
        'post_type' => ['post', 'page'],
        'post_status' => 'publish',
        'posts_per_page' => 5,
    ]);
?>
<?php if ($the_query->have_posts()): ?>
    <section id="main" class="wrapper style2">
        <div class="inner">
            <div class="row">
                <div class="col-12">
                    <h3>新着情報</h3>
                    <ul class="alt">
                        <?php while ($the_query->have_posts()): $the_query->the_post();?>
                            <?php $slug = get_post_field( 'post_name', get_post()); ?>
                            <?php if ($slug != 'front-page'): ?>
                                <li>
                                    <span><?php the_time('Y年n月j日');?>：　</span>
                                    <strong><a href="<?php the_permalink()?>"><?php the_title();?></a></strong>
                                    <span>を更新しました</span>
                                </li>
                            <?php endif; ?>
                        <?php endwhile;?>
                        <?php wp_reset_query(); ?>
                    </ul>
                    <ul class="actions">
                        <li>
                            <a href="<?php echo get_field('whats-new-button-link'); ?>" class="button next">
                                最新ニュースへ
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <?php wp_reset_postdata();?>
<?php endif;?>





<?php get_footer();?>