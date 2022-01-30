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
            $query = "select t.name as term_name, p.ID as post_id, pm.meta_value as priority
                from wp_term_taxonomy tt
                inner join wp_terms t on t.term_id = tt.term_id
                inner join `wp_term_relationships` tr on tr.term_taxonomy_id = tt.term_taxonomy_id
                inner join wp_posts p on p.`ID` = tr.object_id
                left join wp_postmeta pm on pm.post_id = p.ID and pm.meta_key = 'priority'
                where tt.`taxonomy` = 'media_folder' and t.name not like '\_%'
                order by pm.meta_value desc";

            $results = $wpdb->get_results($query);
            $by_category = [];
            foreach($results as $row) {
                $by_category[$row->term_name][] = $row;
            }
            ?>
            <!-- render all images -->
            <li>
                <h3>全て表示</h3>
                <div class="gallery">
                    <?php foreach ($results as $row) :?>
                        <?php echo $row->priority; ?>
                        <a href="<?php echo wp_get_attachment_image_url($row->post_id, 'large') ?>">
                            <img loading="lazy" src="<?php echo wp_get_attachment_image_url($row->post_id, 'medium_large') ?>">
                        </a>
                    <?php endforeach; ?>
                </div>
            </li>
            <!-- render images by category -->
            <?php foreach ($by_category as $category_name => $img_list) : ?>
                <li>
                    <h3><?php echo $category_name; ?></h3>
                    <div class="gallery">
                        <?php foreach ($img_list as $row) : ?>
                            <a href="<?php echo wp_get_attachment_image_url($row->post_id, 'large') ?>">
                                <img loading="lazy" src="<?php echo wp_get_attachment_image_url($row->post_id, 'medium_large') ?>">
                            </a>
                        <?php endforeach; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>


<?php get_footer(); ?>