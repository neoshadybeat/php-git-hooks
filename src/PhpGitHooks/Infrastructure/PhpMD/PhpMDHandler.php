<?php

namespace PhpGitHooks\Infrastructure\PhpMD;

use PhpGitHooks\Infrastructure\Common\ToolHandler;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class PhpMDHandler
 * @package PhpGitHooks\Infrastructure\PhpMD
 */
class PhpMDHandler extends ToolHandler
{
    /** @var  array */
    private $files;
    /** @var  string */
    private $needle;

    /**
     * @throws PHPMDViolationsException
     */
    public function run()
    {
        $this->outputHandler->setTitle('Checking code mess with PHPMD');
        $this->output->write($this->outputHandler->getTitle());

        foreach ($this->files as $file) {
            if (!preg_match($this->needle, $file)) {
                continue;
            }

            $processBuilder = new ProcessBuilder(
                array(
                    'php',
                    'bin/phpmd',
                    $file,
                    'text',
                    'PmdRules.xml',
                    '--minimumpriority',
                    1,
                )
            );
            $process = $processBuilder->getProcess();
            $process->run();

            if (false === $process->isSuccessful()) {
                throw new PHPMDViolationsException($process->getOutput());
            }
        }
        $this->output->writeln($this->outputHandler->getSuccessfulStepMessage());
    }

    /**
     * @param string $needle
     */
    public function setNeedle($needle)
    {
        $this->needle = $needle;
    }

    /**
     * @param array $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }
}
