<?php

add_action( 'wp_abilities_api_init', 'my_post_update_register' );

function my_post_update_register() {

    wp_register_ability(
        'mykiomcp/update-post',
        array(
            'label'       => 'Custom Update Post',
            'description' => 'Updates the content of an existing WordPress post.',
            'category'    => 'site',

            'execute_callback'    => 'my_custom_update_post',
            'permission_callback' => 'my_custom_update_post_permission',

            'input_schema' => array(
                'type'       => 'object',
                'properties' => array(
                    'post_id' => array(
                        'type' => 'integer',
                    ),
                    'content' => array(
                        'type' => 'string',
                    ),
                ),
                'required' => array( 'post_id', 'content' ),
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

function my_custom_update_post_permission() {
    return current_user_can( 'edit_posts' );
}

function my_custom_update_post( $input ) {

    $post = get_post( $input['post_id'] );

    if ( ! $post ) {
        return array(
            'error' => 'Post not found.',
        );
    }

    $updated = wp_update_post(
        array(
            'ID'           => $input['post_id'],
            'post_content' => $input['content'],
        ),
        true
    );

    if ( is_wp_error( $updated ) ) {
        return array(
            'error' => $updated->get_error_message(),
        );
    }

    return array(
        'id'     => $updated,
        'title'  => get_the_title( $updated ),
        'status' => get_post_status( $updated ),
    );
}