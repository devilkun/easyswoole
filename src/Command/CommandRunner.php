<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2019-01-24
 * Time: 23:11
 */

namespace EasySwoole\EasySwoole\Command;


use EasySwoole\Command\AbstractInterface\CallerInterface;
use EasySwoole\Command\AbstractInterface\ResultInterface;
use EasySwoole\Command\Container;
use EasySwoole\Command\Runner;
use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Command\DefaultCommand\Crontab;
use EasySwoole\EasySwoole\Command\DefaultCommand\Help;
use EasySwoole\EasySwoole\Command\DefaultCommand\Install;
use EasySwoole\EasySwoole\Command\DefaultCommand\PhpUnit;
use EasySwoole\EasySwoole\Command\DefaultCommand\Process;
use EasySwoole\EasySwoole\Command\DefaultCommand\Reload;
use EasySwoole\EasySwoole\Command\DefaultCommand\Restart;
use EasySwoole\EasySwoole\Command\DefaultCommand\Start;
use EasySwoole\EasySwoole\Command\DefaultCommand\Status;
use EasySwoole\EasySwoole\Command\DefaultCommand\Stop;
use EasySwoole\EasySwoole\Command\DefaultCommand\Task;
use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\Core;
use EasySwoole\EasySwoole\Command\DefaultCommand\Config as ConfigCommand;


class CommandRunner extends Runner
{
    use Singleton;

    function __construct(Container $container = null)
    {
        parent::__construct($container);
        $this->commandContainer()->set(new Install());
        $this->commandContainer()->set(new Start());
        $this->commandContainer()->set(new Stop());
        $this->commandContainer()->set(new Reload());
        $this->commandContainer()->set(new Restart());
        $this->commandContainer()->set(new PhpUnit());
        $this->commandContainer()->set(new ConfigCommand());
        $this->commandContainer()->set(new Help());
        $this->commandContainer()->set(new Task());
        $this->commandContainer()->set(new Crontab());
        $this->commandContainer()->set(new Process());
        $this->commandContainer()->set(new Status());
    }

    private $beforeCommand;

    function setBeforeCommand(callable $before)
    {
        $this->beforeCommand = $before;
    }

    function run(CallerInterface $caller): ?ResultInterface
    {
        if(is_callable($this->beforeCommand)){
            call_user_func($this->beforeCommand,$caller);
        }
        Utility::opCacheClear();
        return parent::run($caller);
    }
}
