<?php

if ( ! array_key_exists( 'swer-page2cat-core', $GLOBALS ) ) { 
 class Page2Cat_Core {


  function do_header( $text, $level = '2', $class = 'aptools-single-header page2cat-single-header' ){
    $hopen = '<h'.$level.' class="'.$class.'">';
    $hclose = '</h'.$level.'>';
    return $hopen . $text . $hclose;
  }

  function do_content( $text, $class = 'aptools-content page2cat-content' ){
    $copen = '<div class="'.$class.'">';
    $cclose = '</div>';
    return $copen . do_shortcode( $text ) . $cclose;
  }

  function do_wrapper( $content, $class = 'aptools-wrapper page2cat-wrapper' ){
    $output .= '<div class="'.$class.'">'.$content.'</div>';
    return $output;
  }

  function shortcode_posts( $postid, $showheader = 'true', $header = '2', $headerclass = 'aptools-single-header page2cat-single-header', $content = 'true', $contentclass = 'aptools-content page2cat-content', $wrapper = 'false', $wrapperclass = 'aptools-wrapper page2cat-wrapper' ){
   global $wpdb;
   $output = '';
   #$args = array( 'p' => $postid );
   $page = get_post( $postid );
   # error_log( json_encode( $page ) ); die();
   if ( isset( $page ) ):
     if ( $showheader === 'true' ) $output .= self::do_header( $page->post_title, $header, $headerclass );
     if ( $content === 'true' ) $output .= self::do_content( $page->post_content, $contentclass );
     if ( $wrapper !== 'false' ) $output = self::do_wrapper( $output, $wrapperclass );
   endif;
   return $output;
  }


 }

 if ( class_exists( 'Page2cat_Core' ) ):
  $page2cat_core = new Page2cat_Core();
  $GLOBALS['swer-page2cat-core'] = $page2cat_core;
 endif;
}
