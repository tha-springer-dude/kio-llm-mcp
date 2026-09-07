<?php
/**
 * KIO MCP – Find Posts by Title
 *
 * Registers an MCP ability for finding WordPress posts by title.
 */

add_action( 'wp_abilities_api_init', 'my_post_find_by_title_register' );

function my_post_find_by_title_register() {

    wp_register_ability(
        'mykiomcp/find-posts-by-title',
        array(
            'label'       => 'Find Posts by Title',
            'description' => 'Finds WordPress posts by title. By default, searches published posts only. Use status "draft" or "any" when needed.',
            'category'    => 'site',

            'execute_callback'    => 'my_custom_find_posts_by_title',
            'permission_callback' => 'my_custom_find_posts_by_title_permission',

            'input_schema' => array(
                'type'       => 'object',
                'properties' => array(
                    'title' => array(
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
                'required' => array( 'title' ),
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

function my_custom_find_posts_by_title_permission() {
    return current_user_can( 'read' );
}

function my_custom_find_posts_by_title( $input ) {

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
    );

    if ( ! empty( $input['status'] ) ) {
        $args['post_status'] = $input['status'];
    } else {
        $args['post_status'] = 'publish';
    }

    $posts   = get_posts( $args );
    $results = array();

    foreach ( $posts as $post ) {

        if ( stripos( $post->post_title, $input['title'] ) !== false ) {

            $results[] = array(
                'id'    => $post->ID,
                'title' => $post->post_title,
            );
        }
    }

    return array(
        'results' => $results,
    );
}