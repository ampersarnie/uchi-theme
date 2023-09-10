<?php

namespace Ampersarnie\WP\Uchi;

/**
 * Handles loading in scripts and minor boots.
 *
 * @author Paul Taylor <paul.taylor@hey.com>
 */
class Loader
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [ $this, 'enqueueAssets' ]);
        add_filter('body_class', [$this, 'bodyClassCategories']);
    }

    /**
     * Enqueues any assets required for the front-end.
     *
     * @author Paul Taylor <paul.taylor@hey.com>
     * @return void
     */
    public function enqueueAssets(): void
    {
        if (is_admin() && ! is_customize_preview()) {
            return;
        }

        wp_enqueue_script(
            'wp-theme-script',
            get_template_directory_uri() . '/dist/scripts/' . UCHI_FRONTEND_JS,
            UCHI_FRONTEND_DEPENDENCIES,
            UCHI_VERSION,
            [
                'in_footer' => true
            ]
        );

        wp_enqueue_style(
            'wp-theme-style',
            get_template_directory_uri() . '/dist/styles/' . UCHI_FRONTEND_CSS,
            UCHI_FRONTEND_DEPENDENCIES,
            UCHI_VERSION,
            'all'
        );
    }

    /**
     * Add category slugs of the current post as
     * css classes to the output body when viewing
     * a single page.
     *
     * @author Paul Taylor <paul.taylor@hey.com>
     * @param  array $classes An array of body class names.
     * @return array
     */
    public function bodyClassCategories(array $classes): array
    {
        if (!is_single()) {
            return $classes;
        }

        $categories = get_the_category();

        $categories = array_map(function ($category) {
            return "category-$category->slug";
        }, $categories);

        return [
            ...$classes,
            ...$categories,
        ];
    }
}
