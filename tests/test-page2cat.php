<?php

class Page2catTest extends WP_UnitTestCase {

	private $plugin;

    function setUp(){
        parent::setUp();
        $this->plugin = $GLOBALS['swer-page2cat-admin'];
    }

    function testPluginInit(){
        $this->assertFalse( null == $this->plugin , 'Plugin is not working');

        $this->assertGreaterThan( 0, 
        	has_action( 'add_meta_boxes', 'call_SWER_aptools_admin'), 
        	'Action add_meta_boxes not loading.');

        $this->assertGreaterThan( 0, 
        	has_action( 'admin_action_editedtag', array( 'SWER_aptools_admin', 'admin_action_editedtag' ) ), 
        	'Action admin_action_editedtag not loading');

        $this->assertGreaterThan( 0, 
        	has_action( 'category_add_form_fields', array( 'SWER_aptools_admin', 'category_add_form_fields' ) ), 
        	'Action category_add_form_fields not loading.');

        $this->assertGreaterThan( 0, 
        	has_action( 'category_edit_form_fields', array( 'SWER_aptools_admin', 'category_edit_form_fields' ) ), 
        	'Action category_edit_form_fields not loading.');

        $this->assertGreaterThan( 0, 
        	has_action( 'manage_pages_custom_column', array( 'SWER_aptools_admin', 'manage_pages_custom_column' ) ), 
        	'Action manage_pages_custom_column not loading.');

        $this->assertGreaterThan( 0, 
        	has_action( 'save_post', array( 'SWER_aptools_admin', 'save_post' ) ), 
        	'Action save_post not loading.');


        $this->assertGreaterThan( 0, 
        	has_filter( 'manage_edit-category_columns', array( 'SWER_aptools_admin', 'add_post_tag_columns' ) ), 
        	'Filter manage_edit-category_columns not loading.');

        $this->assertGreaterThan( 0, 
        	has_filter( 'manage_category_custom_column', array( 'SWER_aptools_admin', 'add_post_tag_column_content' ), '' ), 
        	'Filter manage_category_custom_column not loading.');

        $this->assertGreaterThan( 0, 
        	has_filter( 'manage_pages_columns', array( 'SWER_aptools_admin', 'manage_pages_columns' ) ), 
        	'Filter manage_pages_columns not loading.');

    }

	function testFactory(){
		$this->assertGreaterThan( 0, $this->factory->post->create_many(25) , 'Could not create many posts.');
	}

	function test_manage_pages_columns(){
		$post_columns = array();
		$this->assertEquals( 0, count($columns),'Pages columns, first count' );

		$columns2 = $this->plugin->manage_pages_columns($post_columns);
		$this->assertEquals( 1, count($columns2), 'Pages columns, second count');
		$this->assertEquals( 'Category', $columns2['aptools'], 'Pages columns should contains Category');
	}

	function test_admin_add_post_tag_columns(){
		$columns = array();
		$this->assertEquals( 0, count($columns),'Array first count' );

		$columns2 = $this->plugin->add_post_tag_columns($columns);
		$this->assertEquals( 1, count($columns2), 'Array second count');
		$this->assertEquals( 'Page', $columns2['atptools'], 'Array should contain Page');
	}

	function test_add_post_tag_column_content(){
		$content = '';
		$column_name = '';
		$id = '';
		$payload = $this->plugin->add_post_tag_column_content($content, $column_name, $id);
		$this->assertEquals( '', $payload, 'Column contains content');
	}



}

