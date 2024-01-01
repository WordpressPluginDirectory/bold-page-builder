<?php

class BT_BB_FE {
	public static $elements = array();
	public static $fe_id = -1;
	public static $content;
	public static $sections_arr_search = array();
	public static $editor_active = false;
}

add_action( 'admin_bar_init', 'bt_bb_fe_init' );

function bt_bb_fe_init() {
	if ( ! bt_bb_active_for_post_type_fe() || ( isset( $_GET['preview'] ) && ! isset( $_GET['bt_bb_fe_preview'] ) ) ) {
		return;
	}
	if ( current_user_can( 'edit_pages' ) ) {
		BT_BB_FE::$editor_active = true;
		BT_BB_FE::$elements = apply_filters( 'bt_bb_fe_elements', array(
			'bt_bb_accordion_item' => array(
				'edit_box_selector' => '> .bt_bb_accordion_item_title',
				'params' => array(
					'title'			=> array( 'js_handler' => array( 'target_selector' => '.bt_bb_accordion_item_title', 'type' => 'inner_html' ) ),
				),
			),
			'bt_bb_button' => array(
				'edit_box_selector' => '',
				'params' => array(
					'text' 				=> array( 'js_handler'	=> array( 'target_selector' => '.bt_bb_button_text', 'type' => 'inner_html' ) ),
					'icon' 				=> array(),
					'icon_position' 	=> array( 'ajax_filter' => array( 'class' ) ),
					'align' 			=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
					'url' 				=> array( 'js_handler'	=> array( 'target_selector' => ' > a', 'type' => 'attr', 'attr' => 'href' ) ),
					'target' 			=> array( 'js_handler'	=> array( 'target_selector' => ' > a', 'type' => 'attr', 'attr' => 'target' ) ),
					'size' 				=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
					'color_scheme' 		=> array( 'ajax_filter' => array( 'class', 'style' ) ),
					'font_weight' 		=> array( 'ajax_filter' => array( 'class' ) ),
					'text_transform' 	=> array( 'ajax_filter' => array( 'class' ) ),
					'style' 			=> array( 'ajax_filter' => array( 'class' ) ),
					'shape' 			=> array( 'ajax_filter' => array( 'class' ) ),
					'width' 			=> array( 'ajax_filter' => array( 'class' ) ),
				),
			),
			'bt_bb_column' => array(
				'edit_box_selector' => '',
				'params' => array(
					'background_image'			=> array( 'js_handler' => array( 'target_selector' => '', 'type' => 'background_image' ) ),
					'inner_background_image'	=> array( 'js_handler' => array( 'target_selector' =>  '>div', 'type' => 'background_image' ) ),
				),
				'condition_params' => true,
			),
			'bt_bb_column_inner' => array(
				'edit_box_selector' => '',
				'params' => array(
					'background_image'			=> array( 'js_handler' => array( 'target_selector' => '', 'type' => 'background_image' ) ),
					'inner_background_image'	=> array( 'js_handler' => array( 'target_selector' =>  '.bt_bb_column_content', 'type' => 'background_image' ) ),
				),
				'condition_params' => true,
			),
			'bt_bb_content_slider_item' => array(
				'edit_box_selector' => '',
				'params' => array(
					'image'			=> array( 'js_handler' => array( 'target_selector' => '', 'type' => 'background_image' ) ),
				),
				'condition_params' => true,
			),
			'bt_bb_countdown' => array(
				'edit_box_selector' => '',
				'use_ajax_placeholder' => true,
				'ajax_animate_elements' => true,
				'params' => array(
					'datetime'		=> array( 'js_handler' => array( 'target_selector' => '.btCountdownHolder', 'type' => 'countdown' ) ),
					'size'			=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
				),
			),
			'bt_bb_counter' => array(
				'edit_box_selector' => '',
				'ajax_animate_elements' => true,
				'params' => array(
					'number'		=> array(),
					'size'			=> array( 'ajax_filter' => array( 'class' ) ),
				),
			),
			'bt_bb_custom_menu' => array( 
				'edit_box_selector' => '',
				'params' => array(
					'font_weight'	=> array( 'ajax_filter' => array( 'class' ) ), 
					'direction'		=> array( 'ajax_filter' => array( 'class' ) ), 
				),
			),
			'bt_bb_headline' => array(
				'edit_box_selector' => '',
				'ajax_animate_elements' => true,
				'params' => array(
					'ai_prompt'				    	=> array(),
					'superheadline'					=> array( 'js_handler'  => array( 'target_selector' => '.bt_bb_headline_superheadline', 'type' => 'inner_html' ) ),
					'headline'						=> array( 'js_handler'  => array( 'target_selector' => '.bt_bb_headline_content', 'type' => 'inner_html_nl2br' ) ),
					'subheadline'					=> array( 'js_handler'  => array( 'target_selector' => '.bt_bb_headline_subheadline', 'type' => 'inner_html_nl2br' ) ),
					'size'							=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
					'align'							=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
					'dash'							=> array( 'ajax_filter' => array( 'class' ) ), 
					'color_scheme'					=> array( 'ajax_filter' => array( 'class', 'style' ) ), 
					'font_weight'					=> array( 'ajax_filter' => array( 'class' ) ),  
					'text_transform'				=> array( 'ajax_filter' => array( 'class' ) ), 
					'superheadline_font_weight'		=> array( 'ajax_filter' => array( 'class' ) ), 
					'superheadline_text_transform'	=> array( 'ajax_filter' => array( 'class' ) ),  
					'subheadline_font_weight'		=> array( 'ajax_filter' => array( 'class' ) ),
					'subheadline_text_transform'	=> array( 'ajax_filter' => array( 'class' ) ), 
					'url'							=> array( 'js_handler'  => array( 'target_selector' => '.bt_bb_headline_content > span > a', 'type' => 'attr', 'attr' => 'href' ) ),
					'target'						=> array( 'js_handler'  => array( 'target_selector' => '.bt_bb_headline_content > span > a', 'type' => 'attr', 'attr' => 'target' ) ),
				),
			),
			'bt_bb_icon' => array(
				'edit_box_selector' => '',
				'params' => array(
					'icon'			=> array(),
					'colored_icon'	=> array(),
					'text'			=> array( 'js_handler'  => array( 'target_selector' => '.bt_bb_icon_holder > span', 'type' => 'inner_html' ) ),
					'url'			=> array( 'js_handler'  => array( 'target_selector' => 'a.bt_bb_icon_holder', 'type' => 'attr', 'attr' => 'href' ) ),
					'url_title'		=> array( 'js_handler'  => array( 'target_selector' => 'a.bt_bb_icon_holder', 'type' => 'attr', 'attr' => 'title' ) ),
					'target'		=> array( 'js_handler'  => array( 'target_selector' => 'a.bt_bb_icon_holder', 'type' => 'attr', 'attr' => 'target' ) ),
					'align'			=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
					'size'			=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
					'color_scheme'	=> array( 'ajax_filter' => array( 'class', 'style' ) ), 
					'style'			=> array( 'ajax_filter' => array( 'class' ) ), 
					'shape'			=> array( 'ajax_filter' => array( 'class' ) ), 
				),
			),
			'bt_bb_image' => array(
				'edit_box_selector' => '',
				'use_ajax_placeholder' => true,
				'params' => array(
					'image'				=> array(),
					'caption'			=> array( 'js_handler'  => array( 'target_selector' => ' > a, > a > img', 'type' => 'attr', 'attr' => 'title' ) ),
					'size'				=> array( 'ajax_filter' => array( 'class' ) ),
					'shape'				=> array( 'ajax_filter' => array( 'class' ) ),
					'align'				=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
					'url'				=> array( 'js_handler'  => array( 'target_selector' => ' > a', 'type' => 'attr', 'attr' => 'href' ) ),
					'target'			=> array( 'js_handler'  => array( 'target_selector' => ' > a', 'type' => 'attr', 'attr' => 'target' ) ),
					'hover_style'		=> array( 'ajax_filter' => array( 'class' ) ),
					'content_display'	=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
					'content_align'		=> array( 'ajax_filter' => array( 'class' ) ),
				),
			),
			'bt_bb_latest_posts' => array(
				'edit_box_selector' => '',
				'params' => array(
					'gap'				=> array( 'ajax_filter' => array( 'class' ) ),
					'target'			=> array( 'js_handler'  => array( 'target_selector' => '.bt_bb_latest_posts_item_image > a, .bt_bb_latest_posts_item_title > a', 'type' => 'attr', 'attr' => 'target' ) ),
					'image_shape'		=> array( 'ajax_filter' => array( 'class' ) ),
				),
			),
			'bt_bb_masonry_image_grid' => array(
				'edit_box_selector' => '',
				'ajax_trigger_window_load' => true,
				'params' => array(
					'images'		=> array(),
					'gap'			=> array( 'ajax_filter' => array( 'class' ) ),
				),
			),
			'bt_bb_css_image_grid' => array(
				'edit_box_selector' => '',
				'ajax_trigger_window_load' => true,
				'params' => array(
					'images'		=> array(),
					'gap'			=> array( 'ajax_filter' => array( 'class' ) ),
					'shape'			=> array( 'ajax_filter' => array( 'class' ) ),
					'format'		=> array(),
				),
			),
			'bt_bb_masonry_post_grid' => array(
				'edit_box_selector' => '',
				'params' => array(
					'gap'			=> array( 'ajax_filter' => array( 'class' ) ),
				),
			),
			'bt_bb_css_post_grid' => array(
				'edit_box_selector' => '',
				'params' => array(
					'gap'				=> array( 'ajax_filter' => array( 'class' ) ),
					'shape' 			=> array( 'ajax_filter' => array( 'class' ) ),
					'image_shape' 		=> array( 'ajax_filter' => array( 'class' ) ),
				),
			),
			'bt_bb_price_list' => array(
				'edit_box_selector' => '',
				'params' => array(
					'title'				=> array( 'js_handler' => array( 'target_selector' => '.bt_bb_price_list_title', 'type' => 'inner_html' ) ),
					'subtitle'			=> array( 'js_handler' => array( 'target_selector' => '.bt_bb_price_list_subtitle', 'type' => 'inner_html' ) ),
					'currency'			=> array( 'js_handler' => array( 'target_selector' => '.bt_bb_price_list_currency', 'type' => 'inner_html' ) ),
					'price'				=> array( 'js_handler' => array( 'target_selector' => '.bt_bb_price_list_amount', 'type' => 'inner_html' ) ),
					'items'				=> array(),
					'color_scheme'		=> array( 'ajax_filter' => array( 'class', 'style' ) ), 
					'currency_position' => array( 'ajax_filter' => array( 'class' ) ), 
				),
			),
			'bt_bb_progress_bar' => array(
				'edit_box_selector' => '',
				'params' => array(
					'percentage'		=> array( 'js_handler'  => array( 'target_selector' => '.bt_bb_progress_bar_inner', 'type' => 'attr', 'attr' => 'style', 'preprocess' => 'progress_bar_style' ) ),
					'text'				=> array( 'js_handler'  => array( 'target_selector' => '.bt_bb_progress_bar_text', 'type' => 'inner_html' ) ),
					'color_scheme'		=> array( 'ajax_filter' => array( 'class', 'style' ) ), 
					'align'				=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
					'size'				=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
					'style'				=> array( 'ajax_filter' => array( 'class' ) ), 
					'shape'				=> array( 'ajax_filter' => array( 'class' ) ), 
				),
			),
			'bt_bb_section' => array(
				'edit_box_selector' => '',
				'params' => array(
					'background_image'		=> array( 'js_handler'  => array( 'target_selector' => '.bt_bb_background_image_holder', 'type' => 'background_image' ) ),
					'parallax'				=> array( 'js_handler'  => array( 'target_selector' => '','type' => 'attr', 'attr' => 'data-parallax' ) ),
					'parallax_offset'		=> array( 'js_handler'  => array( 'target_selector' => '', 'type' => 'attr', 'attr' => 'data-parallax-offset' ) ),
					'top_spacing'			=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
					'bottom_spacing'		=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
					'full_screen'			=> array( 'ajax_filter' => array( 'class' ) ), 
					'vertical_align'		=> array( 'ajax_filter' => array( 'class' ) ), 
					'color_scheme'			=> array( 'ajax_filter' => array( 'class', 'style' ) ), 
					'background_overlay'	=> array( 'ajax_filter' => array( 'class' ) ), 
					'background_color'		=> array( 'js_handler'  => array( 'target_selector' => '', 'type' => 'background_color' ) ),
				),
			),
			'bt_bb_separator' => array(
				'edit_box_selector' => '',
				'params' => array(
					'top_padding'		=> array( 'ajax_filter' => array( 'class' ) ), 
					'bottom_padding'	=> array( 'ajax_filter' => array( 'class' ) ), 
					'top_spacing'		=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
					'bottom_spacing'	=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
					'border_style'		=> array( 'ajax_filter' => array( 'class' ) ), 
					'border_thickness'	=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
					'color_scheme'		=> array( 'ajax_filter' => array( 'class', 'style' ) ), 
					'icon'				=> array(),
					'icon_size'			=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
					'text'				=> array( 'js_handler'  => array( 'target_selector' => '.bt_bb_separator_v2_inner_text', 'type' => 'inner_html_nl2br' ) ),
					'text_size'			=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
				),
			),
			'bt_bb_service' => array(
				'edit_box_selector' => '',
				'params' => array(
					'ai_prompt'		=> array(),
					'icon'			=> array(),
					'title'			=> array( 'js_handler'  => array( 'target_selector' => '.bt_bb_service_content_title', 'type' => 'inner_html' ) ),
					'text'			=> array( 'js_handler'  => array( 'target_selector' => '.bt_bb_service_content_text', 'type' => 'inner_html_nl2br' ) ),
					'url'			=> array( 'js_handler'  => array( 'target_selector' => 'a.bt_bb_icon_holder, .bt_bb_service_content_title a', 'type' => 'attr', 'attr' => 'href' ) ),
					'target'		=> array( 'js_handler'  => array( 'target_selector' => 'a.bt_bb_icon_holder, .bt_bb_service_content_title a', 'type' => 'attr', 'attr' => 'target' ) ),
					'size'			=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
					'color_scheme'	=> array( 'ajax_filter' => array( 'class', 'style' ) ), 
					'style'			=> array( 'ajax_filter' => array( 'class' ) ), 
					'shape'			=> array( 'ajax_filter' => array( 'class' ) ), 
					'align'			=> array( 'ajax_filter' => array( 'class', 'data-bt-override-class' ) ),
				),
			),
			'bt_bb_slider' => array(
				'edit_box_selector' => '',
				'ajax_slick' => true,
				'params' => array(
					'images'	=> array(),
				),
			),
			'bt_bb_tab_item' => array(
				'edit_box_selector' => '',
				'params' => array(
					'title'		=> array( 'js_handler' => array( 'target_selector' => 'span', 'type' => 'inner_html' ) ),
				),
			),
			'bt_bb_text' => array(
				'ajax_mejs' => true,
				'edit_box_selector' => '',
				'params'		=> array(

				),
			),			
			'bt_bb_video' => array(
				'edit_box_selector' => '',
				'ajax_mejs' => true,
				'params' => array(
					'video' 			=> array(),
					'disable_controls'	=> array( 'ajax_filter' => array( 'class' ) ),
				),
			),
		) );
		add_action( 'wp_head', 'bt_bb_fe_head' );
		add_action( 'wp_head', 'bt_bb_translate' );
		add_action( 'wp_footer', 'bt_bb_fe_dialog' );
		
		add_action( 'wp_head', function() {
			wp_enqueue_style( 'bt_bb_framework-leaflet-css', plugin_dir_url( __FILE__ ) . 'css/leafletmap/leaflet.css', array(), BT_BB_VERSION, 'screen' );
			wp_enqueue_style( 'bt_bb_framework-markercluster-css', plugin_dir_url( __FILE__ ) . 'css/leafletmap/MarkerCluster.css', array(), BT_BB_VERSION, 'screen' );
			wp_enqueue_style( 'bt_bb_framework-markercluster-default-css',  plugin_dir_url( __FILE__ ) . 'css/leafletmap/MarkerCluster.Default.css', array(), BT_BB_VERSION, 'screen' );
		});	
		
	}
}

function bt_bb_fe_head() {
	echo '<script>';
		echo 'window.bt_bb_fe_elements = ' . bt_bb_json_encode( BT_BB_FE::$elements ) . ';';
		BT_BB_Root::$elements = apply_filters( 'bt_bb_elements', BT_BB_Root::$elements );
		$elements = BT_BB_Root::$elements;
		foreach ( $elements as $key => $value ) {
			$params = isset( $value[ 'params' ] ) ? $value[ 'params' ] : null;
			$params1 = array();
			if ( is_array( $params ) ) {
				foreach ( $params as $param ) {
					$params1[ $param['param_name'] ] = $param;
				}
			}
			$elements[ $key ][ 'params' ] = $params1;
		}
		echo 'window.bt_bb_elements = ' . bt_bb_json_encode( $elements ) . ';';
		global $post;
		echo 'window.bt_bb_post_id = ' . $post->ID . ';';
		echo 'window.bt_bb_settings = [];';
		$options = get_option( 'bt_bb_settings' );
		$slug_url = array_key_exists( 'slug_url', $options ) ? $options['slug_url'] : '';
		echo 'window.bt_bb_settings.slug_url = "' . esc_js( $slug_url ) . '";';
		echo 'window.bt_bb_ajax_url = "' . esc_js( admin_url( 'admin-ajax.php' ) ) . '";'; // back. compat.
		echo 'window.bt_bb_fa_url = "' . plugins_url( 'css/font-awesome.min.css', __FILE__ ) . '";';
		echo 'window.bt_bb_fe_dialog_content_css_url = "' . plugins_url( 'css/front_end/fe_dialog_content.crush.css', __FILE__ ) . '";';
		echo 'window.bt_bb_fe_dialog_bottom_css_url = "' . plugins_url( 'css/front_end/fe_dialog_bottom.crush.css', __FILE__ ) . '";';
		if ( is_rtl() ) {
			echo 'window.bt_bb_rtl = true;';
		} else {
			echo 'window.bt_bb_rtl = false;';
		}
		if ( function_exists( 'boldthemes_get_icon_fonts_bb_array' ) ) {
			$icon_arr = boldthemes_get_icon_fonts_bb_array();
		} else {
			require_once( dirname(__FILE__) . '/content_elements_misc/fa_icons.php' );
			require_once( dirname(__FILE__) . '/content_elements_misc/fa5_regular_icons.php' );
			require_once( dirname(__FILE__) . '/content_elements_misc/fa5_solid_icons.php' );
			require_once( dirname(__FILE__) . '/content_elements_misc/fa5_brands_icons.php' );
			require_once( dirname(__FILE__) . '/content_elements_misc/s7_icons.php' );
			$icon_arr = array( 'Font Awesome' => bt_bb_fa_icons(), 'Font Awesome 5 Regular' => bt_bb_fa5_regular_icons(), 'Font Awesome 5 Solid' => bt_bb_fa5_solid_icons(), 'Font Awesome 5 Brands' => bt_bb_fa5_brands_icons(), 'S7' => bt_bb_s7_icons() );
		}
		echo 'window.bt_bb_icons = JSON.parse(\'' . bt_bb_json_encode( $icon_arr ) . '\')';
	echo '</script>';
}

function bt_bb_fe_dialog() {
	echo '<div id="bt_bb_fe_dialog">';
		echo '<div>';
			echo '<div id="bt_bb_fe_dialog_main">';
				echo '<div class="bt_bb_dialog_header">';
					echo '<div class="bt_bb_dialog_header_text"></div>';
					echo '<div id="bt_bb_fe_dialog_close" role="button" class="bt_bb_dialog_close" title="' . esc_html__( 'Close dialog', 'bold-builder' ) . '"></div>';
					echo '<div id="bt_bb_fe_dialog_switch" role="button" title="' . esc_html__( 'Switch side', 'bold-builder' ) . '"><i class="fa fa-exchange"></i></div>';
				echo '</div>';
				echo '<div class="bt_bb_dialog_header_tools"></div>';
				echo '<div id="bt_bb_fe_dialog_content"></div>';
				echo '<div id="bt_bb_fe_dialog_tinymce_container">';
					// https://developer.wordpress.org/reference/classes/_wp_editors/parse_settings/
					wp_editor( '' , 'bt_bb_fe_dialog_tinymce', array( 'media_buttons' => false, 'editor_height' => 200, 'tinymce' => array(
						'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator',
						'toolbar2'      => '',
						'toolbar3'      => '',
					) ) );
				echo '</div>';
				echo '<div id="bt_bb_fe_dialog_bottom"></div>';
			echo '</div>';
			// echo '<div id="bt_bb_fe_dialog_close" title="Close dialog"><i class="fa fa-close"></i></div>';
			
		echo '</div>';
	echo '</div>';
	
	if ( ! isset( $_GET['bt_bb_fe_add_section'] ) ) {
		echo '<div id="bt_bb_fe_add_section_dialog">';
			echo '<div class="bt_bb_add_section_header">';
				echo '<div class="bt_bb_add_section_header_text">' . esc_html__( 'Add Section', 'bold-builder' ) . '</div>';
				echo '<div id="bt_bb_fe_add_section_close" role="button" title="' . esc_html__( 'Close dialog', 'bold-builder' ) . '"></div>';
				echo '<div id="bt_bb_fe_add_section_switch" role="button" title="' . esc_html__( 'Switch side', 'bold-builder' ) . '"><i class="fa fa-exchange"></i></div>';
				echo '<input type="search" id="bt_bb_fe_add_section_search" placeholder="' . esc_html__( 'Filter...', 'bold-builder' ) . '">';
			echo '</div>';
			echo '<div id="bt_bb_add_section_iframe_parent">';
				echo '<iframe src="' . get_site_url() . '?bt_bb_fe_add_section"></iframe>';
			echo '</div>';
			echo '<div id="bt_bb_fe_add_section_bottom">';
				echo '<div id="bt_bb_fe_add_section_to_top" role="button"><i class="fa fa-arrow-up"></i><span>' . esc_html__( 'Add to Top', 'bold-builder' ) . '</span></div>';
				echo '<div id="bt_bb_fe_add_section_to_bottom" role="button"><i class="fa fa-arrow-down"></i><span>' . esc_html__( 'Add to Bottom', 'bold-builder' ) . '</span></div>';
				echo '<div id="bt_bb_fe_add_section_to_clipboard" role="button"><i class="fa fa-clipboard"></i><span>' . esc_html__( 'Add to Clipboard', 'bold-builder' ) . '</span></div>';
			echo '</div>';
		echo '</div>';
	}
	
	echo '<div id="bt_bb_fe_init_mouseover"></div>';
}

/**
 * Save post
 */

function bt_bb_fe_save() {
	check_ajax_referer( 'bt_bb_fe_nonce', 'nonce' );
	$post_id = intval( $_POST['post_id'] );
	$post_content = wp_kses_post( $_POST['post_content'] );
	if ( current_user_can( 'edit_post', $post_id ) ) {
		$post = array(
			'ID'           => $post_id,
			'post_content' => $post_content,
		);
		wp_update_post( $post );
		echo 'ok';
	}
	wp_die();
}
add_action( 'wp_ajax_bt_bb_fe_save', 'bt_bb_fe_save' );

/**
 * Get HTML
 */
function bt_bb_fe_get_html() {
	check_ajax_referer( 'bt_bb_fe_nonce', 'nonce' );
	$post_id = intval( $_POST['post_id'] );
	$content = stripslashes( wp_kses_post( $_POST['content'] ) );
	if ( current_user_can( 'edit_post', $post_id ) ) {
		remove_filter( 'the_content', 'wpautop' );
		$html = apply_filters( 'the_content', $content );
		$html = str_ireplace( array( '``', '`{`', '`}`' ), array( '&quot;', '&#91;', '&#93;' ), $html );
		$html = str_ireplace( array( '*`*`*', '*`*{*`*', '*`*}*`*' ), array( '``', '`{`', '`}`' ), $html );
		echo $html;
	}
	wp_die();
}
add_action( 'wp_ajax_bt_bb_fe_get_html', 'bt_bb_fe_get_html' );

/**
 * Add Section template
 */
add_filter( 'template_include', 'bt_bb_fe_add_section_template', 100 );
function bt_bb_fe_add_section_template( $template ) {
    if ( isset( $_GET['bt_bb_fe_add_section'] ) && BT_BB_FE::$editor_active ) {
		add_filter( 'show_admin_bar', function( $classes ) {
			return false;
		});
		add_filter( 'body_class', function( $classes ) {
			return array_merge( $classes, array( 'bt_bb_fe_add_section', 'bt_bb_fe_preview_toggle' ) );
		});
		add_action( 'wp_head', function() {
			echo '<style>html{ margin-top: 0px !important; }</style>';
		});
        $template = dirname( __FILE__ ) . '/add-section-template.php';
    }
    return $template;
}
