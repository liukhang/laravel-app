<?php

namespace App\Jobs;

use App\Model\Repo;
use GuzzleHttp\Client;
use App\Model\SocialAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ForkRepo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $repo;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Repo $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();
        $token = SocialAccount::first();
        $response = $client->post($this->repo->links_fork, [
            'headers' => 
            [
                'Authorization' => 'Bearer ' . $token->access_token,        
                'Accept'        => 'application/json',
            ]
        ]);
        $data  = json_decode($response->getBody()->getContents());
        $this->repo->links_forked = $data->html_url;
        $this->repo->fork_status  = 2;
        $this->repo->save();
    }
}
