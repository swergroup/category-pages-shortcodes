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



}