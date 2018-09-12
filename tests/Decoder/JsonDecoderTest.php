<?php

namespace Polidog\SimpleApiBundle\Test\Decoder;


use PHPUnit\Framework\TestCase;
use Polidog\SimpleApiBundle\Decoder\JsonDecoder;

class JsonDecoderTest extends TestCase
{
    public function testSupport() : void
    {
        $jsonDecoder = new JsonDecoder();
        $this->assertTrue($jsonDecoder->support('json'));
    }

    public function testDecode() : void
    {
        $jsonDecoder = new JsonDecoder();
        $json = '{"uuid":"2113f671-e0ce-4a84-a4f5-4066f66c616a"}';
        $array = $jsonDecoder->decode($json);
        $this->assertSame(['uuid' => '2113f671-e0ce-4a84-a4f5-4066f66c616a'], $array);
    }
}