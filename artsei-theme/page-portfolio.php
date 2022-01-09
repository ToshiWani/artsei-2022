<?php get_header();?>

<!-- Main -->
<section id="main" class="wrapper style2">
    <div class="inner">
        <header class="major special">
            <h1><?php echo the_title(); ?></h1>
        </header>

        <?php the_content(); ?>

        <ul class="tabs">

        <?php
            global $wpdb;
            $query = "select t.name, p.ID
                        from wp_term_taxonomy tt
                        inner join wp_terms t on t.term_id = tt.term_id
                        inner join `wp_term_relationships` tr on tr.term_taxonomy_id = tt.term_taxonomy_id
                        inner join wp_posts p on p.`ID` = tr.object_id
                        where tt.`taxonomy` = 'media_folder' and t.name not like '\_%'";

            $results = $wpdb->get_results($query);
            $image_list = array();

            foreach ($results as $row) {
                $image_list[$row->name][] = $row->ID;
            }
            ?>
            <!-- render all images -->
            <li>
                <h3>全て表示</h3>
                <div class="gallery">
                    <?php foreach ($image_list as $category_name => $img_id_list) :?>
                        <?php foreach ($img_id_list as $imgid) : ?>
                            <a href="<?php echo wp_get_attachment_image_url($imgid, 'large') ?>">
                                <img loading="lazy" src="<?php echo wp_get_attachment_image_url($imgid, 'medium_large') ?>">
                            </a>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </li>
            <!-- render images by category -->
            <?php foreach ($image_list as $category_name => $img_id_list) : ?>
                <li>
                    <h3><?php echo $category_name; ?></h3>
                    <div class="gallery">
                        <?php foreach ($img_id_list as $imgid) : ?>
                            <a href="<?php echo wp_get_attachment_image_url($imgid, 'large') ?>">
                                <img loading="lazy" src="<?php echo wp_get_attachment_image_url($imgid, 'medium_large') ?>">
                            </a>
                        <?php endforeach; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>


<?php get_footer(); ?>