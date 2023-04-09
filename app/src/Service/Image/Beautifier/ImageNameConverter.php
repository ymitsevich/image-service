<?php

namespace App\Service\Image\Beautifier;

interface ImageNameConverter
{
    public function convertToBeautified(string $imageName): string;
}
