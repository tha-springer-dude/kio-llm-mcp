<?php

add_action( 'wp_abilities_api_init', 'my_post_get_register' );

function my_post_get_register() {

    wp_register_ability(
        'mykiomcp/get-post',
        array(
            'label'       => 'Custom Get Post',
            'description' => 'Retrieves a WordPress post by ID.',
            'category'    => 'site',

            'execute_callback'    => 'my_custom_get_post',
            'permission_callback' => 'my_custom_get_post_permission',

            'input_schema' => array(
                'type'       => 'object',
                'properties' => array(
                    'post_id' => array(
                        'type' => 'integer',
                    ),
                ),
                'required' => array( 'post_id' ),
            ),

            'output_schema' => array(
                'type'       => 'object',
                'properties' => array(
                    'id' => array(
                        'type' => 'integer',
                    ),
                    'title' => array(
                        'type' => 'string',
                    ),
                    'content' => array(
                        'type' => 'string',
                    ),
                    'status' => array(
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

function my_custom_get_post_permission() {
    return current_user_can( 'read' );
}

function my_custom_get_post( $input ) {

    $post = get_post( $input['post_id'] );

    if ( ! $post ) {
        return array(
            'error' => 'Post not found.',
        );
    }

    return array(
        'id'      => $post->ID,
        'title'   => $post->post_title,
        'content' => $post->post_content,
        'status'  => $post->post_status,
    );
}