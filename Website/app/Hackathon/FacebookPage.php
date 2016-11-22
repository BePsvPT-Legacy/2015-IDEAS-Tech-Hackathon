<?php

namespace App\Hackathon;

use App\Hackathon\Core\Entity;

class FacebookPage extends Entity
{
    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = ['name', 'page_id', 'avatar_url', 'cover_url'];
}