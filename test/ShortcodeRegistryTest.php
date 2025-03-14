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
use PHPUnit\Framework\Constraint\Callback;
use PHPUnit\Framework\TestCase;

#[CoversClass(ShortcodeRegistry::class)]
class ShortcodeRegistryTest extends TestCase
{
    use PHPMock;

    private ShortcodeRegistry $registry;

    #[Before]
    public function setUp(): void
    {
        $this->registry = new ShortcodeRegistry();
    }

    private function capture(&$destination): Callback
    {
        return $this->callback(function ($source) use (&$destination) {
            $destination = $source;

            return true;
        });
    }

    #[Test]
    public function shouldRegisterShortcode(): void
    {
        $addShortcodeMock = $this->getFunctionMock(__NAMESPACE__, 'add_shortcode');
        $shortcodeMock = $this->getMockBuilder(Shortcode::class)->getMock();

        $addShortcodeMock
            ->expects($this->once())
            ->with('tag', $this->isCallable());

        $shortcodeMock->method('tag')->willReturn('tag');

        $this->registry->register($shortcodeMock);
    }

    #[Test]
    public function shouldRegisterShortcodeRenderCallback(): void
    {
        $addShortcodeMock = $this->getFunctionMock(__NAMESPACE__, 'add_shortcode');
        $shortcodeAttsMock = $this->getFunctionMock(__NAMESPACE__, 'shortcode_atts');
        $shortcodeMock = $this->getMockBuilder(Shortcode::class)->getMock();

        $addShortcodeMock
            ->expects($this->once())
            ->with('tag', $this->capture($renderCallback));
        $shortcodeAttsMock
            ->expects($this->once())
            ->with($this->anything())
            ->willReturn([]);

        $shortcodeMock->method('tag')->willReturn('tag');

        $this->registry->register($shortcodeMock);

        $renderCallback([], '');
    }

    #[Test]
    public function shouldUseShortcodeAttsFunctionToResolveAttributeValues(): void
    {
        $addShortcodeMock = $this->getFunctionMock(__NAMESPACE__, 'add_shortcode');
        $shortcodeAttsMock = $this->getFunctionMock(__NAMESPACE__, 'shortcode_atts');
        $shortcodeMock = $this->getMockBuilder(Shortcode::class)->getMock();
        $tag = 'tag';
        $defaultAttributes = ['key' => 'default'];
        $attributes = ['key' => 'value'];
        $shortcodeAttsResult = ['key' => 'result'];
        $renderResult = 'rendered';

        $addShortcodeMock
            ->expects($this->once())
            ->with($this->anything(), $this->capture($renderCallback));
        $shortcodeAttsMock
            ->expects($this->once())
            ->with($defaultAttributes, $attributes, $tag)
            ->willReturn($shortcodeAttsResult);

        $shortcodeMock->method('tag')->willReturn($tag);
        $shortcodeMock->method('defaultAttributes')->willReturn($defaultAttributes);
        $shortcodeMock->method('render')->with($shortcodeAttsResult, null)->willReturn($renderResult);

        $this->registry->register($shortcodeMock);

        $this->assertEquals($renderResult, $renderCallback($attributes, ''));
    }

    #[Test]
    public function shouldUseEmptyAttributesIfStringIsProvided(): void
    {
        $addShortcodeMock = $this->getFunctionMock(__NAMESPACE__, 'add_shortcode');
        $shortcodeAttsMock = $this->getFunctionMock(__NAMESPACE__, 'shortcode_atts');
        $shortcodeMock = $this->getMockBuilder(Shortcode::class)->getMock();

        $addShortcodeMock
            ->expects($this->once())
            ->with($this->anything(), $this->capture($renderCallback));
        $shortcodeAttsMock
            ->expects($this->once())
            ->with($this->anything(), [])
            ->willReturn([]);

        $shortcodeMock->method('tag')->willReturn('');
        $shortcodeMock->method('defaultAttributes')->willReturn([]);

        $this->registry->register($shortcodeMock);

        $renderCallback('', '');
    }

    #[Test]
    public function shouldUseNullContentIfEmpty(): void
    {
        $addShortcodeMock = $this->getFunctionMock(__NAMESPACE__, 'add_shortcode');
        $shortcodeAttsMock = $this->getFunctionMock(__NAMESPACE__, 'shortcode_atts');
        $shortcodeMock = $this->getMockBuilder(Shortcode::class)->getMock();

        $addShortcodeMock
            ->expects($this->once())
            ->with($this->anything(), $this->capture($renderCallback));
        $shortcodeAttsMock
            ->expects($this->once())
            ->with($this->anything(), $this->anything())
            ->willReturn([]);

        $shortcodeMock->method('tag')->willReturn('');
        $shortcodeMock->method('defaultAttributes')->willReturn([]);
        $shortcodeMock->method('render')->with($this->anything(), null);

        $this->registry->register($shortcodeMock);

        $renderCallback([], '');
    }
}
