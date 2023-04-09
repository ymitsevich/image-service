# Image Service

## Description

A small image service which can deliver images using a GET request and which are stored on the server. It is possible to
use different modifiers to change what will be returned. Two modifiers implemented:
* crop-modifier (cuts the image and will take height and width as parameters)
* resize-modifier (resizes the images based on given height and width as parameters)
Further modifiers could be possible to integrate easily in code.

The services outputs images in the same file format (e.g. jpg) as they have been read.

## Implemented and utilized

1. Simple service container Pimple
2. Simple HTTP router
3. Pipeline with modifiers (extendable)
4. html page http://localhost/test with examples, main page with links http://localhost/
5. Storing a processed image as a file and then redirecting to another endpoint to expose it
6. Unit and Integration tests
7. CI Github Actions to run tests and build+push the dockerfile

## Instructions

### Requirements

1. docker
2. make tool

### Usage

Dev:

* make build-dev
* make init
* make dev
* http://localhost/test
* put a source image file in /var/images until the upload mechanism is implemented. There is a sample.png file only at
  the moment.

Prod:

* make build-prod
* use the dockerfile within either k8s or aws etc.

### Adding new image modifiers

1. Create two classes

* App\Service\Image\Modifier\Dto\NewModifierParameters (should implement ModifierParameters interface)
* App\Service\Image\Modifier\NewModifier (should implement Modifier interface)

2. Add the new modifier to App\Service\Image\Pipeline\ModifierPipelineFactory::$modifiersFqnMap array. It includes
   enabled modifiers.
3. Add new parameters for the url in \App\Http\Router\RoutesConfiguration to be able to handle them.

## Todo

1. Image upload system.
2. Func/App test to trigger http endpoints (requires a testing framework and additional effort)
3. Add additional validation and incorrect arguments handling
4. The full chain integration test needs comprehensive service container 
