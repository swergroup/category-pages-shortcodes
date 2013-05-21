<?php

if ( ! array_key_exists( 'swer-page2cat-core', $GLOBALS ) ) { 
 class Page2Cat_Core {

  static function do_excerpt( $content ){
    return wp_trim_excerpt( $content );
  }


  static function do_header( $text, $level = '2', $class = 'aptools-single-header page2cat-single-header' ){
    if ( $text === null )
      return '';

    $hopen = '<h'.$level.' class="'.$class.'">';
    $hclose = '</h'.$level.'>';
    return $hopen . $text . $hclose;
  }

  static function do_content( $text, $class = 'aptools-content page2cat-content' ){
    $copen = '<div class="'.$class.'">';
    $cclose = '</div>';
    return $copen . do_shortcode( $text ) . $cclose;
  }

  static function do_wrapper( $content, $class = 'aptools-wrapper page2cat-wrapper' ){
    $output = '<div class="'.$class.'">'.$content.'</div>';
    return $output;
  }

  static function do_list_item( $post, $excerpt = 'false', $image = 'false', $headerlink = 'true' ){
    # error_log( json_encode( $post ) ); 
    $size = get_option( 'p2c_list_thumb' );
    if ( $size == 'icon' ) $size = array( 16, 16 );

    $title = ( $headerlink === 'true' ) ? '<a href="'.get_permalink( $post->ID ).'">'.$post->post_title.'</a>' : $post->post_title; 
    $pre = ( $image === 'true' ) ? get_the_post_thumbnail( $post->ID, $size, array( 'class' => 'page2cat-list-icon' ) ) : null;
    $post = ( $excerpt === 'true' ) ? '<span>' . self::do_excerpt( $post->post_content ) . '</span>' : null;
    $output = '<li> ' . $pre . ' ' . $title . ' ' . $post . ' </li>';
    return $output;
  }

  static function shortcode_posts( $args ){
    extract(
     shortcode_atts(
      array(
      'postid' => '',
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
     if ( $showheader === 'true' ) $output .= self::do_header( $page->post_title, $header, $headerclass );
     if ( $content === 'true' ) $output .= self::do_content( $page->post_content, $contentclass );
     if ( $wrapper !== 'false' ) $output = self::do_wrapper( $output, $wrapperclass );
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
      if ( $showheader === 'true' ) $output .= self::do_header( $page->post_title, $header, $headerclass );
      if ( $content === 'true' ) $output .= self::do_content( $page->post_content, $contentclass );
      if ( $wrapper !== 'false' ) $output = self::do_wrapper( $output, $wrapperclass );
    endif;

   elseif ( isset( $autoargs ) && is_array( $autoargs ) ) :
    $pagehead = get_pages( $autoargs );
    if ( isset( $pagehead[0]->ID ) ):
      if ( $showheader === 'true' ) $output .= self::do_header( $pagehead[0]->post_title, '1', 'aptools-auto-header page2cat-auto-header' );
      if ( $content === 'true' ) $output .= self::do_content( $pagehead[0]->post_content, 'aptools-auto-content page2cat-auto-content' );
      if ( $wrapper !== 'false' ) $output = self::do_wrapper( $output, $wrapperclass );
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
    $posts = get_posts( array( 'posts_per_page' => $length, 'numberposts' => $length, 'category' => $catid ) );

    if ( $showheader === 'true' ) $output .= self::do_header( $headertext, $header, $headerclass );
    $output .= ' <ul class="'.$listclass.'"> ';
    foreach ( $posts as $key => $post ):
      $output .= self::do_list_item( $post, $excerpt, $image, $link );
    endforeach;
    $output .= ' </ul> ';
    if ( $wrapper !== 'false' ) $output = self::do_wrapper( $output, $wrapperclass );
   endif;
   wp_reset_postdata();
   return $output;
  }






 }


 if ( class_exists( 'Page2cat_Core' ) ):
  $page2cat_core = new Page2cat_Core();
  $GLOBALS['swer-page2cat-core'] = $page2cat_core;
 endif;
}
