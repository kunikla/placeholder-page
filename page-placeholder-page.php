<?php

/*
 * Template Name: Placeholder page
 * Description: Redirects page to first submenu item
 */

// Find list of menus registered with current theme
$registered_menus = get_registered_nav_menus();

// Get all locations
$locations = get_nav_menu_locations();

if ( !empty( $registered_menus ) ) {

    $child_found = FALSE;

    // Search each menu
    foreach ( $registered_menus as $location => $description ) {

        // Get menu items for each registered menu

        // Get object id by location
        $object = wp_get_nav_menu_object( $locations[$location] );
        $menu_items = wp_get_nav_menu_items( $object->name );

        // If menu empty, on to the next one
        if ( empty ($menu_items) ) {
            break;
        }

        // Search the menu items
        // We are assuming items are returned in ascending order, the default
        $parent_id = 0;
        foreach ( $menu_items as $menu_item ) {

            if ($parent_id == 0) {  // looking for parent
                if ( $menu_item->object_id == $post->ID ) {
                    $parent_id = $menu_item->db_id;
                }

            } else {  // looking for child
                if ( $menu_item->menu_item_parent == $parent_id ) {
                    $child_found = TRUE;
                    wp_redirect( $menu_item->url );
                    break;
                }
            }
        }

        if ( $child_found ) {
            break;
        }
    }
}

/* Fell through; direct to home page if no children found */
if ( !$child_found) {
    wp_redirect( get_home_url() );
}
