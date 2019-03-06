<?php

namespace Elective\FormatterBundle\Entity\DTO;

/**
 * Elective\FormatterBundle\Entity\DTO\Collection
 *
 * @author Kris Rybak <kris.rybak@krisrybak.com>
 */
class Collection
{
    /**
     * Result set
     */
    private $results;

    /**
     * Total possible Count of the collection
     *
     * @var int
     */
    private $totalCount;

    public function __construct($results = null, $totalCount = 0)
    {
        $this->results      = $results;
        $this->totalCount   = $totalCount;
    }

    /**
     * Get Results
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Set Results
     *
     * @param $results mixed
     * @return self
     */
    public function setResults($results): self
    {
        $this->results = $results;

        return $this;
    }

    /**
     * Get totalCount
     *
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * Set totalCount
     *
     * @param $totalCount int
     * @return self
     */
    public function setTotalCount(int $totalCount): self
    {
        $this->totalCount = $totalCount;

        return $this;
    }
}
