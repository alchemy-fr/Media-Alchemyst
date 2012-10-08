#MediAlchemyst

A PHP 5.3 lib to transmute media files.

[![Build Status](https://secure.travis-ci.org/alchemy-fr/Media-Alchemyst.png?branch=master)](http://travis-ci.org/alchemy-fr/Media-Alchemyst)

* Want to extract audio from video ?
* Want to extract image from office document ?
* Want to resize images ?
* Want to generate Gif Animation from Video ?

Media-Alchemyst is a tool to transmute your medias from media-type to
media-type.

## Exemple of use

```php

use MediaAlchemyst\Alchemyst;
use MediaAlchemyst\Specification\Animation;
use MediaAlchemyst\Specification\Image;
use MediaAlchemyst\Specification\Video;

$alchemyst = Alchemyst::create();

$video = new Video();
$video->setDimensions(320, 240)
    ->setFramerate(15)
    ->setGOPSize(200);

// AMAZING
$alchemyst->open('movie.mp4')
    ->turnInto('animation.gif', new Animation())
    ->turnInto('screenshot.jpg', new Image())
    ->turnInto('preview.ogv', $video)
    ->close();

```

## What is currently supported ?

* Working install of FFMpeg (for AUdio / Video processing)
* Gpac (for X264 Video processing)
* Perl (for metadata analysis)
* GraphicsMagick and its Gmagick PHP Extension (recommended) or ImageMagick (Image processing)
* Unoconv (for Office documents processing)
* SwfTools (for Flash files processing)

## Customize drivers

Drivers preferences can be specified through the `DriversContainer` :

```php

$container = new DriversContainer();

$container['ffmpeg.ffmpeg.binary'] = '/path/to/ffmpeg/custom/build';
$container['image.driver'] = 'Gmagick'; // use Gmagick ImagineDriver
$container['mp4box.binary'] = '/path/to/mp4box/custom/build';

```

## License

This is MIT licensed, enjoy :)
