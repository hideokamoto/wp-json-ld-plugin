<?php
/**
 * @package Structured Data of JSON-LD
 * @version 2.1
 */
/*
Plugin Name: Structured Data of JSON-LD
Plugin URI: http://wordpress.org/plugins/ejls-easy-json-ld-setter/
Description: Set Structured Data of "JSON-LD" to your WebSite.schema type that you can use is "Article","Person","WebSite" and "searchAction".
Author: Hidetaka Okamoto
Version: 2.1
Author URI: http://wp-kyoto.net/
*/
add_action('wp_footer','ejls_insert_json_ld');

function ejls_get_article () {
    if (is_page() || is_single()) {
        if (have_posts()) : while (have_posts()) : the_post();
            $contentArr['@type'] = 'Article';
            $contentArr['headline'] = get_the_title();
            $time = strtotime( get_the_time('c') );
            $contentArr['datePublished'] = date( 'c', $time );

            $contentArr['image'] = ejls_post_thumbnail();
            $contentArr['url'] = get_permalink();
            $contentArr['articleBody'] = get_the_content();

            $contentArr['author']['@type'] = 'Person';
            $contentArr['author']['name']  = get_the_author();

            $contentArr['publisher']['@type'] = 'Organization';
            $contentArr['publisher']['name']  = get_bloginfo('name');

        endwhile; endif;
        rewind_posts();
        return $contentArr;
    }
}

function ejls_catch_that_image() {
    global $post;
    if ( preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches ) ) {
        $ejls_first_img = $matches[1][0];
    } else {
        $ejls_first_img = false;
    }
    return $ejls_first_img;
}

function ejls_post_thumbnail() {
    if ( get_post_thumbnail_id() ) {
        $ejls_img = wp_get_attachment_url( get_post_thumbnail_id() );
    } elseif( ejls_catch_that_image() ) {
        $ejls_img = ejls_catch_that_image();
    } else {
        $ejls_img = false;
    }

    if (!$ejls_img) {
        $ejls_img = plugin_dir_url( __FILE__ ). "dummyimage.png";
    }

    return $ejls_img;
}

function ejls_get_search_Action($homeUrl){
    $contentArr = array(
        "@type"      => "SearchAction",
        "target"     => "{$homeUrl}/?s={search_term}",
        "query-input"=> "required name=search_term"
    );
    return $contentArr;
}

function ejls_insert_json_ld(){
    $homeUrl = get_home_url();

    $contentArr = array(
        "@context" => "http://schema.org",
    );
    if (is_front_page()) {
        $contentArr['@type']            = "WebSite";
        $contentArr['url']              = $homeUrl;
        $contentArr['potentialAction']  = ejls_get_search_Action($homeUrl);
    } elseif (is_page() || is_single()) {
        $contentArr['@graph'] = ejls_get_article();
    }

    $jsonld = json_encode($contentArr, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

    echo '<script type="application/ld+json">';
    echo $jsonld;
    echo '</script>';
}

?>