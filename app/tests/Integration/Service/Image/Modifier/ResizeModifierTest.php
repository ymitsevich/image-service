<?php

namespace App\Tests\Integration\Service\Image\Modifier;

use App\Service\Image\Image;
use App\Service\Image\Modifier\Dto\ResizeModifierParameters;
use App\Service\Image\Modifier\ResizeModifier;
use PHPUnit\Framework\TestCase;

class ResizeModifierTest extends TestCase
{
    private readonly ResizeModifier $service;

    /**
     * @dataProvider input
     */
    public function testProcess_resize(array $args, array $referencingSize)
    {
        $modifierParameters = new ResizeModifierParameters($args);
        $this->service = new ResizeModifier($modifierParameters);
        $imageName = 'sample.png';
        $blob = file_get_contents(__DIR__ . '/../../../samples/sample.png');
        $image = (new Image($imageName))->setBlob($blob);
        $assertingImage = $this->service->process($image);
        $this->assertSame($referencingSize[0], $assertingImage->getWidth());
        $this->assertSame($referencingSize[1], $assertingImage->getHeight());
    }

    public function input(): array
    {
        return [
            'normalResize' => [[10, 10], [10, 10]],
            'moreThanImageSizeResize' => [[2000, 2000], [2000, 2000]],
        ];
    }
}
