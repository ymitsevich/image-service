<?php

namespace App\Service\Image\Beautifier;

use App\Common\RandomDataGenerator;

class DashImageNameConverter implements ImageNameConverter
{
    private const SLUG_PART_DELIMITER = '-';

    public function __construct(private readonly RandomDataGenerator $randomDataGenerator)
    {
    }

    public function convertToBeautified(string $imageName, string $modifiersParamsString): string
    {
        $imageName = strtolower($imageName);
        $basename = pathinfo($imageName, PATHINFO_FILENAME);
        $extensions = pathinfo($imageName, PATHINFO_EXTENSION);
        $slug = $basename . self::SLUG_PART_DELIMITER .
            $this->randomDataGenerator->generateIdByString($imageName . $modifiersParamsString) .
            ".$extensions";

        return $slug;
    }
}
