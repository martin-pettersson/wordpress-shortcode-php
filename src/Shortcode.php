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
abstract class Shortcode
{
    /**
     * Shortcode tag.
     *
     * @var string
     */
    protected string $tag;

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected array $defaultAttributes;

    /**
     * Return the shortcode tag.
     *
     * @return string Shortcode tag.
     */
    public function tag(): string
    {
        return $this->tag;
    }

    /**
     * Return the default attribute values.
     *
     * @return array Default attribute values.
     */
    public function defaultAttributes(): array
    {
        return $this->defaultAttributes;
    }

    /**
     * Produce a string representation of the shortcode.
     *
     * @param array $attributes Shortcode attributes.
     * @param string|null $content Shortcode content.
     * @return string String representation of the shortcode.
     */
    abstract public function render(array $attributes, ?string $content): string;
}
