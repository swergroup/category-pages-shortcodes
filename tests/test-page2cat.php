<?php

class Page2cat_Admin extends WP_UnitTestCase {

 private $plugin;

 function setup(){
  parent::setUp();
  $this->plugin = $GLOBALS['swer-page2cat-admin'];
 }

 function test_plugin_init(){
  $this->assertFalse( null == $this->plugin , 'Plugin is not working' );
 }

 function test_hooks_actions(){
  $this->assertGreaterThan(
   0,
   has_action( 'add_meta_boxes', array( 'Page2CatAdmin', 'add_meta_boxes' ) ), 
   'Action add_meta_boxes not loading.'
  );

  $this->assertGreaterThan(
   0, 
   has_action( 'admin_action_editedtag', array( 'Page2CatAdmin', 'admin_action_editedtag' ) ), 
   'Action admin_action_editedtag not loading'
  );

  $this->assertGreaterThan(
   0, 
   has_action( 'category_add_form_fields', array( 'Page2CatAdmin', 'category_add_form_fields' ) ), 
   'Action category_add_form_fields not loading.'
  );

  $this->assertGreaterThan(
   0, 
   has_action( 'category_edit_form_fields', array( 'Page2CatAdmin', 'category_edit_form_fields' ) ), 
   'Action category_edit_form_fields not loading.'
  );

  $this->assertGreaterThan(
   0, 
   has_action( 'manage_pages_custom_column', array( 'Page2CatAdmin', 'manage_pages_custom_column' ) ), 
   'Action manage_pages_custom_column not loading.'
  );

  $this->assertGreaterThan(
   0, 
   has_action( 'save_post', array( 'Page2CatAdmin', 'save_post' ) ), 
   'Action save_post not loading.'
  );
  }

 function test_hooks_filters(){
  $this->assertGreaterThan(
   0, 
   has_filter( 'manage_edit-category_columns', array( 'Page2CatAdmin', 'add_post_tag_columns' ) ), 
   'Filter manage_edit-category_columns not loading.'
  );

  $this->assertGreaterThan(
   0, 
   has_filter( 'manage_category_custom_column', array( 'Page2CatAdmin', 'add_post_tag_column_content' ), '' ), 
   'Filter manage_category_custom_column not loading.'
  );

  $this->assertGreaterThan(
   0, 
   has_filter( 'manage_pages_columns', array( 'Page2CatAdmin', 'manage_pages_columns' ) ), 
   'Filter manage_pages_columns not loading.'
  );

  }

 function test_wp_factory(){
      $this->assertGreaterThan( 0, $this->factory->post->create_many( 25 ) , 'Could not create many posts.' );
  }

 function test_hook_manage_pages_columns(){
  $post_columns = array();
  $this->assertEquals( 0, count( $post_columns ),'Pages columns, first count' );

  $columns2 = $this->plugin->manage_pages_columns( $post_columns );
  $this->assertEquals( 1, count( $columns2 ), 'Pages columns, second count' );
  $this->assertEquals( 'Category', $columns2['page2cat'], 'Pages columns should contains Category' );
  }

 function test_hook_add_post_tag_columns(){
  $columns = array();
  $this->assertEquals( 0, count( $columns ),'Array first count' );

  $columns2 = $this->plugin->add_post_tag_columns( $columns );
  $this->assertEquals( 1, count( $columns2 ), 'Array second count' );
  $this->assertEquals( 'Page', $columns2['page2cat'], 'Array should contain Page' );
  }

 function test_add_post_tag_column_content(){
  $content = '';
  $column_name = '';
  $id = '';
  $payload = $this->plugin->add_post_tag_column_content( $content, $column_name, $id );
  $this->assertEquals( '', $payload, 'Column contains content' );
  }



}

