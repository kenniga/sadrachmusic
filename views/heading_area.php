<?php
	
	$post_id 		= get_the_ID();
	$title			= get_the_title();
	$subtitle 		= esc_html(get_post_meta($post_id, 'lc_swp_meta_subtitle', true));
	$color_theme 	= get_post_meta($post_id, 'lc_swp_meta_heading_color_theme', true);
	$heading_style 	= get_post_meta($post_id, 'lc_swp_meta_heading_full_color', true); /*title_full_color, title_transparent_color*/
	$bg_image 		= get_post_meta($post_id, 'lc_swp_meta_heading_bg_image', true);
	$overlay 		= get_post_meta($post_id, 'lc_swp_meta_heading_overlay_color', true);

	if (is_author() || is_category() || is_archive() || is_home() || is_search()) {
		$subtitle = $bg_image = $overlay = "";
		$color_theme = LUCILLE_SWP_get_default_color_scheme();
	}
	if (is_404()) {
		$subtitle = esc_html__("404 - Page not found", "lucille");
		$title = esc_html__("OOPS!", "lucille");
		/*$color_theme = LUCILLE_SWP_get_default_color_scheme();*/
		/*since the background header image is always the same, the color scheme should be white on black*/
		$color_theme = " white_on_black";
		$heading_style = 'title_transparent_color';
		$bg_image = get_template_directory_uri().'/core/img/404NotFound.jpg';
		$overlay = 'rgba(0,0,0, 0.8)';
	}
	if (LUCILLE_SWP_is_woocommerce_special_page()) {
		$color_theme = LUCILLE_SWP_get_default_color_scheme();
	}

	/*
		Add css classes
	*/
	$heading_area_classes = '';
	$data_bgimage = '';
	$img_overlay_div = '';

	if (!empty($bg_image)) {
		$data_bgimage = ' data-bgimage="'.esc_url($bg_image).'"';
		$img_overlay_div = '<div class="lc_swp_background_image lc_heading_image_bg"'.$data_bgimage.'></div>';
	}

	/*Supported color themes: white_on_black, black_on_white */
	$heading_area_classes .= ' '.esc_attr($color_theme);
	if (empty($subtitle)) {
		$heading_area_classes .= ' no_subtitle';
	} else {
		$heading_area_classes .= ' have_subtitle';
	}

	/*handle overlay*/
	$overlay_div = '';
	if (!empty($overlay)) {
		$overlay_div = '<div class="lc_swp_overlay" data-color="'.esc_attr($overlay).'"></div>';
	}

	/*handle subtitle*/
	$subtitle_div = '';
	if (!empty($subtitle)) {
		$subtitle_div = '<h2 class="heading_area_subtitle>">'.esc_html($subtitle).'</h2>';
	}

	/*add vibrant color for h2 only on full color h1 (first layout option)*/
	$colorItVibrant = '';
	if ('title_full_color' == $heading_style) {
		$colorItVibrant = ' lc_vibrant_color';
	}

	$additional_title_class = '';
	if (empty($subtitle)) {
		$additional_title_class = ' no_subtitle';
	}
	/*keep header with big height for some cases*/
	if (is_tax()) {
		$additional_title_class .= ' spaced_title';
	} elseif (empty($subtitle) && LUCILLE_SWP_emphasize_title_for_this_page()) {
		$additional_title_class .= ' spaced_title';
	}

	/*title for special templates - keep this the latest processed data*/
	if (is_author()) {
		$title = esc_html__('Author: ', "lucille").get_the_author();
	} elseif (is_category()) {
		$title = single_cat_title("", FALSE);
	} elseif (is_archive()) {
		if (is_tax()) {
			/*custom taxonomy*/
			$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$title = $term->name;
		} else {
			$title = get_the_time(get_option('date_format'));	
		}
	} elseif (is_home()) {
		$title = esc_html__("Blog", "lucille");
	} elseif (is_search()) {
		$title = get_search_query();
	}

	/*treat separately the shop and product page [[[*/
	if (function_exists("is_shop")) {
		if (is_shop()) {
			$title = woocommerce_page_title(false);
		}
	}
	if (function_exists("is_product")) {
		if (is_product()) {
			$title = woocommerce_page_title(false);
		}
	}
	/*treat separately the shop and product page ]]]*/

	//is single and is_post
	$supported_post_types = array('js_videos', 'js_events', 'js_albums', 'js_photo_albums');
	$has_cpt_tax = '';
	if (is_singular($supported_post_types)) {
		$has_cpt_tax = " has_cpt_tax";
	}

?>