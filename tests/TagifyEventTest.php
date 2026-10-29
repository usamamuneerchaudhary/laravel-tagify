<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Usamamuneerchaudhary\LaravelTagify\Events\TagAttached;
use Usamamuneerchaudhary\LaravelTagify\Events\TagDetached;

class TagifyEventTest extends TestCase
{
    protected $lesson;

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

        $this->lesson = LessonStub::create([
            'title' => 'Lesson Title',
        ]);
    }

    #[Test]
    public function tagging_dispatches_tag_attached_for_newly_attached_tags()
    {
        Event::fake([TagAttached::class, TagDetached::class]);

        $this->lesson->tag(['Laravel', 'PHP']);

        Event::assertDispatched(TagAttached::class, function (TagAttached $event) {
            return $event->taggable->is($this->lesson)
                && $event->tags->pluck('slug')->sort()->values()->all() === ['laravel', 'php'];
        });
        Event::assertNotDispatched(TagDetached::class);
    }

    #[Test]
    public function tagging_already_attached_tags_does_not_dispatch_tag_attached()
    {
        $this->lesson->tag(['Laravel']);

        Event::fake([TagAttached::class, TagDetached::class]);

        $this->lesson->tag(['Laravel']);

        Event::assertNotDispatched(TagAttached::class);
        Event::assertNotDispatched(TagDetached::class);
    }

    #[Test]
    public function untagging_dispatches_tag_detached_for_removed_tags()
    {
        $this->lesson->tag(['Laravel', 'PHP']);

        Event::fake([TagAttached::class, TagDetached::class]);

        $this->lesson->untag(['Laravel']);

        Event::assertDispatched(TagDetached::class, function (TagDetached $event) {
            return $event->taggable->is($this->lesson)
                && $event->tags->pluck('slug')->all() === ['laravel'];
        });
        Event::assertNotDispatched(TagAttached::class);
    }

    #[Test]
    public function untagging_a_tag_that_is_not_attached_does_not_dispatch_tag_detached()
    {
        $this->lesson->tag(['Laravel']);

        Event::fake([TagAttached::class, TagDetached::class]);

        $this->lesson->untag(['PHP']);

        Event::assertNotDispatched(TagDetached::class);
        Event::assertNotDispatched(TagAttached::class);
    }

    #[Test]
    public function retagging_dispatches_detached_then_attached_events()
    {
        $this->lesson->tag(['Laravel', 'PHP']);

        Event::fake([TagAttached::class, TagDetached::class]);

        $this->lesson->retag(['Livewire']);

        Event::assertDispatched(TagDetached::class, function (TagDetached $event) {
            return $event->taggable->is($this->lesson)
                && $event->tags->pluck('slug')->sort()->values()->all() === ['laravel', 'php'];
        });
        Event::assertDispatched(TagAttached::class, function (TagAttached $event) {
            return $event->taggable->is($this->lesson)
                && $event->tags->pluck('slug')->all() === ['livewire'];
        });
    }
}
