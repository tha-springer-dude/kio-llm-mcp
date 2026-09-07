<?php
/**
 * KIO MCP – List Categories
 *
 * Registers an MCP ability for listing WordPress categories.
 */

add_action( 'wp_abilities_api_init', 'my_category_list_register' );

function my_category_list_register() {

    wp_register_ability(
        'mykiomcp/list-categories',
        array(
            'label'       => 'List Categories',
            'description' => 'Lists all WordPress categories.',
            'category'    => 'site',

            'execute_callback'    => 'my_custom_category_list',
            'permission_callback' => 'my_custom_category_list_permission',

            'input_schema' => array(
                'type'       => 'object',
                'properties' => array(),
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

function my_custom_category_list_permission() {
    return current_user_can( 'read' );
}

function my_custom_category_list() {

    $categories = get_categories();
    $results    = array();

    foreach ( $categories as $category ) {

        $results[] = array(
            'id'   => $category->term_id,
            'name' => $category->name,
        );
    }

    return array(
        'results' => $results,
    );
}