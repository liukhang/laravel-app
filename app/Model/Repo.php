<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Repo extends Model
{
    protected $fillable = ['repo', 'links_fork', 'fork_status'];
}