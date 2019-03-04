<?php

namespace Elective\FormatterBundle\Traits;

/**
 * Elective\FormatterBundle\Traits\CacheTaggable
 *
 * @author Kris Rybak <kris.rybak@krisrybak.com>
 */
trait CacheTaggable
{
    /**
     * @var array
     */
    private $tags;

    /**
     * Get Tags
     *
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Get Tags
     *
     * @param $tags array
     * @return self
     */
    public function setTags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Add Tag
     *
     * @param $tag string
     * @return self
     */
    public function addTag(string $tag): self
    {
        $this->tags[] = $tag;

        return $this;
    }
}
