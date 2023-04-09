<?php

namespace App\Tests\Unit\Service\Image\Beautifier;

use App\Common\RandomDataGenerator;
use App\Service\Image\Beautifier\DashImageNameConverter;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class DashImageNameConverterTest extends TestCase
{
    use ProphecyTrait;

    private readonly DashImageNameConverter $service;
    private readonly RandomDataGenerator|ObjectProphecy $randomDataGenerator;

    public function setUp(): void
    {
        $this->randomDataGenerator = $this->prophesize(RandomDataGenerator::class);
        $this->service = new DashImageNameConverter(
            $this->randomDataGenerator->reveal(),
        );
    }

    /**
     * @dataProvider input
     */
    public function testConvertToBeautified(string $imageName, string $referencingName = null)
    {
        $this->randomDataGenerator->generateId($imageName)
            ->shouldBeCalledOnce()
            ->willReturn('dummyRandomString');
        $assertingName = $this->service->convertToBeautified($imageName);

        $this->assertSame($referencingName, $assertingName);
    }

    public function input(): array
    {
        return [
            ['test.png', 'test-dummyRandomString.png'],
            ['sample.jpg', 'sample-dummyRandomString.jpg'],
        ];
    }
}
