<?php
/**
 * KIO MCP – List Posts
 *
 * Registers an MCP ability for listing WordPress posts.
 */

add_action( 'wp_abilities_api_init', 'my_post_list_register' );

function my_post_list_register() {

    wp_register_ability(
        'mykiomcp/list-posts',
        array(
            'label'       => 'List Posts',
            'description' => 'Lists WordPress posts by status.',
            'category'    => 'site',

            'execute_callback'    => 'my_custom_post_list',
            'permission_callback' => 'my_custom_post_list_permission',

            'input_schema' => array(
                'type'       => 'object',
                'properties' => array(
                    'status' => array(
                        'type' => 'string',
                        'enum' => array(
                            'publish',
                            'draft',
                            'any',
                        ),
                    ),
                ),
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

function my_custom_post_list_permission() {
    return current_user_can( 'read' );
}

function my_custom_post_list( $input ) {

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

        $results[] = array(
            'id'    => $post->ID,
            'title' => $post->post_title,
        );
    }

    return array(
        'results' => $results,
    );
}