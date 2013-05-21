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


 function test_notshortcode() {
 	# $out = do_shortcode( '[showsingle postid="1"]' );
 	# $this->assertEquals( '', $out );
 }

 function test_oneatt(){
  # do_shortcode( '[showsingle postid="1"]' );
  # error_log( json_encode( $this ) );
  # $this->assertEquals( array( 'postid' => '1' ), $this->atts );
  # $this->assertEquals( 'showsingle', $this->tagname );
 }


}

?>