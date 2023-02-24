<?php

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Usamamuneerchaudhary\LaravelTagify\Scopes\TagUsedScopes;

class TagStub extends Model
{
    use HasFactory, TagUsedScopes;

    protected $connection = 'testbench';

    public $table = 'tags';

}
