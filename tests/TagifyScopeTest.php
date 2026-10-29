<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;

class TagifyScopeTest extends TestCase
{
    protected $laravelLesson;

    protected $phpLesson;

    protected $livewireLesson;

    protected function setUp(): void
    {
        parent::setUp();

        $tags = ['PHP', 'Laravel', 'Livewire'];
        foreach ($tags as $tag) {
            TagStub::create([
                'name' => $tag,
                'slug' => Str::slug($tag),
                'count' => 0,
            ]);
        }

        $this->laravelLesson = LessonStub::create(['title' => 'Laravel Lesson']);
        $this->phpLesson = LessonStub::create(['title' => 'PHP Lesson']);
        $this->livewireLesson = LessonStub::create(['title' => 'Livewire Lesson']);

        $this->laravelLesson->tag(['Laravel', 'PHP']);
        $this->phpLesson->tag(['PHP']);
        $this->livewireLesson->tag(['Livewire']);
    }

    #[Test]
    public function has_tags_matches_any_tag_using_normalized_slugs()
    {
        $lessons = LessonStub::hasTags(['Laravel'])->get();

        $this->assertTrue($lessons->contains($this->laravelLesson));
        $this->assertFalse($lessons->contains($this->phpLesson));
        $this->assertFalse($lessons->contains($this->livewireLesson));
    }

    #[Test]
    public function with_any_tag_matches_models_that_have_at_least_one_tag()
    {
        $lessons = LessonStub::withAnyTag(['Laravel', 'Livewire'])->get();

        $this->assertTrue($lessons->contains($this->laravelLesson));
        $this->assertTrue($lessons->contains($this->livewireLesson));
        $this->assertFalse($lessons->contains($this->phpLesson));
    }

    #[Test]
    public function with_all_tags_matches_models_that_have_every_tag()
    {
        $lessons = LessonStub::withAllTags(['Laravel', 'PHP'])->get();

        $this->assertTrue($lessons->contains($this->laravelLesson));
        $this->assertFalse($lessons->contains($this->phpLesson));
        $this->assertFalse($lessons->contains($this->livewireLesson));
    }
}
