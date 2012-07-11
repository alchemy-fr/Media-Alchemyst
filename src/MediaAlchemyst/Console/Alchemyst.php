<?php

namespace MediaAlchemyst\Console;

use MediaAlchemyst\Specification;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

class Alchemyst extends Command
{

    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->setDescription('Transmute a file to another type');

        $this->addArgument('spec', InputArgument::REQUIRED, 'The specification to use ; use the specification command to list them all');
        $this->addArgument('file', InputArgument::REQUIRED, 'The file to transmute');
        $this->addArgument('target', InputArgument::REQUIRED, 'The file to write');
        $this->addOption('force', 'f', InputOption::VALUE_NONE, 'Overwrite target if exists');
        $this->addOption('acodec', null, InputOption::VALUE_OPTIONAL, 'Audio codec (for audio/video specs)');
        $this->addOption('vcodec', null, InputOption::VALUE_OPTIONAL, 'Video codec (for video specs)');
        $this->addOption('width', null, InputOption::VALUE_OPTIONAL, 'Width (for video/image specs)');
        $this->addOption('height', null, InputOption::VALUE_OPTIONAL, 'Height (for video/image specs)');
        $this->addOption('threads', null, InputOption::VALUE_OPTIONAL, 'Number of threads');
        $this->addOption('framerate', null, InputOption::VALUE_OPTIONAL, 'The frame rate');

        return $this;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $spec = $this->getSpecification($input->getArgument('spec'));

        $file = $input->getArgument('file');

        if ( ! file_exists($file)) {
            throw new \InvalidArgumentException(sprintf('file `%s` does not exists', $file));
        }

        $target = $input->getArgument('target');
        $force = $input->getOption('force');

        if (realpath($file) === realpath($target)) {
            throw new \InvalidArgumentException('Source and target should be different');
        }

        if (file_exists($target) && ! $force) {
            throw new \InvalidArgumentException(sprintf('file `%s` already exists ; use --force to overwrite', $target));
        }

        if (method_exists($spec, 'setAudioCodec')) {
            if ($input->getOption('acodec')) {
                $spec->setAudioCodec($input->getOption('acodec'));
            }
        }
        if (method_exists($spec, 'setVideoCodec')) {
            if ($input->getOption('vcodec')) {
                $spec->setVideoCodec($input->getOption('vcodec'));
            }
        }
        if (method_exists($spec, 'setFrameRate')) {
            if ($input->getOption('framerate')) {
                $spec->setFrameRate(($input->getOption('framerate'));
            }
        }
        if (method_exists($spec, 'setDimensions')) {
            if ($input->getOption('width') && $input->getOption('height')) {
                $spec->setDimensions($input->getOption('width'), $input->getOption('height'));
            } elseif (( ! $input->getOption('width') && $input->getOption('height'))
                || ($input->getOption('width') && ! $input->getOption('height'))) {
                throw new \InvalidArgumentException('You should provide both dimensions or no dimensions');
            }
        }

        $logger = new \Monolog\Logger('Logger');

        $parameters = new \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag(array(
                'ffmpeg.threads' => $input->getOption('threads') ? $input->getOption('threads') : 1
            ));

        $drivers = new \MediaAlchemyst\DriversContainer($parameters, $logger);

        $Alchemyst = new \MediaAlchemyst\Alchemyst($drivers);
        $Alchemyst->open($file);
        $Alchemyst->turnInto($target, $spec);
        $Alchemyst->close();
    }

    protected function getSpecification($name)
    {
        switch ($name) {
            case Specification\Specification::TYPE_ANIMATION:
                return new Specification\Animation();
                break;
            case Specification\Specification::TYPE_VIDEO:
                return new Specification\Video();
                break;
            case Specification\Specification::TYPE_SWF:
                return new Specification\Flash();
                break;
            case Specification\Specification::TYPE_IMAGE:
                return new Specification\Image();
                break;
            case Specification\Specification::TYPE_AUDIO:
                return new Specification\Audio();
                break;
        }

        throw new \InvalidArgumentException(sprintf('Spec `s` is unknown', $name));
    }
}
