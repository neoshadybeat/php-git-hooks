<?php

namespace PhpGitHooks\Application\PhpLint;

use PhpGitHooks\Infrastructure\Common\PreCommitExecuter;
use PhpGitHooks\Application\Config\PreCommitConfig;
use PhpGitHooks\Infrastructure\PhpLint\PhpLintHandler;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CheckPhpSyntaxLintPreCommitExecuter
 * @package PhpGitHooks\Application\PhpLint
 */
class CheckPhpSyntaxLintPreCommitExecuter extends PreCommitExecuter
{
    /** @var  PhpLintHandler */
    private $phpLintHandler;

    /**
     * @param PreCommitConfig $preCommitConfig
     * @param PhpLintHandler  $phpLintHandler
     */
    public function __construct(PreCommitConfig $preCommitConfig, PhpLintHandler $phpLintHandler)
    {
        $this->preCommitConfig = $preCommitConfig;
        $this->phpLintHandler = $phpLintHandler;
    }

    /**
     * @param  OutputInterface $output
     * @param  array           $files
     * @throws string          PhpLintException
     */
    public function run(OutputInterface $output, array $files)
    {
        if ($this->isEnabled()) {
            $this->phpLintHandler->setOutput($output);
            $this->phpLintHandler->setFiles($files);
            $this->phpLintHandler->run();
        }
    }

    /**
     * @return string
     */
    protected function commandName()
    {
        return 'phplint';
    }
}
