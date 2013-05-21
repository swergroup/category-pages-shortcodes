<?php

if ( ! array_key_exists( 'swer-page2cat-admin', $GLOBALS ) ) { 
 class Page2CatAdmin extends Page2Cat_Core{

  static function add_meta_boxes(){
    if ( !defined( 'SWER_PHPUNIT' ) )
     add_meta_box( 'page2cat_archive_link', 'Category Pages & Posts', array( 'Page2CatAdmin', 'page2cat_custom_metabox' ), 'page', 'side', 'core' );        
  }


  function validate_settings( $input ){
    $opts = get_option( 'p2c_list_thumb' );
    $opts = trim( $input );
    return $opts;
  }


  static function admin_init() {
   add_settings_section( 'page2cat', 'Page2cat: Category, Pages & Posts Shortcodes', array( 'Page2CatAdmin', 'settings_section' ), 'reading' );
   add_settings_field( 'p2c_list_thumb', '[showlist] thumbnail size', array( 'Page2CatAdmin', 'field_p2c_list_thumb' ), 'reading', 'page2cat', array( 'label_for' => 'p2c_list_thumb') );
   register_setting( 'reading', 'p2c_list_thumb', array( 'Page2CatAdmin', 'validate_settings' ) );
  }


  function settings_section(){
   _e( 'Category, Pages & Posts Shortcodes settings!' );
  }

  function field_p2c_list_thumb(){
    $opt = get_option( 'p2c_list_thumb' );
    $sizes = apply_filters( 'image_size_names_choose', array( 'icon' => __( '16x16 Icon' ), 'thumbnail' => __( 'Thumbnail' ), 'medium' => __( 'Medium' ), 'large' => __( 'Large' ), 'full' => __( 'Full Size' ) ) );
   _e( '<select name="p2c_list_thumb" id="p2c_list_thumb">' );
   foreach ( $sizes as $key => $size ):
      $selected = ( $key === $opt ) ? 'selected="selected"' : null;
      _e( '<option value="'.$key.'" '.$selected.'>'.$size.'</option>' );
   endforeach;

  }


  static function manage_pages_columns( $post_columns ){
      $post_columns['page2cat'] = 'Category';
      return $post_columns;
  }
  
  static function manage_pages_custom_column( $column, $post_id ){
      $selected = (int) get_post_meta( $post_id, 'page2cat_archive_link', true );        
      
  	$category = get_category( $selected );
  	if ( is_wp_error( $category ) ) return false;

   if ( $category ):
    echo '<a 
       href="'.admin_url( 'edit-tags.php?action=edit&taxonomy=category&tag_ID='.$selected.'&post_type=post' ).'">'
       .$category->name
       .'</a>';
   endif;
  }
    
  static function page2cat_custom_metabox( $post ){
   $selected = get_post_meta( $post->ID, 'page2cat_archive_link', true );

   $others = get_posts( array( 'post_type' => 'page', 'meta_key' => 'page2cat_archive_link' ) );
   foreach ( $others as $ex ):
      $ids[] = $ex->ID;
      $exclude[] = get_post_meta( $ex->ID, 'page2cat_archive_link', true );
   endforeach;
   $to_exclude = ( count( $exclude ) > 0 ) ? join( ',', $exclude ) : null;
   # error_log( $to_exclude );


   #print_r($selected);
   wp_nonce_field( plugin_basename( __FILE__ ), 'page2cat-nonce' );
   
   $args = array(
       'selected'          => $selected,
       'show_count'        => 0,
       'hide_empty'        => 1,
       'hierarchical'      => 1,
       'exclude'           => $to_exclude,
       'show_option_none'  => '(None)',
       'name'              => 'page2cat-metabox',
       'id'                => 'page2cat-metabox',
       'taxonomy'          => 'category',
   );
   wp_dropdown_categories( $args );      
   echo '<p>Link this page to a category, display in your category template with the <a href="http://dev.swergroup.com/pages-and-posts-shortcodes/wiki/showauto"><em>[showauto]</em> shortcode</a>.</p>';
  }
    
    
  // update logic, same for manage_posts_custom_columns
  static function save_post( $post_id ){
   global $wp, $wpdb;
   if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
   return;

   if ( !isset($_POST[ 'page2cat-nonce' ]) || !wp_verify_nonce( $_POST[ 'page2cat-nonce' ], plugin_basename( __FILE__ ) ) )
   return;
   if ( 'page' == $_POST['post_type'] ){
      if ( !current_user_can( 'edit_page', $post_id ) )
          return;
   } else {
      if ( !current_user_can( 'edit_post', $post_id ) )
          return;
   }
   if ( $_POST ):
    if ( isset( $_POST['page2cat-metabox'] ) ):
      $others = get_posts(
       array(
       'post_type' => 'page',
       'meta_key' => 'page2cat_archive_link',
       'posts_per_page' => 10,
        )
      );

     # error_log( count( $others ) );
     if ( count( $others ) > 1 && $others[0]->ID != $post_id )
      return;

     update_post_meta( $post_id, 'page2cat_archive_link', $_POST['page2cat-metabox'] );
    endif;
   endif;
  }
    
    
  static function category_add_form_fields( $tag ){
      $args = array(
          'selected'         => 0,
          'echo'             => 0,
          'name'             => 'page2cat_page_id',
          'show_option_none' => '(None)',
      );
          
      echo '
          <div class="form-field">
          	<label for="tag-description">'._x( 'Category Pages & Posts', 'Category Pages & Posts' ).'</label>
          	'.wp_dropdown_pages( $args ).'
          	<p>'._( 'Link this category to a page, and use [showauto] shortcode in your category template to embed that page.' ).'</p>
          </div>
      ';
  }
    
  static function category_edit_form_fields( $tag ){
   $query_args = array(
       'post_type' => 'page',
       'meta_key' => 'page2cat_archive_link',
       'meta_value' => $tag->term_id,
       'posts_per_page' => 1,
   );
   $selected = 0;
   $pages = new WP_Query( $query_args );
   if ( $pages->have_posts() ) :
    while ( $pages->have_posts() ):
        $pages->the_post();       
        #echo 'This category is linked with <a href="'.admin_url('post.php?post='.get_the_ID().'&action=edit').'">'.get_the_title().'</a>';
        $selected = get_the_ID();
    endwhile;
   endif;
    
    $pages_args = array(
        'selected'         => $selected,
        'echo'             => 0,
        'name'             => 'page2cat_page_id',
        'show_option_none' => '(None)',
    );
    echo '
<input type="hidden" name="page2cat_pre_page_id" value="'.$selected  .'" />
<tr class="form-field">
<th scope="row" valign="top"><label for="page2cat_page_id">Category Pages & Posts</label></th>
<td>'.wp_dropdown_pages( $pages_args ).'<br />
<span class="description">Link this category to a page, and use [showauto] shortcode in your category template to embed that page.</span>
</td>
</tr>            	
   ';
    wp_reset_postdata();
  }
    
  static function admin_action_editedtag(){
   if ( $_POST['page2cat_pre_page_id'] !== $_POST['page2cat_page_id'] ):
     update_post_meta( $_POST['page2cat_pre_page_id'], 'page2cat_archive_link', '' );
     update_post_meta( $_POST['page2cat_page_id'], 'page2cat_archive_link', $_POST['tag_ID'] );
   endif;
  }
    
    
  static function add_post_tag_columns( $columns ){
    $columns['page2cat'] = 'Page';
    return $columns;
  }
    
  static function add_post_tag_column_content( $content, $column_name, $id ){
    $query_args = array(
        'post_type' => 'page',
        'meta_key' => 'page2cat_archive_link',
        'meta_value' => $id,
        'posts_per_page' => 1,
    );

   $pages = new WP_Query( $query_args );

   if ( $pages->have_posts() ):
    while ( $pages->have_posts() ):
        $pages->the_post();       
        $content .= '<a href="'.admin_url( 'post.php?post='.get_the_ID().'&action=edit' ).'">'.get_the_title().'</a>';
    endwhile;
   endif;

    return $content;
  }


  static function filter_the_content( $content ){
    return $content;
  }


}

 if ( class_exists( 'Page2CatAdmin' ) ):
  $page2cat_admin = new Page2CatAdmin();
  $GLOBALS['swer-page2cat-admin'] = $page2cat_admin;
 endif;
}

#function call_page2cat_admin(){
#    return new Page2CatAdmin();
#}

#add_action( 'add_meta_boxes', 'call_page2cat_admin' );

add_action( 'add_meta_boxes', array( 'Page2CatAdmin', 'add_meta_boxes' ) );
add_action( 'admin_action_editedtag' , array( 'Page2CatAdmin', 'admin_action_editedtag' ) );

add_action( 'admin_init', array( 'Page2CatAdmin', 'admin_init' ) );

add_action( 'category_add_form_fields', array( 'Page2CatAdmin', 'category_add_form_fields' ) );
add_action( 'category_edit_form_fields', array( 'Page2CatAdmin', 'category_edit_form_fields' ) );
add_filter( 'manage_edit-category_columns', array( 'Page2CatAdmin', 'add_post_tag_columns' ) );
add_filter( 'manage_category_custom_column', array( 'Page2CatAdmin', 'add_post_tag_column_content' ), 10, 3 );
add_filter( 'manage_pages_columns', array( 'Page2CatAdmin', 'manage_pages_columns' ) );
add_action( 'manage_pages_custom_column', array( 'Page2CatAdmin', 'manage_pages_custom_column' ), 10, 2 );
add_action( 'save_post', array( 'Page2CatAdmin', 'save_post' ) );

add_filter( 'the_content', array( 'Page2CatAdmin', 'filter_the_content' ) );
