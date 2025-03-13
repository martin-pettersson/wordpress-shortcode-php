<?php

/*
 * Copyright (c) 2025 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace N7e\WordPress\Fixtures;

use N7e\WordPress;
use Override;

final class Shortcode extends WordPress\Shortcode
{
    protected string $tag = 'tag';
    protected array $defaultAttributes = [
        'key' => 'value'
    ];

    /** {@inheritDoc} */
    #[Override]
    public function render(array $attributes, ?string $content): string
    {
        return '';
    }
}
