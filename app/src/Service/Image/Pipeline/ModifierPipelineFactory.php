<?php

namespace App\Service\Image\Pipeline;

use App\Service\Image\Exception\PipelineException;
use App\Service\Image\Modifier\CropModifier;
use App\Service\Image\Modifier\Modifier;
use App\Service\Image\Modifier\ResizeModifier;

use function array_key_exists;
use function explode;

class ModifierPipelineFactory
{
    public static array $modifiersFqnMap = [
        'resize' => ResizeModifier::class,
        'crop' => CropModifier::class,
    ];

    /**
     * @throws PipelineException
     */
    public function createByArguments(array $args): Pipeline
    {
        $pipeline = new Pipeline();
        foreach ($args as $key => $modifierArgs) {
            if (!array_key_exists($key, static::$modifiersFqnMap)) {
                throw new PipelineException();
            }
            $modifierClassFqn = static::$modifiersFqnMap[$key];

            $modifierArgs = explode(',', $modifierArgs);
            $modifierParametersFqn = $modifierClassFqn::getParametersFqn();
            $modifierParameters = new $modifierParametersFqn($modifierArgs);
            /** @var Modifier $modifier */
            $modifier = new $modifierClassFqn($modifierParameters);
            $pipeline->addModifier($modifier);
        }

        return $pipeline;
    }
}
