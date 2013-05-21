<?php

if ( ! array_key_exists( 'swer-page2cat-shortcodes', $GLOBALS ) )
{ 
 class Page2catShortcodes extends Page2cat_Core {

  static function showsingle( $atts ){
    global $post;
    $clean = $output = false;

    extract(
     shortcode_atts(
      array(
      'postid' => '',
      'pageid' => '',
      'showheader' => 'true',
      'header' => '2',
      'headerclass' => 'aptools-single-header page2cat-single-header',
      'content' => 'true',
      'contentclass' => 'aptools-content page2cat-content',
      'wrapper' => 'false',
      'wrapperclass' => 'aptools-wrapper page2cat-wrapper',
      ),
      $atts
     )
    );

   if ( $postid == $post->ID ) return;
   if ( $pageid == $post->ID ) return;
    # print_r( $atts); die();
   ob_start();

   if ( !empty( $postid ) && empty( $pageid ) ) :
    $output = self::shortcode_posts( $atts );
   elseif ( !empty( $pageid ) && empty( $postid ) ) :
    $output = self::shortcode_pages( $atts );
   else :
    $output = false;
   endif;

    _e( $output );
    $clean = ob_get_clean();
    return $clean;
  }


  static function showlist( $atts ){
    $clean = $output = false;

    extract(
     shortcode_atts(
      array(
        'catid' => '',
        'length' => '10',
        'listclass' => 'aptools-list page2cat-list',
        'header' => '2',
        'headerclass' => 'aptools-list-header page2cat-list-header',
        'excerpt' => 'false',
        'image' => 'false',
        'wrapper' => 'false',
        'wrapperclass' => 'aptools-list-wrapper page2cat-list-wrapper',
      ),
      $atts
     )
    );


   if ( isset( $catid ) ) :
    ob_start();
    $output = self::shortcode_list( $atts );
    _e( $output );
    $clean = ob_get_clean();
   endif;
   return $clean;
  }

    
  static function showauto(){
   global $cat;
   $query_args = array(
       'post_type' => 'page',
       'post_status' => 'publish',
       'meta_key' => 'page2cat_archive_link',
       'meta_value' => $cat,
       'posts_per_page' => 1,
   );

   $defaults = array(
      'showheader' => 'true',
      'header' => '2',
      'headerclass' => 'aptools-single-header page2cat-single-header',
      'content' => 'true',
      'contentclass' => 'aptools-content page2cat-content',
      'wrapper' => 'false',
      'wrapperclass' => 'aptools-wrapper page2cat-wrapper',
    );

   ob_start();
   $output = self::shortcode_pages( $defaults, $query_args );
   _e( $output );
   $clean = ob_get_clean();
   return $clean;
  }
 }

 if ( class_exists( 'Page2catShortcodes' ) ):
  $page2cat_shortcodes = new Page2catShortcodes();
  $GLOBALS['swer-page2cat-shortcodes'] = $page2cat_shortcodes;
 endif;
}


add_shortcode( 'showsingle', array( 'Page2catShortcodes', 'showsingle' ) );
add_shortcode( 'showlist', array( 'Page2catShortcodes', 'showlist' ) );
add_shortcode( 'showauto', array( 'Page2catShortcodes', 'showauto' ) );


