<?php

/*
 * Copyright (c) 2025 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace N7e\WordPress;

use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Shortcode::class)]
final class ShortcodeTest extends TestCase
{
    use PHPMock;

    private Fixtures\Shortcode $shortcode;

    #[Before]
    public function setUp(): void
    {
        $this->shortcode = new Fixtures\Shortcode();
    }

    #[Test]
    public function shouldProduceShortcodeTag(): void
    {
        $this->assertEquals('tag', $this->shortcode->tag());
    }

    #[Test]
    public function shouldProduceShortcodeDefaultAttributes(): void
    {
        $this->assertEquals(['key' => 'value'], $this->shortcode->defaultAttributes());
    }

    #[Test]
    public function shouldProduceStringRepresentationOfShortcode(): void
    {
        $this->assertEquals('', $this->shortcode->render([], ''));
    }
}
