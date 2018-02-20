<?php

namespace App\Console\Commands;

use Exception;
use App\Report;
use App\Jobs\Ping;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ReportsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'View Reports';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $reports = new Collection(Report::latest()->take(30)->get()->toArray());

        if ( $reports->isEmpty() ) {
        	$this->info('No recent reports');
        	return;
        }

        $reports = $reports->map(function($report) {
        	$report['closed'] = $report['closed'] ? 'Yes' : 'No';
        	return $report;
        });

        $headers = array_keys($reports->first());
        $this->table($headers, $reports->all());
    }

}
