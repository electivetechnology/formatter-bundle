<?php

namespace Elective\FormatterBundle\Transformer;

use Elective\FormatterBundle\Transformer\TransformerInterface;

/**
 * Elective\FormatterBundle\Transformer\AbstractCollection
 *
 * @author Kris Rybak <kris.rybak@electivegroup.com>
 */
abstract class AbstractCollection
{
    /**
     * Model Transformer
     *
     * @var TransformerInterface
     */
    private $modelTransformer;

    public function __construct(TransformerInterface $modelTransformer)
    {
        $this->modelTransformer = $modelTransformer;
    }

    /**
     * Get ModelTransformer
     *
     * @return TransformerInterface
     */
    public function getModelTransformer(): TransformerInterface
    {
        return $this->modelTransformer;
    }

    /**
     * Set ModelTransformer
     *
     * @param TransformerInterface
     * @return self
     */
    public function setModelTransformer(TransformerInterface $modelTransformer): self
    {
        $this->modelTransformer = $modelTransformer;

        return $this;
    }

    /**
     * Transforms collection
     *
     * @param $collection iterable
     * @return array
     */
    public function transform(iterable $collection = null): array
    {
        $ret = array();

        foreach ($collection as $model){
            array_push($ret, $this->getModelTransformer()->transform($model));
        }

        return $ret;
    }
}
