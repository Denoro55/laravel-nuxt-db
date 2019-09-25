<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleComments extends Model
{
    protected $fillable = ['user_id', 'article_id', 'name', 'text'];
}
