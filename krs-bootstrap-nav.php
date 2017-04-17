<?php
/**
 * Plugin Name: Bootstrap 3 Navbar Menu
 * Plugin URI: http://karskiy.com
 * Description: Заготовка в виде плагина для последующей интергации Bootstrap3 Navbar в тему WordPress.
 * Version: 1.0.0
 * Author: Evgeniy Karskiy
 * Author URI: http://karskiy.com
 * License: GPL2
 */
 
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/** Следует добавить в шаблон темы и некоторые параметры изменить под свои нужды
<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand visible-xs" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
		</div>
		<?php wp_nav_menu( array( 
			'theme_location' => 'primary', 
			'menu_id' => 'primary-menu',
			'container' => 'div',
			'container_class' => 'collapse navbar-collapse',
			'container_id' => 'bs-example-navbar-collapse-1',
			'menu_class' => 'nav navbar-nav',
			'depth' => '2',
			'fallback_cb' => '__return_empty_string',
			'walker' => new KRS_Walker_Nav_Menu()
		) ); ?>
	</div><!-- /.container-fluid -->
</nav><!-- #site-navigation -->	
 */

/**
 * Наследуем и кастомизируем класс для выпадающего под-меню
 */
class KRS_Walker_Nav_Menu extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"dropdown-menu\">\n";
	}
}

/**
 * Добавляем к элементу особый класс, если у него есть дочерние
 */
function krs_secondary_menu_classes( $classes, $item, $args ) {	
    if ( isset( $args->walker->has_children ) && $args->walker->has_children === true ) {
        $classes[] = 'dropdown';
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'krs_secondary_menu_classes', 10, 3 ); 

/**
 * Добавляем к ссылкам различные атрибуты
 */
function krs_add_specific_menu_atts( $atts, $item, $args ) {
    // проверяем машинное имя меню и наличие дочерних эл.
    if( $args->theme_location == 'primary' && isset( $args->walker->has_children ) && $args->walker->has_children === true ) {
		// собираем массив с атрибутами:
		$atts['class'] = 'dropdown-toggle';
		$atts['data-toggle'] = 'dropdown';
		$atts['role'] = 'button';
		$atts['aria-haspopup'] = 'true';
		$atts['aria-expanded'] = 'false';
	  
		$item->title = $item->title . ' <span class="caret"></span>';
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'krs_add_specific_menu_atts', 10, 3 );
