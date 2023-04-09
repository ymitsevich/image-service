<?php

namespace App\Tests\Unit\Service\Image\Pipeline;

use App\Service\Image\Exception\PipelineException;
use App\Service\Image\Modifier\CropModifier;
use App\Service\Image\Modifier\ResizeModifier;
use App\Service\Image\Pipeline\ModifierPipelineFactory;
use App\Service\Image\Pipeline\Pipeline;
use PHPUnit\Framework\TestCase;

class ModifierPipelineFactoryTest extends TestCase
{

    public function testCreateByArguments()
    {
        $factory = new ModifierPipelineFactory();
        $pipeline = $factory->createByArguments([
            'resize' => '100,200',
            'crop' => '50,50,100,100',
        ]);

        $this->assertInstanceOf(Pipeline::class, $pipeline);

        $modifiers = $pipeline->getModifiers();
        $this->assertCount(2, $modifiers);

        $resizeModifier = $modifiers[0];
        $this->assertInstanceOf(ResizeModifier::class, $resizeModifier);

        $cropModifier = $modifiers[1];
        $this->assertInstanceOf(CropModifier::class, $cropModifier);
    }

    public function testCreateByArguments_invalidModifier_exception()
    {
        $factory = new ModifierPipelineFactory();
        $this->expectException(PipelineException::class);
        $factory->createByArguments([
            'invalid_modifier' => 'invalid_arguments',
        ]);
    }
}
