<?php

namespace App\Jobs;

use Exception;
use App\Report;
use Carbon\Carbon;

class Ping extends Job
{

    protected $domain;
    protected $port;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($domain, $port = 80)
    {
        $this->domain = $domain;
        $this->port = $port;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ( $this->online() ) {
            $this->handleOnline();
        } else {
            $this->handleOffline();
        }
    }

    protected function handleOnline()
    {
        $latestReport = $this->getLatestReport();

        if ( ! $latestReport || $latestReport->closed ) {
            return;
        }

        $latestReport->update([
            'closed' => true,
            'up' => Carbon::now()
        ]);
    }

    protected function handleOffline()
    {
        $latestReport = $this->getLatestReport();

        if ( $latestReport && ! $latestReport->closed ) {
            return;
        }

        Report::create([
            'down' => Carbon::now(),
            'closed' => false
        ]);
    }

    protected function getLatestReport()
    {
        return Report::latest()->first();
    }

    protected function online()
    {
        if ( strpos($this->domain, 'http') === false ) {
            $this->domain = 'http://'.$this->domain;
        }

        if( ! filter_var($this->domain, FILTER_VALIDATE_URL) ) {
            throw new Exception('Cannot check ' . $this->domain);
        }

        $c = curl_init($this->domain);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($c, CURLOPT_PORT, $this->port);
        curl_setopt($c, CURLOPT_HEADER, true);
        curl_setopt($c, CURLOPT_NOBODY, true);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($c);
        curl_close($c);

        return $response ? true : false;
   }

}
