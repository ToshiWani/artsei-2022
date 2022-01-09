<?php

add_action('init', function () {

    //  add menus

    register_nav_menu('primary_menu', 'ページ上部メニュー');
    register_nav_menu('footer_menu', 'ページ下部メニュー');
});


add_action( 'after_setup_theme', function(){
    /*
        * Enable support for Post Thumbnails on posts and pages.
        *
        * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
        */
    add_theme_support( 'post-thumbnails' );
} );


add_action('widgets_init', function(){

    //  add widgets 
    
    register_sidebar([
        'name'          => 'サイトの概要',
        'id'            => 'sidebar-site-summary',
        'description'   => 'このサイトの概要を入力してください',
        'before_widget' => null,
        'after_widget'  => null,
    ]);
});

add_action( 'wp_enqueue_scripts', function($hook){

    $ver  = wp_get_theme()->get( 'Version' );

    //  add header scripts

    wp_enqueue_script('style-js', get_theme_file_uri('/assets/style.js'), array(), $ver, false);

    //  add footer scripts
    
    $deps = array('jquery');
    $in_footer = true;
    
    wp_enqueue_script('jquery');
    wp_enqueue_script('scrollex-js', get_theme_file_uri('/assets/js/jquery.scrollex.min.js'), $deps, $ver, $in_footer);
    wp_enqueue_script('scrolly-js', get_theme_file_uri('/assets/js/jquery.scrolly.min.js'), $deps, $ver, $in_footer);
    wp_enqueue_script('selectorr-js', get_theme_file_uri('/assets/js/jquery.selectorr.min.js'), $deps, $ver, $in_footer);
    wp_enqueue_script('browser-js', get_theme_file_uri('/assets/js/browser.min.js'), $deps, $ver, $in_footer);
    wp_enqueue_script('breakpoints-js', get_theme_file_uri('/assets/js/breakpoints.min.js'), $deps, $ver, $in_footer);
    wp_enqueue_script('util-js', get_theme_file_uri('/assets/js/util.js'), $deps, $ver, $in_footer);
    wp_enqueue_script('main-js', get_theme_file_uri('/assets/js/main.js'), $deps, $ver, $in_footer);

    //  fetch list of banner images

    global $wpdb;
    $query = "select p.ID as post_id
    from wp_term_taxonomy tt
    inner join wp_terms t on t.term_id = tt.term_id
    inner join `wp_term_relationships` tr on tr.term_taxonomy_id = tt.term_taxonomy_id
    inner join wp_posts p on p.`ID` = tr.object_id
    where tt.`taxonomy` = 'media_folder' and t.name = '_top_page';";

    $results = $wpdb->get_results($query);

    $img_list = [];
    foreach($results as $img){
        $key = wp_get_attachment_image_url($img->post_id, 'large');
        $img_list[$key] = 'center';
    }

    $data = 'const bannerSettings =';
    $data .= json_encode(array(
        'delay' => 6000,
        'images' => $img_list,
    ));

    wp_add_inline_script('main-js', $data, 'before');
});
