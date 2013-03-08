<?php

class Page2catTest extends WP_UnitTestCase {

	private $plugin;

    function setUp(){
        parent::setUp();
        $this->plugin = $GLOBALS['swer-page2cat-admin'];
    }

    function testPluginInit(){
        $this->assertFalse( null == $this->plugin , 'Plugin is not working');
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

