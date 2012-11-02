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
    
    function manage_pages_custom_column(){
        $args = array(
            'show_count'         => 0,
            'hide_empty'         => 1,
            'hierarchical'       => 1,
            'taxonomy'           => 'category'
        );
        wp_dropdown_categories( $args );
    }


    function add_meta_boxes(){
    }
    
    function aptools_custom_metabox(){
        $args = array(
            'show_count'         => 0,
            'hide_empty'         => 1,
            'hierarchical'       => 1,
            'taxonomy'           => 'category'
        );
        wp_dropdown_categories( $args );
    }
    
    
    function save_post( $post_id ){
        // update logic, same for manage_posts_custom_columns
    	if( $_POST ):
    	    update_post_meta( $post_id, 'aptools_archive_link', "?");
    	endif;
    }
    
}

function call_SWER_aptools_admin(){
    return new SWER_aptools_admin();
}

add_filter( 'manage_pages_columns', array( 'SWER_aptools_admin', 'manage_pages_columns') );
add_action( 'manage_pages_custom_column', array( 'SWER_aptools_admin', 'manage_pages_custom_column'), 10, 2);
if ( is_admin() )
    add_action( 'load-post.php', 'call_SWER_aptools_admin' );

#add_action( 'add_meta_boxes', array( 'SWER_aptools_admin', 'add_meta_boxes') );
#add_action( 'save_post', array( 'SWER_aptools_admin', 'save_post' ) );



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