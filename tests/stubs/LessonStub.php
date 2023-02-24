<?php

use Illuminate\Database\Eloquent\Model;
use Usamamuneerchaudhary\LaravelTagify\Taggable;

class LessonStub extends Model
{
    use Taggable;

    protected $connection = 'testbench';

    public $table = 'lessons';

}
