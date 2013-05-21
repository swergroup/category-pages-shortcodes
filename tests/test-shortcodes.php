<?php

class Page2cat_Shortcode_Test extends WP_UnitTestCase {

 private $plugin;

 protected $shortcodes = array( 'showsingle', 'showlist', 'showauto');

 function setup(){
   parent::setUp();
   $this->plugin = $GLOBALS['swer-page2cat-shortcodes'];
 }

 function test_factory(){
 	$this->assertGreaterThan( 0, $this->factory->post->create_many( 25 ) , 'Could not create many posts.' );
 }

 function test_showsingle() {
 	$out = do_shortcode( '[showsingle /]' );
 	$this->assertEquals( '', $out );

 	$out2 = do_shortcode( '[showsingle]' );
 	$this->assertEquals( '', $out2 );

 }




}

?>