<?php

use Illuminate\Support\Str;

class TagifyModelUsageTest extends TestCase
{

    protected $lesson;

    public function setUp(): void
    {
        parent::setUp();
        $tags = ['PHP', 'Laravel', 'Livewire', 'Vue JS', 'Redis', 'MySQL', 'React JS'];
        foreach ($tags as $tag) {
            \TagStub::create([
                'name' => $tag,
                'slug' => Str::slug($tag),
                'count' => 0
            ]);
        }
        $this->lesson = \LessonStub::create([
            'title' => 'Lesson Title'
        ]);
    }

    /**
     * @test
     */
    public function can_tag_lesson()
    {
        $this->lesson->tag(TagStub::where('slug', 'laravel')->first());

        $this->assertCount(1, $this->lesson->tags);

        $this->assertContains('Laravel', $this->lesson->tags->pluck('name'));
    }

    /**
     * @test
     */
    public function can_tag_lesson_with_collection_of_tags()
    {
        $tags = TagStub::whereIn('slug', ['laravel', 'php'])->get();

        $this->lesson->tag($tags);
        $this->assertCount(2, $this->lesson->tags);

        foreach (['Laravel', 'PHP'] as $tag) {
            $this->assertContains($tag, $this->lesson->tags->pluck('name'));
        }
    }

    /**
     * @test
     */
    public function can_untag_lesson_tags()
    {
        $tags = TagStub::whereIn('slug', ['laravel', 'php', 'livewire'])->get();
        $this->lesson->tag($tags);
        $this->lesson->untag($tags->first());

        $this->assertCount(2, $this->lesson->tags);

        foreach (['Livewire', 'PHP'] as $tag) {
            $this->assertContains($tag, $this->lesson->tags->pluck('name'));
        }
    }

    /**
     * @test
     */
    public function can_untag_all_lesson_tags()
    {
        $tags = TagStub::whereIn('slug', ['laravel', 'php', 'livewire'])->get();
        $this->lesson->tag($tags);
        $this->lesson->untag();
        $this->lesson->load('tags');
        $this->assertCount(0, $this->lesson->tags);

        foreach (['laravel', 'php', 'livewire'] as $tag) {
            $this->assertNotContains($tag, $this->lesson->tags->pluck('slug'));
        }
    }

    /**
     * @test
     */
    public function can_retag_lesson_tags()
    {

        $tags = TagStub::whereIn('slug', ['laravel', 'php', 'MySQL'])->get();
        $retags = TagStub::whereIn('slug', ['laravel', 'livewire', 'vue-js'])->get();
        $this->lesson->tag($tags);
        $this->lesson->retag($retags);

        $this->lesson->load('tags');

        $this->assertCount(3, $this->lesson->tags);
        foreach (['Laravel', 'Livewire', 'Vue JS'] as $tag) {
            $this->assertContains($tag, $this->lesson->tags->pluck('name'));
        }
    }
    /** @test */
    public function non_models_are_filtered_when_using_collections()
    {
        $tags = TagStub::whereIn('slug', ['laravel', 'php', 'mysql'])->get();
        $tags->push('not a tag model');
        $this->lesson->tag($tags);
        $this->assertCount(3, $this->lesson->tags);

    }

    /** @test */
    public function non_existing_tags_are_ignored_on_tagging()
    {
        $tags = TagStub::whereIn('slug', ['laravel', 'python', 'postgreSQL', 'livewire'])->get();

        $this->lesson->tag($tags);
        $this->lesson->load('tags');
        $this->assertCount(2, $this->lesson->tags);

        foreach (['Laravel', 'Livewire'] as $tag) {
            $this->assertContains($tag, $this->lesson->tags->pluck('name'));
        }

        foreach (['python', 'PostgreSQL'] as $tag) {
            $this->assertNotContains($tag, $this->lesson->tags->pluck('name'));
        }
    }


}
