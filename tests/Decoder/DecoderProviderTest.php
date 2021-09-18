<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\Test\Decoder;

use PHPUnit\Framework\TestCase;
use Polidog\SimpleApiBundle\Decoder\DecoderInterface;
use Polidog\SimpleApiBundle\Decoder\DecoderProvider;
use Prophecy\PhpUnit\ProphecyTrait;

class DecoderProviderTest extends TestCase
{
    use ProphecyTrait;

    private $decoder;

    final protected function setUp(): void
    {
        $this->decoder = $this->prophesize(DecoderInterface::class);
    }

    final public function testCheckSupports(): void
    {
        $format = 'json';

        $this->decoder->support($format)
            ->willReturn(true);

        $decoderProvider = new DecoderProvider([$this->decoder->reveal()]);
        $actual = $decoderProvider->supports($format);
        $this->assertTrue($actual);

        $this->decoder->support($format)
            ->shouldHaveBeenCalled();
    }

    final public function testCheckNoSupportedFormat(): void
    {
        $format = 'xml';

        $this->decoder->support($format)
            ->willReturn(false);

        $decoderProvider = new DecoderProvider([$this->decoder->reveal()]);
        $actual = $decoderProvider->supports($format);
        $this->assertFalse($actual);

        $this->decoder->support($format)
            ->shouldHaveBeenCalled();
    }

    final public function testGetDecoder(): void
    {
        $format = 'json';

        $this->decoder->support($format)
            ->willReturn(true);

        $decoderProvider = new DecoderProvider([]);
        $decoderProvider->add($this->decoder->reveal());

        $decoderProvider->getDecoder($format);

        $this->decoder->support($format)
            ->shouldHaveBeenCalled();
    }

    final public function testNoSupportedGetDecoder(): void
    {
        $this->expectException(\Polidog\SimpleApiBundle\Exception\UnsupportedMediaTypeHttpException::class);

        $format = 'xml';

        $this->decoder->support($format)
            ->willReturn(false);

        $decoderProvider = new DecoderProvider([$this->decoder->reveal()]);
        $decoderProvider->getDecoder($format);
    }
}
