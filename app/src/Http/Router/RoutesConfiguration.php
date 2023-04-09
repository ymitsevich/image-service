<?php

namespace App\Http\Router;

use App\Http\Controller\ImageController;
use Pecee\Http\Middleware\Exceptions\TokenMismatchException;
use Pecee\SimpleRouter\Exceptions\HttpException;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\SimpleRouter\SimpleRouter;

class RoutesConfiguration
{
    public function __construct(
        private readonly ImageController $imageController,
        private readonly SimpleRouter $router,
    ) {
    }

    public function run(): void
    {
        $this->router->get('/', fn() => $this->imageController->index());

        $this->router->get('/test', fn() => $this->imageController->test());

        $this->router->get(
            '/{imageName}/crop/{cropParameters?}/resize/{resizeParameters?}',
            function (string $imageName, string $cropParameters, string $resizeParameters) {
                $this->imageController->processAndCacheImage(
                    imageName: $imageName,
                    crop: $cropParameters,
                    resize: $resizeParameters,
                );
            }
        )->where([
            'imageName' => '.*',
            'resizeParameters' => '\d+,\d+',
            'cropParameters' => '\d+,\d+,\d+,\d+',
        ]);

        $this->router->get(
            '/{slug}',
            fn(string $slug) => $this->imageController->getBySlug(slug: $slug)
        )->where([
            'slug' => '.*',
        ]);

        try {
            $this->router->start();
        } catch (TokenMismatchException) {
            $this->router->response()->httpCode(400);
            echo 'Token mismatch';
        } catch (NotFoundHttpException) {
            $this->router->response()->httpCode(404);
            echo '404 Not Found';
        } catch (HttpException) {
            $this->router->response()->httpCode(400);
            echo 'Bad request';
        }
    }
}
