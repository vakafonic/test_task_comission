<?php

namespace App\Command;

use App\Service\NumberPrinter\NumberPrintManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use function range;

class NumberOutputCommand extends Command
{
    protected static $defaultName = 'app:numbers';

    private NumberPrintManager $taskOneManager;
    private NumberPrintManager $taskTwoManager;
    private NumberPrintManager $taskThreeManager;

    public function __construct(
        NumberPrintManager $taskOneManager,
        NumberPrintManager $taskTwoManager,
        NumberPrintManager $taskThreeManager
    )
    {
        parent::__construct(static::$defaultName);
        $this->taskOneManager = $taskOneManager;
        $this->taskTwoManager = $taskTwoManager;
        $this->taskThreeManager = $taskThreeManager;
    }

    protected function configure()
    {
        $this->setDescription('Print task output');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $array = range(1, 20);

        $io->writeln('Task v1:');
        $io->writeln($this->taskOneManager->process($array));

        $array = range(1, 15);
        $io->writeln('Task v2:');
        $io->writeln($this->taskTwoManager->process($array));

        $array = range(1, 10);
        $io->writeln('Task v3:');
        $io->writeln($this->taskThreeManager->process($array));

        return Command::SUCCESS;
    }
}
