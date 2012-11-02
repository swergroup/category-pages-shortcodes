<?php

class SWER_aptools{

    function showsingle( $atts ){
    	extract( shortcode_atts( array(
    		'postid' => '',
    		'pageid' => '',
    		'showheader' => 'true',
    		'header' => '2',
    		'headerclass' => 'aptools-single-header',
    		'wrapper' => 'false',
    		'wrapperclass' => 'aptools-wrapper'
    	), $atts ) );

        $hopen = '<h'.$header.' class='.$headerclass.'>';
        $hclose = '</h'.$header.'>';

        if( $postid === '' && $pageid !== '' ):
            $args = array( 'page_id' => $pageid, 'posts_per_page' => 1 );
        elseif( $pageid === '' && $postid !== '' ):
            $args = array( 'p' => $postid, 'posts_per_page' => 1 );
        endif;

        $page = new WP_Query( $args );
        if( $page->have_posts() ):
            if( $wrapper !== 'false'){
                echo '<div class="'.$wrapperclass.'">';
            }
            while( $page->have_posts() ):
                $page->the_post();
                if( $showheader === 'true' ) echo $hopen . get_the_title() . $hclose;
                echo get_the_content();
            endwhile;
            if( $wrapper !== 'false'){
                echo '</div>';
            }
        endif;
        wp_reset_postdata();                
    }


    function showlist( $atts ){
    	extract( shortcode_atts( array(
    		'catid' => '',
    		'lenght' => '10',
    		'listclass' => 'aptools-list',
    		'excerpt' => 'false',
    		'image' => 'false',
    		'wrapper' => 'false',
    		'wrapperclass' => 'aptools-list-wrapper'
    	), $atts ) );

        $hopen = '<h'.$header.' class='.$class.'>';
        $hclose = '</h'.$header.'>';

        if( $catid !== '' ):
            $args = array( 'p' => $postid, 'posts_per_page' => $length );
        elseif( $tagid !== '' ):
            $args = array( 'tag_id' => $tagid, 'posts_per_page' => $length );
        endif;

        $page = new WP_Query( $args );
        if( $page->have_posts() ):
            if( $wrapper !== 'false'){
                echo '<div class="'.$wrapperclass.'">';
            }
            echo '<ul class="'.$listclass.'">';
            while( $page->have_posts() ):
                $page->the_post();
                echo '<li>';
                echo '<a href="'.get_permalink().'">'.get_the_title().'</a>'; 
                if( $image !== 'false' && has_post_thumbnail() ){ the_post_thumbnail( $image ); }
                if( $excerpt === 'true' ) echo ' <span>'.get_the_excerpt().'</span>';
                echo '</li>';
            endwhile;
            echo '</ul>';
            if( $wrapper !== 'false'){
                echo '</div>';
            }
        endif;
        wp_reset_postdata();                
    }


}

add_shortcode( 'showsingle', array( 'SWER_aptools', 'showsingle' ) );
add_shortcode( 'showlist', array( 'SWER_aptools', 'showlist' ) );

class SWER_aptools_admin{

    function __construct(){
        add_meta_box( 'aptools_archive_link', 'Archive Link', array( &$this, 'aptools_custom_metabox'), 'page', 'side', 'core' );        
    }

    function manage_pages_columns( $post_columns ){
        $post_columns['aptools'] = 'Archive Link';
        return $post_columns;
    }
    
    function manage_pages_custom_column( $column, $post_id ){
        $selected = get_post_meta( $post_id, 'aptools_archive_link', true );        
        print_r($selected);
        $args = array(
            'selected'          => $selected,
            'show_count'         => 0,
            'hide_empty'         => 1,
            'hierarchical'       => 1,
            'id'                 => 'aptools-custom-'.$post_id,
            'taxonomy'           => 'category'
        );
        wp_dropdown_categories( $args );
    }
    
    function aptools_custom_metabox( $post ){
        wp_nonce_field( plugin_basename( __FILE__ ), 'aptools-nonce' );
        
        $selected = get_post_meta( $post->ID, 'aptools_archive_link', true );
        print_r($selected);
        $args = array(
            'selected'          => $selected,
            'show_count'        => 0,
            'hide_empty'        => 1,
            'hierarchical'      => 1,
            'show_option_none' => '(None)',
            'name'              => 'aptools-metabox',
            'id'                => 'aptools-metabox',
            'taxonomy'          => 'category'
        );
        wp_dropdown_categories( $args );
    }
    
    
    // update logic, same for manage_posts_custom_columns
    function save_post( $post_id ){
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;

        if ( !wp_verify_nonce( $_POST[ 'aptools-nonce' ], plugin_basename( __FILE__ ) ) )
        return;
        
        if ( 'page' == $_POST['post_type'] ){
           if ( !current_user_can( 'edit_page', $post_id ) )
               return;
         }else{
           if ( !current_user_can( 'edit_post', $post_id ) )
               return;
         }
          
        if( $_POST ):
        update_post_meta( $post_id, 'aptools_archive_link', $_POST['aptools-metabox'] );
        endif;
    }
    
    
    function category_add_form_fields( $tag ){
        $args = array(
            'selected'         => 0,
            'echo'             => 0,
            'name'             => 'aptools_page_id',
            'show_option_none' => '(None)'
        );
            
        echo '
            <div class="form-field">
            	<label for="tag-description">'._x('Link to Page', 'Taxonomy Description').'</label>
            	'.wp_dropdown_pages( $args ).'
            	<p>'._('The description is not prominent by default; however, some themes may show it.').'</p>
            </div>
        ';
    }
    
    function category_edit_form_fields( $tag, $taxonomy ){

        $query_args = array(
            'post_type' => 'page',
            'meta_key' => 'aptools_archive_link',
            'meta_value' => $tag,
            'posts_per_page' => 1
        );

        $pages = new WP_Query( $query_args );
        if( $pages->have_posts() ):
            while( $pages->have_posts() ):
                $pages->the_post();       
                #echo 'This category is linked with <a href="'.admin_url('post.php?post='.get_the_ID().'&action=edit').'">'.get_the_title().'</a>';
                
                $pages_args = array(
                    'selected'         => get_the_ID(),
                    'echo'             => 0,
                    'name'             => 'aptools_page_id',
                    'show_option_none' => '(None)'
                );


                echo '
            	<tr class="form-field">
        			<th scope="row" valign="top"><label for="aptools_link"></label></th>
        			<td>'.wp_dropdown_pages( $pages_args ).'<br />
        			<span class="description"></span>
        			</td>
        		</tr>            	
                ';
            endwhile;
        else:
            echo 'no page';
        endif;
        wp_reset_postdata();
    }
    
}

function call_SWER_aptools_admin(){
    return new SWER_aptools_admin();
}
#if ( is_admin() )
#    add_action( 'post-new.php', 'call_SWER_aptools_admin' );

add_filter( 'manage_pages_columns', array( 'SWER_aptools_admin', 'manage_pages_columns') );
add_action( 'manage_pages_custom_column', array( 'SWER_aptools_admin', 'manage_pages_custom_column'), 10, 2);

add_action( 'add_meta_boxes', 'call_SWER_aptools_admin' );
add_action( 'save_post', array( 'SWER_aptools_admin', 'save_post' ) );

add_action( 'category_add_form_fields', array( 'SWER_aptools_admin', 'category_add_form_fields' ) );
add_action( 'category_edit_form_fields', array( 'SWER_aptools_admin', 'category_edit_form_fields' ) );


/*
class SWER_aptools_setup{

    function admin_init() {
        // settings
    }

    function admin_menu() {
        // pages
    }
}
add_action( 'admin_init', array( 'SWER_aptools_setup', 'admin_init' ) );
add_action( 'admin_menu', array( 'SWER_aptools_setup', 'admin_menu' ) );
*/
?>