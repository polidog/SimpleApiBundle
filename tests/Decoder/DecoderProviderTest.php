<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\Test\Decoder;

use PHPUnit\Framework\TestCase;
use Polidog\SimpleApiBundle\Decoder\DecoderInterface;
use Polidog\SimpleApiBundle\Decoder\DecoderProvider;

class DecoderProviderTest extends TestCase
{
    private $decoder;

    protected function setUp(): void
    {
        $this->decoder = $this->prophesize(DecoderInterface::class);
    }

    public function testCheckSupports(): void
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

    public function testCheckNoSupportedFormat(): void
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

    public function testGetDecoder(): void
    {
        $format = 'json';

        $this->decoder->support($format)
            ->willReturn(true);

        $decoderProvider = new DecoderProvider([]);
        $decoderProvider->add($this->decoder->reveal());

        $decoder = $decoderProvider->getDecoder($format);
        $this->assertInstanceOf(DecoderInterface::class, $decoder);

        $this->decoder->support($format)
            ->shouldHaveBeenCalled();
    }

    /**
     * @expectedException \Polidog\SimpleApiBundle\Exception\UnsupportedMediaTypeHttpException
     */
    public function testNoSupportedGetDecoder(): void
    {
        $format = 'xml';

        $this->decoder->support($format)
            ->willReturn(false);

        $decoderProvider = new DecoderProvider([$this->decoder->reveal()]);
        $decoderProvider->getDecoder($format);
    }
}
