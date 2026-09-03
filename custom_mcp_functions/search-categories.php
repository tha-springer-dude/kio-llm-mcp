<?php
/**
 * KIO MCP – Search Categories
 *
 * Registers an MCP ability for searching WordPress categories.
 */

add_action( 'wp_abilities_api_init', 'my_category_search_register' );

function my_category_search_register() {

    wp_register_ability(
        'mykiomcp/search-categories',
        array(
            'label'       => 'Custom Search Categories',
            'description' => 'Custom searches WordPress categories by name.',
            'category'    => 'site',

            'execute_callback'    => 'my_custom_category_search',
            'permission_callback' => 'my_custom_category_search_permission',

            'input_schema' => array(
                'type'       => 'object',
                'properties' => array(
                    'search' => array(
                        'type' => 'string',
                    ),
                ),
                'required' => array( 'search' ),
            ),

            'output_schema' => array(
                'type'       => 'object',
                'properties' => array(
                    'results' => array(
                        'type'  => 'array',
                        'items' => array(
                            'type'       => 'object',
                            'properties' => array(
                                'id' => array(
                                    'type' => 'integer',
                                ),
                                'name' => array(
                                    'type' => 'string',
                                ),
                            ),
                        ),
                    ),
                ),
            ),

            'meta' => array(
                'mcp' => array(
                    'public' => true,
                ),
            ),
        )
    );
}

function my_custom_category_search_permission() {
    return current_user_can( 'read' );
}

function my_custom_category_search( $input ) {

    $categories = get_categories();
    $results   = array();

    foreach ( $categories as $category ) {

        if ( stripos( $category->name, $input['search'] ) !== false ) {
            $results[] = array(
                'id'   => $category->term_id,
                'name' => $category->name,
            );
        }
    }

    return array(
        'results' => $results,
    );
}