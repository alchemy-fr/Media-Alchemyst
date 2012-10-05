#MediAlchemyst

[![Build Status](https://secure.travis-ci.org/alchemy-fr/Media-Alchemyst.png?branch=master)](http://travis-ci.org/alchemy-fr/Media-Alchemyst)


Media-Alchemyst is a tool to transmute your media from media-types to
media-types.

Want to extract audio from video ?
Want to extract image from office document ?
Want to resize images ?
Want to generate Gif Animation from Video ?

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

$alchemyst->open('movie.mp4')
    ->turnInto('animation.gif', new Animation())
    ->turnInto('screenshot.jpg', new Image())
    ->turnInto('preview.ogv', $video)
    ->close();

```

## Customize drivers

```php
// to do
```

## License

This is MIT licensed, enjoy :)