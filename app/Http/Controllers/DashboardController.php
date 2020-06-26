<?php

namespace App\Http\Controllers;

use App\Model\Repo;
use App\Jobs\ForkRepo;
use GuzzleHttp\Client;
use App\Model\SocialAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    protected $page = 1;
    protected $per_page = 30;

    public function index(Request $request)
    {
        $user = $request->user();

        return view('dashboard', compact('user'));
    }

    public function getUser(Request $request)
    {
        $page = $this->page;
        $per_page = $this->per_page;
        $client = new Client();
        try {
            $userGithub = $request->name;
            $public_repos = $client->get("https://api.github.com/users/$userGithub");
            $totalRepo = json_decode($public_repos->getBody()->getContents())->public_repos;
            $response = $client->get("https://api.github.com/users/$userGithub/repos?page=$page&per_page=$per_page");
            $data = json_decode($response->getBody()->getContents());
            return view('repo', compact('data', 'userGithub', 'totalRepo'));
        } catch (\Exception $e) {
            return response()->json([
                'status'    => false,
                'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'   => $e->getMessage(),
            ]);
        }
    }

    public function loadUser(Request $request)
    {
        $client = new Client();
        try {
            if($request->ajax()) {
                $per_page = $this->per_page;
                $userGithub = $request->user_github;
                $page = $request->page;
                $public_repos = $client->get("https://api.github.com/users/$userGithub");
                $totalRepo = json_decode($public_repos->getBody()->getContents())->public_repos;
                $countTotalPage=$request->count_total_page;
                if ($countTotalPage < $totalRepo) {
                    $response = $client->get("https://api.github.com/users/$userGithub/repos?page=$page&per_page=$per_page");
                    
                    return json_encode($response->getBody()->getContents());
                }
            }            
        } catch (\Exception $e) {
            return response()->json([
                'status'    => false,
                'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'   => $e->getMessage(),
            ]);
        }
    }

    public function repo(Request $request)
    {
        try {
            if($request->ajax()) {
                Repo::create([
                    'repo' => $request->url_repo,
                    'links_fork' => $request->url_fork,
                ]);
                return response()->json([
                    'status'    => true,
                    'code'      => Response::HTTP_OK,
                    'message'   => 'susscess',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'    => false,
                'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'   => $e->getMessage(),
            ]);
        }
    }

    public function listRepo()
    {
        $listRepo = Repo::all();
        return view('listrepo', compact('listRepo'));
    }

    public function forkRepo(Request $request)
    {
        $user = Auth::user();
        try {
            if($request->ajax()) {
                $repo = Repo::where('repo', $request->url_fork)->first();
                ForkRepo::dispatch($repo, $user)->onConnection('database');
                return response()->json([
                    'status'    => true,
                    'code'      => Response::HTTP_OK,
                    'message'   => 'susscess',
                ]);
            }            
        } catch (\Exception $e) {
            return response()->json([
                'status'    => false,
                'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'   => $e->getMessage(),
            ]);
        }
    }
}
