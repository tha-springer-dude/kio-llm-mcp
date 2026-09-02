<?php

add_action( 'wp_abilities_api_init', 'my_post_search_register' );

function my_post_search_register() {

    wp_register_ability(
        'mykiomcp/search-posts',
        array(
            'label'       => 'Custom Search Posts',
            'description' => 'Custom searches WordPress posts by title.',
            'category'    => 'site',

            'execute_callback'    => 'my_custom_post_search',
            'permission_callback' => 'my_custom_post_search_permission',

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

function my_custom_post_search_permission() {
    return current_user_can( 'read' );
}

function my_custom_post_search( $input ) {

    $posts = get_posts(
        array(
            'post_type'      => 'post',
            'post_status'    => 'publish',
            's'              => $input['search'],
            'posts_per_page' => -1,
        )
    );

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