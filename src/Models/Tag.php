<?php

namespace Usamamuneerchaudhary\LaravelTagify\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Usamamuneerchaudhary\LaravelTagify\Scopes\TagUsedScopes;

class Tag extends Model
{
    use HasFactory, TagUsedScopes;
}
