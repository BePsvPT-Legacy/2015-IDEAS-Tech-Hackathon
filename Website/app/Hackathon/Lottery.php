<?php

namespace App\Hackathon;

use App\Hackathon\Core\Entity;

class Lottery extends Entity
{
    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = ['id', 'facebook_page', 'lottery_method', 'awards', 'article_url', 'cover_url', 'expired_at', 'announced_at'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['published_at', 'announced_at'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['facebook_page', 'lottery_method'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function facebook_page()
    {
        return $this->belongsTo('App\Hackathon\FacebookPage', 'facebook_page_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lottery_method()
    {
        return $this->belongsTo('App\Hackathon\LotteryMethod', 'lottery_method_id');
    }
}