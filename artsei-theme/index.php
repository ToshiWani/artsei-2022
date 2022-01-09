<?php 
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
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
        
    </div>
</section>


<?php get_footer(); ?>