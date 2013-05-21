<?php

if ( ! array_key_exists( 'swer-page2cat-shortcodes', $GLOBALS ) )
{ 
 class Page2catShortcodes extends Page2cat_Core {

  function showsingle( $atts ){
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

   /*
   $hopen = '<h'.$header.' class='.$headerclass.'>';
   $hclose = '</h'.$header.'>';
   $copen = '<div class='.$contentclass.'>';
   $cclose = '</div>';
   $output = '';

   if ( $postid === '' && $pageid !== '' ) :
       $args = array( 'page_id' => $pageid, 'posts_per_page' => 1 );
   elseif ( $pageid === '' && $postid !== '' ) :
       $args = array( 'p' => $postid, 'posts_per_page' => 1 );
   endif;

   $page = new WP_Query( $args );
   if ( $page->have_posts() ):
    while ( $page->have_posts() ):
        $page->the_post();
        if ( $showheader === 'true' ) $output .= parent::do_header( get_the_title(), $header, $headerclass );
        if ( $content === 'true' ) $output .= parent::do_content( get_the_content(), $contentclass );
        if ( $wrapper !== 'false' ) $output = parent::do_wrapper( $output, $wrapperclass );
    endwhile;
   endif;
   wp_reset_postdata();                
   # error_log( $output );
   */

   if ( isset( $postid ) && !isset( $pageid ) ) :
    $output = parent::shortcode_posts( $postid, $showheader, $header, $headerclass, $content, $contentclass, $wrapper, $wrapperclass );
   elseif ( isset( $pageid ) && !isset( $postid ) ) :
    $output = parent::shortcode_page( $postid, $showheader, $header, $headerclass, $content, $contentclass, $wrapper, $wrapperclass );
   endif;

   _e( $output );
  }


  function showlist( $atts ){
    extract(
     shortcode_atts(
      array(
        'catid' => '',
        'lenght' => '10',
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

   $hopen = '<h'.$header.' class='.$headerclass.'>';
   $hclose = '</h'.$header.'>';

   if ( $catid !== '' ) :
       $args = array( 'category__in' => array($catid), 'posts_per_page' => $lenght );
   endif;

   $page = new WP_Query( $args );
   if ( $page->have_posts() ):
    if ( $wrapper !== 'false' ){
        echo '<div class="'.$wrapperclass.'">';
    }
    echo '<ul class="'.$listclass.'">';
    while ( $page->have_posts() ):
     $page->the_post();
     echo '<li>';
     echo '<a href="'.get_permalink().'">'.get_the_title().'</a>'; 
     if ( $image !== 'false' && has_post_thumbnail() ){
       the_post_thumbnail( $image );
     }
     if ( $excerpt === 'true' ) echo ' <span>'.get_the_excerpt().'</span>';
     echo '</li>';
    endwhile;
    echo '</ul>';
    if ( $wrapper !== 'false' ){
        echo '</div>';
    }
   endif;
   wp_reset_postdata();                
  }

    
  function showauto(){
   global $cat;
   $query_args = array(
       'post_type' => 'page',
       'meta_key' => 'aptools_archive_link',
       'meta_value' => $cat,
       'posts_per_page' => 1,
   );

   $pages = new WP_Query( $query_args );

   if ( $pages->have_posts() ):
    while ( $pages->have_posts() ):
        $pages->the_post();
        echo '<h2>'.get_the_title().'</h2>';
        echo '<div class="aptools-category-content page2cat-category-content">'.get_the_content().'</div>';
    endwhile;
   endif;
   wp_reset_postdata();                

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


