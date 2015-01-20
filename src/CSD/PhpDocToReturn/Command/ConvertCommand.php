<?php
namespace CSD\PhpDocToReturn\Command;

use CSD\PhpDocToReturn\Application as App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class ConvertCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('convert')
            ->addOption('src', 's', InputOption::VALUE_REQUIRED, 'The source directory, defaults to current working directory')
            ->addOption('dest', 'd', InputOption::VALUE_REQUIRED, 'The destination directory, defaults to overwrite source directory')
            ->addOption('remove-comments', 'r', InputOption::VALUE_REQUIRED, 'Remove redundant @return doc comments?', true)
            ->addOption('dry-run')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $src = $input->getOption('src');

        if ($src) {
            $src = realpath($src);

            if (!$src) {
                throw new \Exception('Cannot find source folder');
            }
        } else {
            $src = getcwd();
        }

        $dest = $input->getOption('dest');

        if ($dest) {
            if (!file_exists($dest)) {
                if (!mkdir($dest, 0777, true)) {
                    throw new \Exception('Could not create output folder');
                }
            }

            $dest = realpath($dest);
        } else {
            $dest = $src;
        }

        $app = new App($src, $dest);
        $app->run();
    }
}
