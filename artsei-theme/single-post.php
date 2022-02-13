<?php 
/**
 * The template file for blog pages
 *
 * This is derived from index.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

get_header();?>

<!-- Main -->
<section id="main" class="wrapper style2">
    <div class="inner">
        <header class="major special">
            <h1><?php echo the_title(); ?></h1>
            <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
            <?php if ($image) : ?>
                <span class="image fit">
                    <img loading="lazy"  src="<?php echo $image[0]; ?>" />
                </span>
            <?php endif; ?> 
        </header>

        <?php the_content(); ?>

        <?php if (in_category("essay")) : ?>
            <a class="button next" style="cursor: pointer;" href="<?php echo get_permalink( get_page_by_path( 'essays' ) ) ?>">
                一覧へ戻る
            </a>
        <?php endif; ?>
    </div>
</section>


<?php get_footer(); ?>