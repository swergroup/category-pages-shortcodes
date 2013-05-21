<?php

class Page2cat_Core_Test extends WP_UnitTestCase {

 private $plugin;

 function setup(){
  parent::setUp();
  $this->plugin = $GLOBALS['swer-page2cat-core'];
 }

 function test_do_header(){
  $content = 'some content';

  $header = $this->plugin->do_header( $content );
  $this->assertEquals( '<h2 class="aptools-single-header page2cat-single-header">some content</h2>', $header , 'Header #1 does not match content' );

  $header2 = $this->plugin->do_header( $content, '5', 'custom-class' );
  $this->assertEquals( '<h5 class="custom-class">some content</h5>', $header2 , 'Header #2 does not match content' );
 }


 function test_do_content(){
  $content = 'some test content';

  $wrapped = $this->plugin->do_content( $content );
  $this->assertEquals( '<div class="aptools-content page2cat-content">some test content</div>', $wrapped, 'Wrapped content #1 does not match content' );

  $wrapped2 = $this->plugin->do_content( $content, 'custom-class' );
  $this->assertEquals( '<div class="custom-class">some test content</div>', $wrapped2, 'Wrapped content #2 does not match content' );
 }


 function test_do_wrapper(){
  $content = 'some wrapped content';

  $wrapped = $this->plugin->do_wrapper( $content );
  $this->assertEquals( '<div class="aptools-wrapper page2cat-wrapper">some wrapped content</div>', $wrapped, 'Wrapped content #1 does not match content' );

  $wrapped2 = $this->plugin->do_content( $content, 'custom-wrap' );
  $this->assertEquals( '<div class="custom-wrap">some wrapped content</div>', $wrapped2, 'Wrapped content #2 does not match content' );
 }


 function test_shortcode_posts(){
  $postID = wp_insert_post( array( 'post_title' => 'title', 'post_content' => 'content content content', 'post_status' => 'publish' ) );

  $short1 = $this->plugin->shortcode_posts( $postID );
  $this->assertEquals( $short1, '<h2 class="aptools-single-header page2cat-single-header">title</h2><div class="aptools-content page2cat-content">content content content</div>' );

  $short2 = $this->plugin->shortcode_posts( $postID, 'false' );
  $this->assertEquals( $short2, '<div class="aptools-content page2cat-content">content content content</div>' );

  $short3 = $this->plugin->shortcode_posts( $postID, 'true', '3', 'header-class' );
  $this->assertEquals( $short3, '<h3 class="header-class">title</h3><div class="aptools-content page2cat-content">content content content</div>' );

  $short4 = $this->plugin->shortcode_posts( $postID, 'true', '2', 'header-class-2', 'false' );
  $this->assertEquals( $short4, '<h2 class="header-class-2">title</h2>' );

  $short5 = $this->plugin->shortcode_posts( $postID, 'true', '4', 'header-class-3', 'true', 'content-class' );
  $this->assertEquals( $short5, '<h4 class="header-class-3">title</h4><div class="content-class">content content content</div>' );

  $short6 = $this->plugin->shortcode_posts( $postID, 'true', '6', 'header-class-4', 'true', 'content-class', 'true' );
  $this->assertEquals( $short6, '<div class="aptools-wrapper page2cat-wrapper"><h6 class="header-class-4">title</h6><div class="content-class">content content content</div></div>' );

  $short7 = $this->plugin->shortcode_posts( $postID, 'true', '5', 'header-class-5', 'true', 'content-class', 'true', 'wrapper-class' );
  $this->assertEquals( $short7, '<div class="wrapper-class"><h5 class="header-class-5">title</h5><div class="content-class">content content content</div></div>' );

}



}