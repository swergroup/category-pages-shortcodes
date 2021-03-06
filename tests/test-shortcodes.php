<?php

class Page2cat_Shortcode_Test extends WP_UnitTestCase {

 private $plugin;

 protected $shortcodes = array( 'showsingle', 'showlist', 'showauto');

 function setup(){
   parent::setUp();
   $this->plugin = $GLOBALS['swer-page2cat-shortcodes'];

   foreach ( $this->shortcodes as $shortcode )
    add_shortcode( $shortcode, array( $this, '_shortcode_' . str_replace( '-', '_', $shortcode ) ) );

  $this->atts = null;
  $this->content = null;
  $this->tagname = null;
 }

 function teardown(){
  global $shortcode_tags;
  parent::tearDown();
  foreach ( $this->shortcodes as $shortcode )
    unset( $shortcode_tags[ $shortcode ] );

 }

 function _shortcode_test_shortcode_tag( $atts, $content = null, $tagname = null ) {
    $this->atts = $atts;
    $this->content = $content;
    $this->tagname = $tagname;
    $this->filter_atts_out = null;
    $this->filter_atts_pairs = null;
    $this->filter_atts_atts = null;
  }

 function test_oneatt(){
  $post2 = wp_insert_post( array( 'post_title' => 'title', 'post_content' => 'content content content', 'post_status' => 'publish' ) );
  $this->assertFalse( is_wp_error( $post2 ) );

  $out2 = do_shortcode( '[showsingle postid="'.$post2.'"]' );
  $this->assertEquals( '<h2 class="aptools-single-header page2cat-single-header">title</h2><div class="aptools-content page2cat-content">content content content</div>', $out2 );
 }


 function test_shortcode_posts(){
  $postID = wp_insert_post( array( 'post_title' => 'title', 'post_content' => 'content content content', 'post_status' => 'publish' ) );

  $args1 = array( 'postid' => $postID );
  $short1 = $this->plugin->shortcode_posts( $args1 );
  $this->assertEquals( $short1, "<h2 class=\"aptools-single-header page2cat-single-header\">title</h2><div class=\"aptools-content page2cat-content\"><p>content content content</p>\n</div>" );

  $args2 = array( 'postid' => $postID, 'showheader' => 'false' );
  $short2 = $this->plugin->shortcode_posts( $args2 );
  $this->assertEquals( $short2, "<div class=\"aptools-content page2cat-content\"><p>content content content</p>\n</div>" );

  $args3 = array( 'postid' => $postID, 'showheader' => 'true', 'header' => '3', 'headerclass' => 'header-class' );
  $short3 = $this->plugin->shortcode_posts( $args3 );
  $this->assertEquals( $short3, '<h3 class="header-class">title</h3><div class="aptools-content page2cat-content"><p>content content content</p>\n</div>' );

  $args4 = array( 'postid' => $postID, 'showheader' => 'true', 'header' => '2', 'headerclass' => 'header-class-2', 'content' => 'false' );
  $short4 = $this->plugin->shortcode_posts( $args4 );
  $this->assertEquals( $short4, '<h2 class="header-class-2">title</h2>' );

  $args5 = array( 'postid' => $postID, 'showheader' => 'true', 'header' => '4', 'headerclass' => 'header-class-3', 'content' => 'true', 'contentclass' => 'content-class' );
  $short5 = $this->plugin->shortcode_posts( $args5 );
  $this->assertEquals( $short5, '<h4 class="header-class-3">title</h4><div class="content-class"><p>content content content</p>\n</div>' );

  $args6 = array( 'postid' => $postID, 'showheader' => 'true', 'header' => '6', 'headerclass' => 'header-class-4', 'content' => 'true', 'contentclass' => 'content-class', 'wrapper' => 'true' );
  $short6 = $this->plugin->shortcode_posts( $args6 );
  $this->assertEquals( $short6, '<div class="aptools-wrapper page2cat-wrapper"><h6 class="header-class-4">title</h6><div class="content-class"><p>content content content</p>\n</div></div>' );

  $args7 = array( 'postid' => $postID, 'showheader' => 'true', 'header' => '5', 'headerclass' => 'header-class-5', 'content' => 'true', 'contentclass' => 'content-class', 'wrapper' => 'true', 'wrapperclass' => 'wrapper-class' );
  $short7 = $this->plugin->shortcode_posts( $args7 );
  $this->assertEquals( $short7, '<div class="wrapper-class"><h5 class="header-class-5">title</h5><div class="content-class"><p>content content content</p>\n</div></div>' );

}


 function test_shortcode_pages(){
  $postID = wp_insert_post( array( 'post_title' => 'title', 'post_content' => 'content content content', 'post_status' => 'publish', 'post_type' => 'page' ) );

  $args1 = array( 'pageid' => $postID );
  $short1 = $this->plugin->shortcode_pages( $args1 );
  $this->assertEquals( $short1, "<h2 class=\"aptools-single-header page2cat-single-header\">title</h2><div class=\"aptools-content page2cat-content\"><p>content content content</p>\n</div>" );

  $args2 = array( 'pageid' => $postID, 'showheader' => 'false' );
  $short2 = $this->plugin->shortcode_pages( $args2 );
  $this->assertEquals( $short2, "<div class=\"aptools-content page2cat-content\"><p>content content content</p>\n</div>" );

  $args3 = array( 'pageid' => $postID, 'showheader' => 'true', 'header' => '3', 'headerclass' => 'header-class' );
  $short3 = $this->plugin->shortcode_pages( $args3 );
  $this->assertEquals( $short3, "<h3 class=\"header-class\">title</h3><div class=\"aptools-content page2cat-content\"><p>content content content</p>\n</div>" );

  $args4 = array( 'pageid' => $postID, 'showheader' => 'true', 'header' => '2', 'headerclass' => 'header-class-2', 'content' => 'false' );
  $short4 = $this->plugin->shortcode_pages( $args4 );
  $this->assertEquals( $short4, '<h2 class="header-class-2">title</h2>' );

  $args5 = array( 'pageid' => $postID, 'showheader' => 'true', 'header' => '4', 'headerclass' => 'header-class-3', 'content' => 'true', 'contentclass' => 'content-class' );
  $short5 = $this->plugin->shortcode_pages( $args5 );
  $this->assertEquals( $short5, "<h4 class=\"header-class-3\">title</h4><div class=\"content-class\"><p>content content content</p>\n</div>" );

  $args6 = array( 'pageid' => $postID, 'showheader' => 'true', 'header' => '6', 'headerclass' => 'header-class-4', 'content' => 'true', 'contentclass' => 'content-class', 'wrapper' => 'true' );
  $short6 = $this->plugin->shortcode_pages( $args6 );
  $this->assertEquals( $short6, "<div class=\"aptools-wrapper page2cat-wrapper\"><h6 class=\"header-class-4\">title</h6><div class=\"content-class\"><p>content content content</p>\n</div></div>" );

  $args7 = array( 'pageid' => $postID, 'showheader' => 'true', 'header' => '5', 'headerclass' => 'header-class-5', 'content' => 'true', 'contentclass' => 'content-class', 'wrapper' => 'true', 'wrapperclass' => 'wrapper-class' );
  $short7 = $this->plugin->shortcode_pages( $args7 );
  $this->assertEquals( $short7, "<div class=\"wrapper-class\"><h5 class=\"header-class-5\">title</h5><div class=\"content-class\"><p>content content content</p>\n</div></div>" );

 }


 function test_shortcode_list(){

  for ( $i = 0; $i < 20; $i++ ):
    $posts[] = wp_insert_post( array( 'post_title' => 'title #'.$i, 'post_content' => 'content '.$i.' content '.$i.' content', 'post_status' => 'publish', 'post_type' => 'post' ) );
  endfor;

  $this->assertTrue( is_array( $posts ), '$posts is not an array' );
  $this->assertTrue( count( $posts ) === 20, '$posts is not 20' );

  $args1 = array( 'catid' => '1' );
  $list1 = $this->plugin->shortcode_list( $args1 );
  $this->assertFalse( $list1 === null, 'list #1 is not ok' );

  $args2 = array( 'catid' => '1', 'link' => 'false' );
  $list2 = $this->plugin->shortcode_list( $args2 );
  $this->assertFalse( $list2 === null, 'list #2 is not ok' );

  $args3 = array( 'catid' => '1', 'length' => '2' );
  $list3 = $this->plugin->shortcode_list( $args3 );
  $this->assertFalse( $list3 === null, 'list #3 is not ok' );

  $args4 = array( 'catid' => '1', 'listclass' => 'custom-list' );
  $list4 = $this->plugin->shortcode_list( $args4 );
  $this->assertFalse( $list4 === null, 'list #4 is not ok' );

  $args5 = array( 'catid' => '1', 'showheader' => 'false' );
  $list5 = $this->plugin->shortcode_list( $args5 );
  $this->assertFalse( $list5 === null, 'list #5 is not ok' );

  $args6 = array( 'catid' => '1', 'header' => '4' );
  $list6 = $this->plugin->shortcode_list( $args6 );
  $this->assertFalse( $list6 === null, 'list #6 is not ok' );

  $args7 = array( 'catid' => '1', 'headerclass' => 'custom-header' );
  $list7 = $this->plugin->shortcode_list( $args7 );
  $this->assertFalse( $list7 === null, 'list #7 is not ok' );

  /*
  $args8 = array( 'catid' => '1', 'excerpt' => 'true' );
  $list8 = $this->plugin->shortcode_list( $args8 );
  $this->assertFalse( $list8 === null, 'list #8 is not ok' );
  */
  
  $args9 = array( 'catid' => '1', 'image' => 'true' );
  $list9 = $this->plugin->shortcode_list( $args9 );
  $this->assertFalse( $list9 === null, 'list #9 is not ok' );

  $args10 = array( 'catid' => '1', 'wrapper' => 'true' );
  $list10 = $this->plugin->shortcode_list( $args10 );
  $this->assertFalse( $list10 === null, 'list #10 is not ok' );

  $args11 = array( 'catid' => '1', 'wrapper' => 'true', 'wrapperclass' => 'custom-wrapper' );
  $list11 = $this->plugin->shortcode_list( $args11 );
  $this->assertFalse( $list11 === null, 'list #11 is not ok' );

  }



}

?>