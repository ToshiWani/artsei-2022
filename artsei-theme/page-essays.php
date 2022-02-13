<?php 
/**
 * Template for the blog posts index
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#front-page-display
 */

get_header();?>

<!-- Main -->
<section id="main" class="wrapper style2">
    <div class="inner">
        <header class="major special">
            <h1><?php echo the_title(); ?></h1>
        </header>

        <!-- Eye catch image --> 

        <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
        <?php if ($image) : ?>
            <span class="image fit">
                <img loading="lazy"  src="<?php echo $image[0]; ?>" />
            </span>
        <?php endif; ?> 


        <?php 
            global $wpdb;
            $query = "select ID as post_id, p.post_title
            from wp_posts p 
            where p.`post_type` = 'post' and p.`post_status`='publish'
            order by p.post_date_gmt desc";

            $results = $wpdb->get_results($query);
            $post_rows = null;
            if($results) {
                $post_rows = array_chunk($results, 4);
            }
        ?>

        <?php if ($post_rows): ?>
            <?php foreach($post_rows as $row): ?>
                <div class="row aln-center">
                    <?php foreach($row as $col): ?>
                        <div class="col-3 col-6-small col-12-xsmall" style="padding: 0.512rem 0.64rem 0.512rem;">
                            <a class="button fit" style="cursor: pointer;" href="<?php echo get_permalink($col->post_id); ?>"><?php echo $col->post_title ?></a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php //the_content(); ?>
        
    </div>
</section>


<?php get_footer(); ?>