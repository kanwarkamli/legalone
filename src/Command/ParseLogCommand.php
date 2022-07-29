<?php

namespace App\Command;

use App\Services\Log;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'parse-log',
    description: 'Parse log file and insert into the database',
)]
class ParseLogCommand extends Command
{
    private Log $log;
    private string $logDir;

    public function __construct($logDir, Log $log)
    {
        $this->log = $log;
        $this->logDir = $logDir;
        
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'Log file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $file = $input->getArgument('file');

        if ($file) {
            $io->note(sprintf('You passed an argument: %s', $file));
        }

        $inputFile = $this->logDir . $file;

        if (!file_exists($inputFile)) {
            $io->error("File not found {$inputFile}");
        }

        $this->log->parse($inputFile);

        return Command::SUCCESS;
    }
}
