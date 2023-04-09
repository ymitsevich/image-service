<?php

namespace App\Tests\Integration\Service\Image\Modifier;

use App\Service\Image\Image;
use App\Service\Image\Modifier\CropModifier;
use App\Service\Image\Modifier\Dto\CropModifierParameters;
use PHPUnit\Framework\TestCase;

class CropModifierTest extends TestCase
{
    private readonly CropModifier $service;

    /**
     * @dataProvider input
     */
    public function testProcess_crop(array $args, array $referencingSize)
    {
        $modifierParameters = new CropModifierParameters($args);
        $this->service = new CropModifier($modifierParameters);
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
            'normalCrop' => [[10, 10, 100, 100], [100, 100]],
            'moreThanImageSizeCrop' => [[10, 10, 2000, 2000], [1808, 752]],
        ];
    }
}
