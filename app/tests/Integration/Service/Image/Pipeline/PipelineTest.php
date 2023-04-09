<?php

namespace App\Tests\Integration\Service\Image\Pipeline;

use App\Service\Image\Image;
use App\Service\Image\Modifier\CropModifier;
use App\Service\Image\Modifier\Dto\CropModifierParameters;
use App\Service\Image\Modifier\Dto\ResizeModifierParameters;
use App\Service\Image\Modifier\ResizeModifier;
use App\Service\Image\Pipeline\Pipeline;
use PHPUnit\Framework\TestCase;

class PipelineTest extends TestCase
{
    private readonly Pipeline $service;

    public function testProcess_pipeline()
    {
        $args = [100,100];
        $modifierParameters = new ResizeModifierParameters($args);
        $modifier1 = new ResizeModifier($modifierParameters);

        $args = [10,10,300,300];
        $modifierParameters = new CropModifierParameters($args);
        $modifier2 = new CropModifier($modifierParameters);

        $this->service = (new Pipeline())->addModifier($modifier1)->addModifier($modifier2);
        $imageName = 'sample.png';
        $blob = file_get_contents(__DIR__ . '/../../../samples/sample.png');
        $image = (new Image($imageName))->setBlob($blob);

        $assertingImage = $this->service->process($image);
        $this->assertSame(90, $assertingImage->getWidth());
        $this->assertSame(90, $assertingImage->getHeight());
    }
}
