<?php

namespace Elective\FormatterBundle\Traits;

use Elective\FormatterBundle\Request\HandlerInterface;
use Elective\FormatterBundle\Exception\ApiException;
use Elective\FormatterBundle\Exception\ErrorCode;
use Ucc\Data\Types\Pseudo\DisplayType;
use Ucc\Exception\Data\InvalidDataValueException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Elective\FormatterBundle\Traits\Displayable
 *
 * @author Kris Rybak <kris.rybak@krisrybak.com>
 */
trait Displayable
{
    /**
     * Gets list of display for this request
     *
     * @param   array       List of fields to display
     * @return  array       List of Display objects
     */
    public function getDisplay($validDisplay = array()): array
    {
        if (!isset($this->handler) || (!$this->handler instanceof HandlerInterface)) {
            return array();
        }

        $display = $this->handler->getDisplay();

        try {
            // Get display
            $display = DisplayType::check($display, $validDisplay);
        } catch (InvalidDataValueException $e) {
            throw new ApiException($e->getMessage(), ErrorCode::MALFORMED_DISPLAY, Response::HTTP_BAD_REQUEST);
        }

        return $display;
    }

    /**
     * Generates query version of display, i.e. '&display[0]=candidate.firstName-name'
     *
     * @param array $display    Array of display: ['candidate.firstName-name', 'candidate.lastName']
     */
    public static function getUrlQueryDisplay(array $displays): string
    {
        $query = '';

        foreach ($displays as $display) {
            $query .= '&display[]='. $display;
        }

        return $query;
    }
}
