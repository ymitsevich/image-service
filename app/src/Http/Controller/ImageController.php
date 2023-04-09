<?php

namespace App\Http\Controller;

use App\Http\Template\TemplateBuilder;
use App\Service\Image\Exception\ImageNotFoundException;
use App\Service\Image\ImageService;
use Pecee\SimpleRouter\SimpleRouter;

class ImageController
{
    public function __construct(
        private readonly ImageService $imageService,
        private readonly TemplateBuilder $templateBuilder,
        private readonly SimpleRouter $router,
    ) {
    }

    public function index(): string
    {
        return $this->templateBuilder->getTemplate('index.html');
    }

    public function getBySlug(string $slug): ?string
    {
        try {
            $image = $this->imageService->getBySlug($slug);

            $this->router->response()->header("Content-Type: {$image->getMime()}");
            $this->router->response()->header('Content-Length: ' . $image->getSizeInBytes());

            return $image->getBlob();
        } catch (ImageNotFoundException) {
            $this->router->response()->httpCode(404);

            return 'Image not found';
        }
    }

    public function processAndCacheImage(
        string $imageName,
        string $modifiersParamsString
    ): ?string {
        try {
            $beautifiedName = $this->imageService->processAndGetBeautifiedName($imageName, $modifiersParamsString);
        } catch (ImageException) {
            $this->router->response()->httpCode(400);

            return 'Invalid modifiers request';
        } catch (ImageNotFoundException) {
            $this->router->response()->httpCode(404);

            return 'Image not found';
        }

        $this->router->response()->httpCode(301)->redirect("/$beautifiedName");
        return null;
    }

    public function test(): string
    {
        return $this->templateBuilder->getTemplate('test.html', [
            'ORIGINAL_IMAGE' => '/sample.png',
            'PROCESSED_IMAGE' => '/sample.png/crop/280,115,85,27/resize/850,270',
        ]);
    }
}
