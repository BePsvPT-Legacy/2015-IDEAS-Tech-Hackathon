<?php

namespace App\Hackathon;

use App\Hackathon\Core\Entity;

class LotteryMethod extends Entity
{
    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = ['grate', 'share', 'reply'];
}