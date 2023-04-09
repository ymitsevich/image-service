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
    public function testConvertToBeautified(array $input, string $referencingName = null)
    {
        $this->randomDataGenerator->generateIdByString($input[0] . $input[1])
            ->shouldBeCalledOnce()
            ->willReturn('dummyRandomString');
        $assertingName = $this->service->convertToBeautified($input[0], $input[1]);

        $this->assertSame($referencingName, $assertingName);
    }

    public function input(): array
    {
        return [
            [['test.png', 'resize/10,200'], 'test-dummyRandomString.png'],
            [['sample.jpg','resize/10,10/crop/10,10,100,200'], 'sample-dummyRandomString.jpg'],
        ];
    }
}
