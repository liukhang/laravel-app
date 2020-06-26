<?php

namespace App\Jobs;

use App\User;
use App\Model\Repo;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ForkRepo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $repo;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Repo $repo, User $user)
    {
        $this->repo = $repo;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();
        $token = $this->user->access_token;
        $response = $client->post($this->repo->links_fork, [
            'headers' => 
            [
                'Authorization' => 'Bearer ' . $token,   
                'Accept'        => 'application/json',
            ]
        ]);
        $data = json_decode($response->getBody()->getContents());
        $this->repo->links_forked = $data->html_url;
        $this->repo->fork_status  = 2;
        $this->repo->save();
    }
}
