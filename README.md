[![image](https://www.linkpicture.com/q/tagify.png)](https://www.linkpicture.com/view.php?img=LPic63f95691d58321472352071)
### Tagify is a simple Tagging Package for Laravel
Using this package, you can simply tag, untag or retag any existing model in your laravel app.

Here's a quick example of what you can do in your models to enable tagging:

*This package supports Laravel 10*

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
## Run Migrations

Once the package is installed, you can run migrations,

`php artisan migrate`
 
## Service Provider

Don't forget to add the ServiceProvider in `app.php`:

```
Usamamuneerchaudhary\LaravelTagify\TagifyServiceProvider::class,
```
## Usage & Some basic functions

Add Tags to a Model
```
$tags =['Laravel','PHP'];
$model->tag($tags);
```
Untag a tag
```
$model->untag(['Laravel]);
```

Untag all tags
```
$model->untag();
```

Re-tag a tag to a model
```
$model->retag(['Eloquent','mysql']);
```

Get all tags
```
$model->tags;
```

You can also use scopes to get tags associated likeso,
```
App\Model\Lesson::withAllTags(['laravel','php']); //will return if has all tags
App\Model\Lesson::withAnyTags(['laravel','php','python']); //will only return the ones associated, and ignore the 
ones which are not.
App\Model\Lesson::hasTags(['laravel','php']); //check if a model has tags
```
## Tests
`composer test`

## Security
If you discover any security related issues, please email hello@usamamuneer.me instead of using the issue tracker.

## Credits

- [Usama Muneer](https://github.com/usamamuneerchaudhary)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
