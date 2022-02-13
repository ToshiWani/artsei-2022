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
            $query = "select p.ID as post_id, p.post_title, year(p.post_date) as post_year
            from wp_posts p 
            where p.`post_type` = 'post' and p.`post_status`='publish'
            order by p.post_date_gmt desc";

            $results = $wpdb->get_results($query);
            $post_list = [];

            foreach($results as $item) {
                $post_obj = new stdClass();
                $post_obj->post_id = $item->post_id;
                $post_obj->post_title = $item->post_title;
                $post_obj->url = get_permalink($item->post_id);

                $post_list[$item->post_year][] = $post_obj;
            }
        ?>

        <?php if ($post_list) : ?>
            <?php foreach($post_list as $year => $posts_by_year) : ?>
                <h2 id="<?php echo $year; ?>" style="padding-top: 6rem; margin-top: -6rem;"><?php echo $year; ?>年</h2>
                <?php $row_list = array_chunk($posts_by_year, 4); ?>
                <?php foreach ($row_list as $row) : ?>
                    <?php $row = array_pad($row, 4, false); ?>
                    <div class="row aln-center">
                    <?php foreach($row as $post) : ?>
                        <div class="col-3 col-6-small col-12-xsmall" style="margin-top: 0.512rem; ">
                            <?php if($post) : ?>
                            <a class="button fit" style="cursor: pointer; font-size:0.96rem;" href="<?php echo $post->url; ?>">
                                <?php echo $post->post_title ?>
                            </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; /* end column */ ?>
                    </div>
                <?php endforeach; /* end row */ ?>
                <hr />
            <?php endforeach; /* end year */ ?>
        <?php endif; ?>
    </div>
</section>


<?php get_footer(); ?>