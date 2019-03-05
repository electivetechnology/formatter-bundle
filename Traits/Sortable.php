<?php

namespace Elective\FormatterBundle\Traits;

use Elective\FormatterBundle\Request\HandlerInterface;
use Elective\FormatterBundle\Exception\ApiException;
use Elective\FormatterBundle\Exception\ErrorCode;
use Ucc\Data\Types\Pseudo\SortType;
use Ucc\Exception\Data\InvalidDataValueException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Elective\FormatterBundle\Traits\Sortable
 *
 * @author Kris Rybak <kris.rybak@krisrybak.com>
 */
trait Sortable
{
    /**
     * Gets list of sorts for this request
     *
     * @param   array       List of fields to sort by
     * @return  array       List of Sort objects
     */
    public function getSorts($validSorts = array()): array
    {
        if (!isset($this->handler) || (!$this->handler instanceof HandlerInterface)) {
            return array();
        }

        $sorts = $this->handler->getSorts();

        try {
            // Get sorts
            $sorts = SortType::check($sorts, $validSorts);
        } catch (InvalidDataValueException $e) {
            throw new ApiException($e->getMessage(), ErrorCode::MALFORMED_SORT, Response::HTTP_BAD_REQUEST);
        }

        return $sorts;
    }
}
