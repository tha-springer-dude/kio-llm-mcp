<?php
/**
 * KIO MCP – Find Posts by Category ID
 *
 * Registers an MCP ability for finding WordPress posts by category.
 */

add_action( 'wp_abilities_api_init', 'my_post_find_by_category_id_register' );

function my_post_find_by_category_id_register() {

    wp_register_ability(
        'mykiomcp/find-posts-by-category-id',
        array(
            'label'       => 'Find Posts by Category id',
            'description' => 'Finds WordPress posts in a category id.',
            'category'    => 'site',

            'execute_callback'    => 'my_custom_find_posts_by_category_id',
            'permission_callback' => 'my_custom_find_posts_by_category_id_permission',

            'input_schema' => array(
                'type'       => 'object',
                'properties' => array(
                    'category_id' => array(
                        'type' => 'integer',
                    ),
                    'status' => array(
                        'type' => 'string',
                        'enum' => array(
                            'publish',
                            'draft',
                            'any',
                        ),
                    ),
                ),
                'required' => array( 'category_id' ),
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
                                'title' => array(
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

function my_custom_find_posts_by_category_id_permission() {
    return current_user_can( 'read' );
}

function my_custom_find_posts_by_category_id( $input ) {

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'cat'            => $input['category_id'],
    );

    if ( ! empty( $input['status'] ) ) {
        $args['post_status'] = $input['status'];
    } else {
        $args['post_status'] = 'publish';
    }

    $posts   = get_posts( $args );
    $results = array();

    foreach ( $posts as $post ) {

        $results[] = array(
            'id'    => $post->ID,
            'title' => $post->post_title,
        );
    }

    return array(
        'results' => $results,
    );
}