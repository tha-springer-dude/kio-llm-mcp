<?php
/**
 * KIO MCP – Find Posts by Category Name
 *
 * Registers an MCP ability for finding WordPress posts by category name.
 */

add_action( 'wp_abilities_api_init', 'my_post_find_by_category_name_register' );

function my_post_find_by_category_name_register() {

    wp_register_ability(
        'mykiomcp/find-posts-by-category-name',
        array(
            'label'       => 'Find Posts by Category Name',
            'description' => 'Finds WordPress posts in a category by category name.',
            'category'    => 'site',

            'execute_callback'    => 'my_custom_find_posts_by_category_name',
            'permission_callback' => 'my_custom_find_posts_by_category_name_permission',

            'input_schema' => array(
                'type'       => 'object',
                'properties' => array(
                    'category_name' => array(
                        'type' => 'string',
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
                'required' => array( 'category_name' ),
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

function my_custom_find_posts_by_category_name_permission() {
    return current_user_can( 'read' );
}

function my_custom_find_posts_by_category_name( $input ) {

    $category = get_term_by( 'name', $input['category_name'], 'category' );

    if ( ! $category ) {
        return array(
            'results' => array(),
        );
    }

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'cat'            => $category->term_id,
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