<?php

namespace App\Console\Commands;

use Exception;
use App\Jobs\Ping;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PingCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ping';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        dispatch(new Ping('google.com'));
    }

}
