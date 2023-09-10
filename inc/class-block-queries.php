<?php

namespace Ampersarnie\WP\Uchi;

class BlockQueries
{
    /**
     * Placeholder for the current post id when querying.
     */
    public const CURRENT_POST_PARAM = 'currentPost';

    /**
     * Placeholder for the current post type when querying.
     */
    public const CURRENT_TYPE_PARAM = 'currentPostType';

    /**
     * Class constructor.
     */
    public function __construct()
    {
        add_filter('query_loop_block_query_vars', [ $this, 'queryVars' ], 10, 2);
        add_filter('render_block', [ $this, 'emptyFeaturedImage'], 10, 2);
        add_filter('rest_request_before_callbacks', [$this, 'RESTOverrideExcludeParamSchemaError'], 10, 3);
    }

    /**
     * Get accepted params for overide and exclusion.
     *
     * @author Paul Taylor <paul.taylor@hey.com>
     * @return array
     */
    public function getParams(): array
    {
        $params = [
            self::CURRENT_POST_PARAM,
            self::CURRENT_TYPE_PARAM,
        ];

        return $params;
    }

    /**
     * Override parameters that are part of the param
     * list by ignoring the errors reported by the REST
     * API when they apply to those params.
     *
     * @author Paul Taylor <paul.taylor@hey.com>
     * @param  mixed $response Result to send to the client.
     * @param  array $handler Route handler used for the request.
     * @param  WP_REST_Request $request Request used to generate the response.
     */
    public function RESTOverrideExcludeParamSchemaError($response, array $handler, \WP_REST_Request $request)
    {
        $request_params = $request->get_param('exclude');

        if (!is_array($request_params) || !in_array($this->getParams(), $request_params)) {
            return $response;
        }

        if (!is_wp_error($response)) {
            return $response;
        }

        $error_data = $response->get_error_data();

        if (!isset($error_data['status']) || $error_data['status'] !== 400) {
            return $response;
        }

        if (!isset($error_data['details']['exclude'])) {
            return $response;
        }

        if (
            !isset($error_data['details']['exclude']['code'])
            || $error_data['details']['exclude']['code'] !== 'rest_invalid_type'
        ) {
            return $response;
        }

        return null;
    }

    /**
     * Replace the query with the preset param as a placeholder.
     *
     * @author Paul Taylor <paul.taylor@hey.com>
     * @param  array $query Array containing parameters for WP_Query as parsed by the block context.
     * @param  WP_Block $block Block instance.
     * @return array
     */
    public function queryVars(array $query, \WP_Block $block): array
    {
        $post_id = get_queried_object_id();
        $post_type = get_post_type($post_id);

        $query = $block->context['query'] ?? [];

        if (in_array("{{" . self::CURRENT_POST_PARAM . "}}", $query['exclude'], true)) {
            $query['post__not_in'][] = $post_id;
        }

        if ("{{" . self::CURRENT_TYPE_PARAM . "}}" === $query['postType']) {
            $query['post_type'] = $post_type;
        }

        return $query;
    }

    public function emptyFeaturedImage($content, $block)
    {
        if ($block['blockName'] !== 'core/post-featured-image') {
            return $content;
        }

        if (empty($content)) {
            return '<div class="empty-featured-image"></div>';
        }

        return $content;
    }
}
