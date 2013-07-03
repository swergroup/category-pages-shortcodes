<?php

if ( ! array_key_exists( 'swer-page2cat-core', $GLOBALS ) )
{ 
	class Page2Cat_Core {

	static function do_excerpt( $post ){
		if ( '' !== trim( $post->excerpt ) ):
			return $post->excerpt;
		else :
			return wp_trim_excerpt( $post->content );
		endif;
	}


	static function do_header( $text, $level = '2', $class = 'aptools-single-header page2cat-single-header' ){
		if ( $text === null )
		return '';
		$hopen  = '<h'.$level.' class="'.$class.'">';
		$hclose = '</h'.$level.'>';
		return $hopen . $text . $hclose;
	}

	static function do_content( $text, $class = 'aptools-content page2cat-content' ){
		$copen  = '<div class="'.$class.'">';
		$cclose = '</div>';
		return $copen . do_shortcode( apply_filters( 'the_content', wp_trim_excerpt( $text ) ) ) . $cclose;
	}

	static function do_wrapper( $content, $class = 'aptools-wrapper page2cat-wrapper' ){
		$output = '<div class="'.$class.'">'.$content.'</div>';
		return $output;
	}

	static function do_list_item( $post, $excerpt = 'false', $image = 'false', $headerlink = 'true' ){
		# error_log( json_encode( $post ) ); 
		$size = get_option( 'p2c_list_thumb' );
		if ( $size == 'icon' ) $size = array( 16, 16 );

		$title  = ( $headerlink === 'true' ) ? '<a href="'.get_permalink( $post->ID ).'">'.$post->post_title.'</a>' : $post->post_title; 
		$pre    = ( $image === 'true' ) ? get_the_post_thumbnail( $post->ID, $size, array( 'class' => 'page2cat-list-icon' ) ) : null;
		$post   = ( $excerpt === 'true' ) ? '<span>' . self::do_excerpt( $post ) . '</span>' : null;
		$output = '<li> ' . $pre . ' ' . $title . ' ' . $post . ' </li>';
		return $output;
	}

	static function shortcode_posts( $args ){
		extract(
			shortcode_atts(
				array(
				'postid' => '',
				'post_type' => 'post',
				'status' => 'publish',
				'showheader' => 'true',
				'header' => '2',
				'headerclass' => 'aptools-single-header page2cat-single-header',
				'content' => 'true',
				'contentclass' => 'aptools-content page2cat-content',
				'wrapper' => 'false',
				'wrapperclass' => 'aptools-wrapper page2cat-wrapper',
				),
				$args
			)
		);

		$output = '';
		#$args = array( 'p' => $postid );
		$page = get_post( $postid );
		# error_log( json_encode( $page ) ); die();
		if ( isset( $page->ID ) ):
			if ( $showheader === 'true' )
				$output .= self::do_header( $page->post_title, $header, $headerclass );
			if ( $content === 'true' )
				$output .= self::do_content( $page->post_content, $contentclass );
			if ( $wrapper !== 'false' )
				$output = self::do_wrapper( $output, $wrapperclass );
		endif;
		wp_reset_postdata();
		return $output;
	}

	static function shortcode_pages( $args, $autoargs = null ){
		$output = false;
		extract(
			shortcode_atts(
				array(
				'pageid' => '',
				'showheader' => 'true',
				'header' => '2',
				'headerclass' => 'aptools-single-header page2cat-single-header',
				'content' => 'true',
				'contentclass' => 'aptools-content page2cat-content',
				'wrapper' => 'false',
				'wrapperclass' => 'aptools-wrapper page2cat-wrapper',
				),
				$args
			)
		);

		if ( !isset( $autoargs ) ):
			$output = '';
			#$args = array( 'p' => $postid );
			$page = get_page( $pageid );
			# error_log( json_encode( $page ) ); die();
			if ( isset( $page->ID ) ):
				if ( $showheader === 'true' )
					$output .= self::do_header( $page->post_title, $header, $headerclass );
				if ( $content === 'true' )
					$output .= self::do_content( $page->post_content, $contentclass );
				if ( $wrapper !== 'false' )
					$output = self::do_wrapper( $output, $wrapperclass );
			endif;

		elseif ( isset( $autoargs ) && is_array( $autoargs ) ) :
			$pagehead = get_pages( $autoargs );
			if ( isset( $pagehead[0]->ID ) ):
				if ( $showheader === 'true' )
					$output .= self::do_header( $pagehead[0]->post_title, $header, $headerclass );
				if ( $content === 'true' )
					$output .= self::do_content( $pagehead[0]->post_content, $contentclass );
				if ( $wrapper !== 'false' )
					$output = self::do_wrapper( $output, $wrapperclass );
			endif;
		endif;
		wp_reset_postdata();                
		return $output;
	}

	static function shortcode_list( $args ){
		extract(
			shortcode_atts(
				array(
					'catid' => '',
					'taxonomy' => '',
					'post_type' => 'post',
					'status' => 'publish',
					'link' => 'true',
					'length' => '10',
					'listclass' => 'aptools-list page2cat-list',
					'showheader' => 'true',
					'header' => '2',
					'headertext' => 'Posts',
					'headerclass' => 'aptools-list-header page2cat-list-header',
					'excerpt' => 'false',
					'image' => 'false',
					'wrapper' => 'false',
					'wrapperclass' => 'aptools-wrapper page2cat-wrapper',
				),
				$args
			)
		);

		$output = '';
		if ( isset( $catid ) ):
			$posts = get_posts( array( 'posts_per_page' => $length, 'numberposts' => $length, 'category' => $catid, 'post_type' => $post_type ) );
		elseif ( isset( $taxonomy ) ) :
			$posts = get_posts( array( 'posts_per_page' => $length, 'numberposts' => $length, 'taxonomy' => $taxonomy, 'post_type' => $post_type ) );
		endif;

		if ( $showheader === 'true' )
			$output .= self::do_header( $headertext, $header, $headerclass );

		$output .= ' <ul class="'.$listclass.'"> ';

		foreach ( $posts as $key => $post ):
			$output .= self::do_list_item( $post, $excerpt, $image, $link );
		endforeach;
		$output .= ' </ul> ';

		if ( $wrapper !== 'false' )
			$output = self::do_wrapper( $output, $wrapperclass );

		wp_reset_postdata();
		return $output;
	}


	function admin_dropdown_category( $post_ID ){
		$exclude  = array();
		$selected = get_post_meta( $post_ID, 'page2cat_archive_link', true );
		$others   = get_posts( array( 'post_type' => 'page', 'meta_key' => 'page2cat_archive_link' ) );
		foreach ( $others as $ex ):
			$ids[]     = $ex->ID;
			$linked_ID = get_post_meta( $ex->ID, 'page2cat_archive_link', true );
			if ( $post_ID != $ex->ID )
				$exclude[] = $linked_ID;
		endforeach;

		$to_exclude = ( count( $exclude ) > 0 ) ? join( ',', $exclude ) : null;
		# error_log( $to_exclude );
		#print_r($selected);
		$args = array(
			'selected'          => $selected,
			'show_count'        => 0,
			'hide_empty'        => 1,
			'hierarchical'      => 1,
			'exclude'           => $to_exclude,
			'show_option_none'  => '(None)',
			'name'              => 'page2cat-metabox',
			'id'                => 'page2cat-metabox',
			'taxonomy'          => 'category',
			'echo'              => false,
		);
		$out = wp_dropdown_categories( $args );
		wp_reset_postdata();
		return $out;
	}


	function admin_dropdown_pages( $cat_ID = 0 ){
		$pre_out    = '';
		$selected   = 0;
		$to_exclude = array();

		if ( $cat_ID != 0 ):
			$query_args = array(
				'post_type' => 'page',
				'meta_key' => 'page2cat_archive_link',
				'meta_value' => $cat_ID,
				'posts_per_page' => 1,
			);
			$pages = new WP_Query( $query_args );
			if ( $pages->have_posts() ) :
				while ( $pages->have_posts() ):
					$pages->the_post();       
					# error_log( ' Linked with '.admin_url( 'post.php?post='.get_the_ID().'&action=edit' ) );
					$selected = get_the_ID();
				endwhile;
			endif;
			$pre_out = '<input type="hidden" name="page2cat_pre_page_id" value="'.$selected  .'" >';

		else :
			$query_args = array(
			'post_type' => 'page',
			'meta_key' => 'page2cat_archive_link',
			'nopaging' => true,
			);
			$pages = new WP_Query( $query_args );
			if ( $pages->have_posts() ) :
				while ( $pages->have_posts() ):
					$pages->the_post();       
					# error_log( ' Linked with '.admin_url( 'post.php?post='.get_the_ID().'&action=edit' ) );
					$to_exclude[] = get_the_ID();
				endwhile;
			endif;
			# error_log( json_encode( $to_exclude ) );
			$excluded = ( count( $to_exclude ) > 0 ) ? join( ',', $to_exclude ) : '';
		endif;

		$pages_args = array(
			'selected'          => $selected,
			'exclude'           => $excluded,
			'name'              => 'page2cat_page_id',
			'show_option_none'  => '(None)',
			'option_none_value' => 0,
			'echo'              => 0,
		);

		$out = wp_dropdown_pages( $pages_args );
		wp_reset_postdata();
		return trim( $pre_out . $out );
	}

	}


	if ( class_exists( 'Page2cat_Core' ) ):
		$page2cat_core = new Page2cat_Core();
		$GLOBALS['swer-page2cat-core'] = $page2cat_core;
	endif;
}
	