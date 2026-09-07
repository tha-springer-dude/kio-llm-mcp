<?php
/**
 * KIO MCP – Find Category by Name
 *
 * Registers an MCP ability for finding a WordPress category by name.
 */

add_action( 'wp_abilities_api_init', 'my_category_find_by_name_register' );

function my_category_find_by_name_register() {

    wp_register_ability(
        'mykiomcp/find-category-by-name',
        array(
            'label'       => 'Find Category by Name',
            'description' => 'Finds a WordPress category by name.',
            'category'    => 'site',

            'execute_callback'    => 'my_custom_category_find_by_name',
            'permission_callback' => 'my_custom_category_find_by_name_permission',

            'input_schema' => array(
                'type'       => 'object',
                'properties' => array(
                    'name' => array(
                        'type' => 'string',
                    ),
                ),
                'required' => array( 'name' ),
            ),

            'output_schema' => array(
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

            'meta' => array(
                'mcp' => array(
                    'public' => true,
                ),
            ),
        )
    );
}

function my_custom_category_find_by_name_permission() {
    return current_user_can( 'read' );
}

function my_custom_category_find_by_name( $input ) {

    $categories = get_categories();

    foreach ( $categories as $category ) {

        if ( strcasecmp( $category->name, $input['name'] ) === 0 ) {
            return array(
                'id'   => $category->term_id,
                'name' => $category->name,
            );
        }
    }

    return array(
        'error' => 'Category not found.',
    );
}