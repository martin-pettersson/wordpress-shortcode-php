<?php

/*
 * Copyright (c) 2025 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace N7e\WordPress;

/**
 * Represents a WordPress shortcode.
 */
class ShortcodeRegistry
{
    /**
     * Register given shortcode.
     *
     * @param \N7e\WordPress\Shortcode $shortcode Arbitrary shortcode.
     */
    public function register(Shortcode $shortcode): void
    {
        add_shortcode(
            $shortcode->tag(),
            fn($attributes, $content) => $this->render($shortcode, $attributes, $content)
        );
    }

    /**
     * Produces a string representation of a given shortcode.
     *
     * @param \N7e\WordPress\Shortcode $shortcode Arbitrary shortcode.
     * @param array|string $attributes Associated attributes.
     * @param string $content Associated content.
     * @return string String representation of the given shortcode.
     */
    private function render(Shortcode $shortcode, array|string $attributes, string $content): string
    {
        return $shortcode->render(
            shortcode_atts(
                $shortcode->defaultAttributes(),
                is_string($attributes) ? [] : $attributes,
                $shortcode->tag()
            ),
            strlen($content) > 0 ? $content : null
        );
    }
}
