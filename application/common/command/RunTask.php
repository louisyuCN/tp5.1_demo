<?php
namespace app\common\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class RunTask extends Command
{
    protected function  configure()
    {
        $this->setName('runTask')->setDescription('跑任务');
    }

    protected function execute(Input $input, Output $output)
    {
        $output->writeln('Hello World');
    }

}