<?php

namespace Elective\FormatterBundle\Traits;

use Elective\FormatterBundle\Request\HandlerInterface;
use Elective\FormatterBundle\Exception\ApiException;
use Elective\FormatterBundle\Exception\ErrorCode;
use Ucc\Data\Types\Pseudo\FilterType;
use Ucc\Exception\Data\InvalidDataValueException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Elective\FormatterBundle\Traits\Filterable
 *
 * @author Kris Rybak <kris.rybak@krisrybak.com>
 */
trait Filterable
{
    /**
     * Gets list of filters for this request
     *
     * @param   array       List of fields to filter by
     * @return  array       List of Filter objects
     */
    public function getFilters(array $validFilters): array
    {
        if (!isset($this->handler) || (!$this->handler instanceof HandlerInterface)) {
            return array();
        }

        $filters = $this->handler->getFilters();

        // Build array of filters
        if (!empty($filters)) {
            // Check if we have nested filters
            if (count($filters) !== count($filters, COUNT_RECURSIVE)) {
                foreach ($filters as $filter) {
                    $nested[] = $filter;
                }

                $filters = $nested;
            } else {
                $filters = array($filters);
            }
        }

        // Now we need to create map of filters
        // as filters can have multiple dimensions
        $filtersMap = array();

        try {
            foreach ($filters as $namespace => $filter) {
                $logic = false;

                if (is_array($filter)){
                    if (array_key_exists('logic', $filter)){
                        $logic = $filter['logic'];
                        unset($filter['logic']);
                    }
                }

                $filtersMap[$namespace] = FilterType::check($filter, $validFilters);

                if ($logic) {
                    $filtersMap[$namespace]->setLogic($logic);
                }
            }

            if (isset($filtersMap['private'])) {
                $filtersMap['private']->setLogic('or');
            }
        } catch (InvalidDataValueException $e) {
            throw new ApiException($e->getMessage(), ErrorCode::MALFORMED_FILTER, Response::HTTP_BAD_REQUEST);
        }

        return $filtersMap;
    }
}
