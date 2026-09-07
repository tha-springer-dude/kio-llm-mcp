<?php
/**
 * KIO MCP – Find Category by ID
 *
 * Registers an MCP ability for finding a WordPress category by ID.
 */

add_action( 'wp_abilities_api_init', 'my_category_find_by_id_register' );

function my_category_find_by_id_register() {

    wp_register_ability(
        'mykiomcp/find-category-by-id',
        array(
            'label'       => 'Find Category by ID',
            'description' => 'Finds a WordPress category by ID.',
            'category'    => 'site',

            'execute_callback'    => 'my_custom_category_find_by_id',
            'permission_callback' => 'my_custom_category_find_by_id_permission',

            'input_schema' => array(
                'type'       => 'object',
                'properties' => array(
                    'id' => array(
                        'type' => 'integer',
                    ),
                ),
                'required' => array( 'id' ),
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

function my_custom_category_find_by_id_permission() {
    return current_user_can( 'read' );
}

function my_custom_category_find_by_id( $input ) {

    $category = get_category( $input['id'] );

    if ( ! $category || is_wp_error( $category ) ) {
        return array(
            'error' => 'Category not found.',
        );
    }

    return array(
        'id'   => $category->term_id,
        'name' => $category->name,
    );
}