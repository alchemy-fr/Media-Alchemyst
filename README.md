#MediAlchemyst

A PHP 5.3 lib to transmute media files.

[![Build Status](https://travis-ci.org/alchemy-fr/Media-Alchemyst.png?branch=master)](http://travis-ci.org/alchemy-fr/Media-Alchemyst)

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

## Silex service provider ?

Need a [Silex](silex.sensiolabs.org) service provider ? Of course it's provided !

Please note that Media-Alchemyst service provider requires MediaVorus service
provider.

```php
use Silex\Application;
use MediaAlchemyst\Alchemyst;
use MediaAlchemyst\MediaAlchemystServiceProvider;
use MediaVorus\MediaVorusServiceProvider;

$app = new Application();
$app->register(new MediaAlchemystSerciceProvider());

// MediaVorus service provider is required to use MediaAlchemyst service provider
// Find it here https://github.com/romainneutron/MediaVorus
$app->register(new MediaVorusServiceProvider());

// Have fun OH YEAH
assert($app['media-alchemyst'] instanceof Alchemyst);
```

You can customize the service provider with the following options :

```
$app->register(new MediaVorusServiceProvider(), array(
    'media-alchemyst.logger'                => $logger,  // A Monolog Logger
    'media-alchemyst.ffmpeg.timeout'        => 200,
    'media-alchemyst.ffmpeg.ffprobe.binary' => '/path/to/custom/ffprobe',
    'media-alchemyst.ffmpeg.ffmpeg.binary'  => '/path/to/custom/ffmpeg',
    'media-alchemyst.ffmpeg.threads'        => 8,
    'media-alchemyst.mp4box.binary'         => '/path/to/custom/MP4Box',
    'media-alchemyst.unoconv.binary'        => '/path/to/custom/unoconv',
    'media-alchemyst.swftools.timeout'      => 100,
    'media-alchemyst.swf-extract.binary'    => '/path/to/custom/swfextract',
    'media-alchemyst.swf-render.binary'     => '/path/to/custom/swfrender',
    'media-alchemyst.pdf2swf.binary'        => '/path/to/custom/pdf2swf',
    'media-alchemyst.imagine.driver'        => 'imagick',
    'media-alchemyst.ghostscript.binary'    => '/path/to/custom/gs',
));
```


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
