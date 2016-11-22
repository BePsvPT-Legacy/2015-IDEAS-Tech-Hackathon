<?php

namespace App\Console\Commands;

use App\Hackathon\Lottery;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ArticleAnalysis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article-analysis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analysis articles.';

    /**
     * @var array
     */
    protected $grateRegex = [
        '/按讚/',
        '/點讚/',
        '/讚好/',
    ];

    /**
     * @var array
     */
    protected $shareRegex = [
        '/分享/',
        '/PO文/i',
    ];

    /**
     * @var array
     */
    protected $replyRegex = [
        '/回覆/',
        '/留言/',
        '/留下/',
        '/標記/',
        '/tag/i',
    ];

    /**
     * @var array
     */
    protected $expiredRegex = [
        '/(~|～|到|至|限時).{0,5}\d\D{1,3}\d?\d.*/',
        '/.*\d\D{1,3}\d?\d.*(止|前).*/',
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        foreach (Lottery::all() as $lottery)
        {
            $data = [
                'grate' => false,
                'share' => false,
                'reply' => false,
                'expired_at' => null,
            ];

            $matches = [];

            //$lottery = Lottery::find($id = mt_rand(1, 130));
            //$lottery = Lottery::find($id = 844);

            // 按讚
            foreach ($this->grateRegex as $regex)
            {
                if (preg_match($regex, $lottery->content, $matches[0]))
                {
                    $data['grate'] = true;

                    break;
                }
            }

            // 分享
            foreach ($this->shareRegex as $regex)
            {
                if (preg_match($regex, $lottery->content, $matches[1]))
                {
                    $data['share'] = true;

                    break;
                }
            }

            // 回覆
            foreach ($this->replyRegex as $regex)
            {
                if (preg_match($regex, $lottery->content, $matches[2]))
                {
                    $data['reply'] = true;

                    break;
                }
            }

            // Expired At
            foreach ($this->expiredRegex as $regex)
            {
                if (preg_match_all($regex, $lottery->content, $matches[3]))
                {
                    $data['expired_at'] = true;

                    //dd([$id, $data['expired_at'], $lottery->content, $matches]);

                    break;
                }
            }

            if (null !== $data['expired_at'])
            {
                $check = true;

                foreach ($matches[3] as $matchs)
                {
                    foreach ($matchs as $match)
                    {
                        if (strlen($match) > 2)
                        {
                            $check = false;

                            //dd($match);

                            if (preg_match('/\d\D+?\d?\d(\D{1,2}\d?\d)?/', $match, $result))
                            {
                                //dd($result);

                                $result = preg_split('/\D+/', $result[0]);
                                try {
                                    switch (count($result))
                                    {
                                        case 3:
                                            if (intval($result[0]) != Carbon::now()->year)
                                            {
                                                $data['expired_at'] = Carbon::parse(Carbon::now()->year . '-' . $result[0] . '-' . $result[1]);
                                            }
                                            else
                                            {
                                                $data['expired_at'] = Carbon::parse($result[0] . '-' . $result[1] . '-' . $result[2]);
                                            }
                                            break;
                                        case 2:
                                            $data['expired_at'] = Carbon::parse(Carbon::now()->year . '-' . $result[0] . '-' . $result[1]);
                                            break;
                                        default:
                                            $data['expired_at'] = null;
                                            //dd($result);
                                            break;
                                    }
                                } catch(\Exception $e) {
                                    $data['expired_at'] = null;
                                }
                            }

                            break;
                        }
                    }
                }

                if ($check)
                {
                    $data['expired_at'] = null;
                }
            }

            //dd([$data['expired_at'], $lottery->content]);

            $lottery->lottery_method_id = 1 + intval($data['reply']) * 1 + intval($data['share']) * 2 + intval($data['grate']) * 4;
            $lottery->expired_at = $data['expired_at'];
            $lottery->save();

            //dd([$id, $lottery->content]);
        }
    }
}