<?php

namespace Gobiz\Transformer\Finders;

use Gobiz\Transformer\TransformerFinderInterface;
use Illuminate\Contracts\Container\Container;
use Gobiz\Transformer\TransformerInterface;

class ClassNameTransformerFinder implements TransformerFinderInterface
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $className;

    /**
     * ClassNameTransformer constructor
     *
     * @param Container $container
     * @param string $className
     */
    public function __construct(Container $container, $className)
    {
        $this->container = $container;
        $this->className = $className;

        $this->container->singleton($className, $className);
    }

    /**
     * Find the corresponding transformer of given data
     *
     * @param object $data
     * @return TransformerInterface|null
     */
    public function find($data)
    {
        return $this->container->make($this->className)->transform($data);
    }
}