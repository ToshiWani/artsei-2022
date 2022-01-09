<?php
/**
 * The header.
 *
 * This is the template that displays all of the <head> section and everything up until main.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */
?>

<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>
	<title><?php the_title(); echo ' | '; bloginfo( 'name' ); ?></title>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<?php wp_head(); ?>
</head>

<body <?php body_class('is-preload'); ?>>
<?php wp_body_open(); ?>

<!-- Page wrapper -->
<div id="page-wrapper">


<!-- Header -->
<header id="header" class="<?php echo is_front_page() ? 'alt' : ''; ?>" >
	<span class="logo">
		<a href="<?php echo get_home_url(); ?>"><?php bloginfo( 'name' ); ?></a>
	</span>
	<a href="#menu"><span>Menu</span></a>
</header>


<!-- Nav -->
<nav id="menu">
	<div class="inner">
		<h2>Menu</h2>
		<ul class="links">
			<?php
				$menuParameters = array(
					'container'       => false,
					'echo'            => false,
					'items_wrap'      => '%3$s',
				);

				echo strip_tags(wp_nav_menu($menuParameters), '<li><a>' );
			?>
		</ul>
		<a class="close"><span>閉じる</span></a>
	</div>
</nav>