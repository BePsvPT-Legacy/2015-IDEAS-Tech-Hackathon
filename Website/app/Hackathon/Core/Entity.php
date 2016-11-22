<?php

namespace App\Hackathon\Core;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 16;
}