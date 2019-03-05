<?php

namespace Elective\FormatterBundle\Transformer;

/**
 * Elective\FormatterBundle\Transformer\TransformerInterface
 *
 * @author Kris Rybak <kris.rybak@electivegroup.com>
 */
interface TransformerInterface
{
    /**
     * Transforms object
     */
    public function transform($model);
}
