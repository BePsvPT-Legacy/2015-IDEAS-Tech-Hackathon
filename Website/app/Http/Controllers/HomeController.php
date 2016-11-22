<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Log;

class HomeController extends Controller
{
    /**
     * @var Guard
     */
    protected $guard;

    /**
     * @param Guard $guard
     */
    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

    public function index()
    {
        return redirect()->route('facebook.pages');
    }

    public function pages(Request $request)
    {
        if ($this->guard->guest())
        {
            return redirect()->route('facebook.signIn');
        }

//        if (env('FACEBOOK_APP_URL') === $request->header('referer'))
//        {
//            $client = new Client();
//
//            $url = 'https://graph.facebook.com/'.($this->guard->user()->id).'/notifications?access_token='.(env('FACEBOOK_APP_ID') . '|' . env('FACEBOOK_APP_SECRET')).'&template='.'Coding 人生' . str_random(8);
//
//            $client->post($url);
//        }

        return view('index');
    }

    public function canvas()
    {
        return redirect()->route('facebook.pages');
    }

    public function githubPull(Request $request)
    {
        Log::info('github receive pull');

        if ('refs/heads/master' === $request->json('ref'))
        {
            shell_exec('git --work-tree ' . (env('GITHUB_COMMAND_BASE_PATH')) . ' --git-dir ' . (env('GITHUB_COMMAND_BASE_PATH')) . '.git pull');

            Log::info('github pull success');

            return response('', 204);
        }

        return response('', 200);
    }
}