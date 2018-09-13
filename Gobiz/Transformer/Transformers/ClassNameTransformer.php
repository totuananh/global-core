<?php

namespace Gobiz\Transformer\Transformers;

use Illuminate\Contracts\Container\Container;
use Gobiz\Transformer\TransformerInterface;

class ClassNameTransformer implements TransformerInterface
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
     * Transform the data
     *
     * @param object $data
     * @return mixed
     */
    public function transform($data)
    {
        return $this->container->make($this->className)->transform($data);
    }
}