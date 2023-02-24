<?php

use Illuminate\Support\Str;

class TagifyStringUsageTest extends TestCase
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
        $this->lesson->tag(['laravel', 'php']);

        $this->assertCount(2, $this->lesson->tags);

        foreach (['Laravel', 'PHP'] as $tag) {
            $this->assertContains($tag, $this->lesson->tags->pluck('name'));
        }
    }

    /**
     * @test
     */
    public function can_untag_lesson()
    {
        $this->lesson->tag(['laravel', 'php']);
        $this->lesson->untag(['Laravel']);
        $this->assertCount(1, $this->lesson->tags);

        foreach (['PHP'] as $tag) {
            $this->assertContains($tag, $this->lesson->tags->pluck('name'));
        }
    }

    /**
     * @test
     */
    public function can_untag_all_lesson_tags()
    {
        $this->lesson->tag(['laravel', 'php', 'MySQL']);
        $this->lesson->untag();

        $this->lesson->load('tags');

        $this->assertCount(0, $this->lesson->tags);
        $this->assertEquals(0, $this->lesson->tags->count());
    }

    /**
     * @test
     */
    public function can_retag_lesson_tags()
    {
        $this->lesson->tag(['laravel', 'php', 'MySQL']);
        $this->lesson->retag(['Laravel', 'Livewire', 'Vue JS']);

        $this->lesson->load('tags');

        $this->assertCount(3, $this->lesson->tags);
        foreach (['Laravel', 'Livewire', 'Vue JS'] as $tag) {
            $this->assertContains($tag, $this->lesson->tags->pluck('name'));
        }
    }

    /** @test */
    public function non_existing_tags_are_ignored_on_tagging()
    {
        $this->lesson->tag(['laravel', 'python', 'PostgreSQL', 'Livewire']);
        $this->assertCount(2, $this->lesson->tags);

        foreach (['Laravel', 'Livewire'] as $tag) {
            $this->assertContains($tag, $this->lesson->tags->pluck('name'));
        }

        foreach (['python', 'PostgreSQL'] as $tag) {
            $this->assertNotContains($tag, $this->lesson->tags->pluck('name'));
        }
    }

    /** @test */
    public function inconsistent_tag_cases_are_normalized()
    {
        $this->lesson->tag(['laravel', 'Vue JS', 'React JS']);
        $this->assertCount(3, $this->lesson->tags);

        foreach (['Laravel', 'Vue JS', 'React JS'] as $tag) {
            $this->assertContains($tag, $this->lesson->tags->pluck('name'));
        }
    }
}
