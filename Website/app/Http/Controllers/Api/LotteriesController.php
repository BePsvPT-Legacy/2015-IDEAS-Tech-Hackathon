<?php

namespace App\Http\Controllers\Api;

use App\Hackathon\Lottery;
use App\Hackathon\UserSubscribe;
use Carbon\Carbon;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LotteriesController extends Controller
{
    public function getAll()
    {
        $now = Carbon::now();

        $lotteries = Lottery::orderBy('expired_at', 'desc')->paginate();

        $lotteries->transform(function ($lottery) use ($now)
        {
            if (null !== $lottery->expired_at)
            {
                $lottery->expired_at = Carbon::parse($lottery->expired_at)->diffForHumans($now);
            }

            $lottery->facebook_page->name = str_limit($lottery->facebook_page->name, 9);

            return $lottery;
        });

        return response()->json($lotteries);
    }

    public function getSubscribesList(Guard $guard)
    {
        if ($guard->guest())
        {
            return response()->json([]);
        }

        $now = Carbon::now();

        $subs = $guard->user()->subscribes->pluck('lottery_id')->toArray();

        $lotteries = Lottery::whereIn('id', $subs)->orderBy('expired_at', 'desc')->get();

        $lotteries->transform(function ($lottery) use ($now)
        {
            if (null !== $lottery->expired_at)
            {
                $lottery->expired_at = Carbon::parse($lottery->expired_at)->diffForHumans($now);
            }

            $lottery->facebook_page->name = str_limit($lottery->facebook_page->name, 9);

            return $lottery;
        });

        return response()->json($lotteries);
    }

    public function getSubscribes(Guard $guard)
    {
        if ($guard->guest())
        {
            return response()->json([]);
        }

        $subscribes = $guard->user()->subscribes;

        return response()->json($subscribes);
    }

    public function subscribe(Request $request, Guard $guard)
    {
        if ($guard->guest() || ! $request->has('id'))
        {
            return response()->json(['success' => false]);
        }
        else if ( ! Lottery::where('id', '=', $request->input('id'))->exists())
        {
            return response()->json(['success' => false]);
        }

        if (UserSubscribe::where('user_id', '=', $guard->user()->id)->where('lottery_id', '=', $request->input('id'))->exists())
        {
            UserSubscribe::where('user_id', '=', $guard->user()->id)->where('lottery_id', '=', $request->input('id'))->delete();
        }
        else
        {
            UserSubscribe::create(['user_id' => $guard->user()->id, 'lottery_id' => $request->input('id')]);
        }

        return response()->json(['success' => true]);
    }

    public function getSpecific($id)
    {
        if (null === ($lottery = Lottery::find($id)))
        {
            return 'not found';
        }

        return $lottery->content;
    }
}