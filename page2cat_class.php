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