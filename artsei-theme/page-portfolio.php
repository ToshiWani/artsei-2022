<?php get_header();?>

<?php
    // fetch gallery images

    global $wpdb;
    $query = "select t.name as term_name, p.ID as post_id, pm.meta_value as priority
        from wp_term_taxonomy tt
        inner join wp_terms t on t.term_id = tt.term_id
        inner join `wp_term_relationships` tr on tr.term_taxonomy_id = tt.term_taxonomy_id
        inner join wp_posts p on p.`ID` = tr.object_id
        left join wp_postmeta pm on pm.post_id = p.ID and pm.meta_key = 'priority'
        where tt.`taxonomy` = 'media_folder' and t.name not like '\_%'
        order by pm.meta_value desc";

    $results = $wpdb->get_results($query);
    $images = [];
    $images_by_category = [];

    foreach($results as $row) {

        //  fetch thumbnails 

        $image = wp_get_attachment_image_src( $row->post_id, 'medium' ); 
        list( $url, $width, $height ) = $image;

        //  if thumbnails is too small, fetch bigger size

        if($height < 300) {
            $image = wp_get_attachment_image_src( $row->post_id, 'large' ); 
            list( $url, $width, $height ) = $image;
        }

        //  set properies

        $img_obj = new stdClass();
        $img_obj->width = $width;
        $img_obj->height = $height;
        $img_obj->preview = $url;
        $img_obj->full = wp_get_attachment_image_url( $row->post_id, 'full'); 

        // push to the arrays

        $images[] = $img_obj;
        $images_by_category[$row->term_name][] = $img_obj;
    }
?>

<!-- Main -->
<section id="main" class="wrapper style2">
    <div class="inner">
        <header class="major special">
            <h1><?php echo the_title(); ?></h1>
        </header>

        <?php the_content(); ?>

        <ul class="tabs">
            <!-- render all images -->
            <li>
                <h3>全て表示</h3>
                <div class="gallery">
                    <?php foreach ($images as $img) :?>
                        <a href="<?php echo $img->full; ?>">
                            <img loading="lazy"
                                data-w="<?php echo $img->width; ?>"
                                data-h="<?php echo $img->height; ?>"
                                src="<?php echo $img->preview; ?>" />
                        </a>
                    <?php endforeach; ?>
                </div>
            </li>
            <?php foreach ($images_by_category as $category_name => $images) : ?>
                <li>
                    <h3><?php echo $category_name; ?></h3>
                    <div class="gallery">
                    <?php foreach ($images as $img) :?>
                        <a href="<?php echo $img->full; ?>">
                            <img loading="lazy"
                                data-w="<?php echo $img->width; ?>"
                                data-h="<?php echo $img->height; ?>"
                                src="<?php echo $img->preview; ?>" />
                        </a>
                    <?php endforeach; ?>
                </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>


<?php get_footer(); ?>