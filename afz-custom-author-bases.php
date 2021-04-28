<?php
/**
* Plugin Name: Custom Author Bases
* Description: Add custom author bases depending on user roles.
* Version: 1.1.1
* Author: Álvaro Franz
* GitHub Plugin URI: https://github.com/alvarofranz/afz-custom-author-bases
**/

defined('ABSPATH') || exit;

// Add tag
add_action( 'init', 'afz_custom_author_bases_rewrite_tag' );
function afz_custom_author_bases_rewrite_tag(){
    global $wp_rewrite;
    $author_levels = array( 'user', 'bar', 'pub' );

    // Define the tag and use it in the rewrite rule
    add_rewrite_tag( '%author_level%', '(' . implode( '|', $author_levels ) . ')' );
    $wp_rewrite->author_base = '%author_level%';
}

// Filter
add_filter( 'author_rewrite_rules', 'afz_custom_author_bases_rewrite_filter' );
function afz_custom_author_bases_rewrite_filter( $author_rewrite_rules ){
    foreach( $author_rewrite_rules as $pattern => $substitution ) {
        if( FALSE === strpos( $substitution, 'author_name' ) ) {
            unset( $author_rewrite_rules[$pattern] );
        }
    }
    return $author_rewrite_rules;
}

// Generated links
add_filter( 'author_link', 'afz_custom_author_bases_link', 10, 2 );
function afz_custom_author_bases_link( $link, $author_id ){

    // Default
    $author_level = 'user';

    // Get the user object.
    $user = get_userdata( $author_id );

    // Get all the user roles as an array.
    $user_roles = $user->roles;

    // Check if the role you're interested in, is present in the array.
    if ( in_array( 'bar', $user_roles, true ) ) {
        $author_level = 'bar';
    }

    // Check if the role you're interested in, is present in the array.
    if ( in_array( 'pub', $user_roles, true ) ) {
        $author_level = 'pub';
    }

    $link = str_replace( '%author_level%', $author_level, $link );
    return $link;
}