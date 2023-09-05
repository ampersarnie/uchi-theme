<?php

namespace Ampersarnie\WP\Uchi;

class BlockQueries
{
    public const CURRENT_POST_PARAM = 'currentPost';

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

    public function getParams(): array
    {
        $params = [
            self::CURRENT_POST_PARAM,
            self::CURRENT_TYPE_PARAM,
        ];

        return $params;
    }

    public function RESTOverrideExcludeParamSchemaError($response, $handler, $request)
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

    public function queryVars($query, $block)
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
