<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Test;

class TagifyTagCountTest extends TestCase
{
    protected $lesson;

    protected function setUp(): void
    {
        parent::setUp();
        $this->lesson = LessonStub::create([
            'title' => 'Lesson Title',
        ]);
    }

    #[Test]
    public function tag_count_is_incremented_when_tagged()
    {
        $tag = $this->createTag('Laravel', 0);
        $this->lesson->tag($tag);
        $tag = $tag->fresh();
        $this->assertEquals(1, $tag->count);

        $this->assertContains('Laravel', $this->lesson->tags->pluck('name'));
    }

    #[Test]
    public function tag_count_is_decremented_when_untagged()
    {
        $tag = $this->createTag('Laravel', 50);
        $this->lesson->tag($tag);
        $this->lesson->untag($tag);
        $tag = $tag->fresh();
        $this->assertEquals(50, $tag->count);

        $this->assertNotContains('Laravel', $this->lesson->tags->pluck('name'));
    }

    #[Test]
    public function tag_count_does_not_dip_below_zero()
    {
        $tag = $this->createTag('Laravel', 0);
        $this->lesson->untag($tag);
        $tag = $tag->fresh();
        $this->assertEquals(0, $tag->count);
        $this->assertNotEquals(-1, $tag->count);

        $this->assertNotContains('Laravel', $this->lesson->tags->pluck('name'));
    }

    #[Test]
    public function tag_count_does_not_increment_if_already_exists()
    {
        $tag = $this->createTag('Laravel', 0);
        $this->lesson->tag($tag);
        $this->lesson->retag($tag);
        $this->lesson->tag($tag);
        $this->lesson->tag($tag);
        $tag = $tag->fresh();
        $this->assertEquals(1, $tag->count);
        $this->assertNotEquals(3, $tag->count);

        $this->assertContains('Laravel', $this->lesson->tags->pluck('name'));
    }
}
