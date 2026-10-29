## Laravel Tagify
[![Latest Version on Packagist](https://img.shields.io/packagist/v/usamamuneerchaudhary/laravel-tagify?style=flat-square)](https://packagist.org/packages/usamamuneerchaudhary/laravel-tagify)
[![Total Downloads](https://img.shields.io/packagist/dt/usamamuneerchaudhary/laravel-tagify?style=flat-square)](https://packagist.org/packages/usamamuneerchaudhary/laravel-tagify)
[![Licence](https://img.shields.io/packagist/l/usamamuneerchaudhary/filament-notifier?style=flat-square)](https://github.com/usamamuneerchaudhary/filament-notifier/blob/HEAD/LICENSE.md)
### Tagify is a simple Tagging Package for Laravel
Using this package, you can tag, untag, or retag any Eloquent model, and listen for attach and detach events.

- Laravel 11, 12, and 13
- PHP 8.3+

```
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Usamamuneerchaudhary\LaravelTagify\Taggable;

class Lesson extends Model
{
    use Taggable;
}
```

## Installation
You can install the package via composer:

`composer require usamamuneerchaudhary/laravel-tagify`

The service provider is discovered automatically.

## Run Migrations

Once the package is installed, you can run migrations:

`php artisan migrate`

## Usage & Some basic functions

Add Tags to a Model
```
$tags = ['Laravel', 'PHP'];
$model->tag($tags);
```
Untag a tag
```
$model->untag(['Laravel']);
```

Untag all tags
```
$model->untag();
```

Re-tag a tag to a model
```
$model->retag(['Eloquent', 'mysql']);
```

Get all tags
```
$model->tags;
```

You can also use scopes to get models associated with tags:
```
App\Models\Lesson::withAllTags(['Laravel', 'php']); // models that have every given tag
App\Models\Lesson::withAnyTag(['Laravel', 'php', 'python']); // models that have at least one of the given tags
App\Models\Lesson::hasTags(['Laravel', 'php']); // models that have at least one of the given tags
```

Tag names are normalized to slugs before they are stored or queried, so `Laravel` and `laravel` refer to the same tag.

### Events

`TagAttached` and `TagDetached` are dispatched after tags are actually attached or detached, and only after the surrounding database transaction commits:

```
use Usamamuneerchaudhary\LaravelTagify\Events\TagAttached;
use Usamamuneerchaudhary\LaravelTagify\Events\TagDetached;

Event::listen(TagAttached::class, function (TagAttached $event) {
    $event->taggable;
    $event->tags;
});

Event::listen(TagDetached::class, function (TagDetached $event) {
    $event->taggable;
    $event->tags;
});
```

## Tests
`composer test`

## Security
If you discover any security related issues, please email hello@usamamuneer.me instead of using the issue tracker.

## Credits

- [Usama Muneer](https://github.com/usamamuneerchaudhary)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
