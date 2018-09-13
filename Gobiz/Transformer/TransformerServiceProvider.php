<?php

namespace Gobiz\Transformer;

use Gobiz\Transformer\Finders\ClassNameTransformerFinder;
use Gobiz\Transformer\Finders\ModelTransformerFinder;
use Gobiz\Transformer\Transformers\ValidationErrorTransformer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Validator;
use InvalidArgumentException;
use Gobiz\Transformer\Transformers\ClassNameTransformer;
use Gobiz\Transformer\Transformers\DefaultTransformer;
use Gobiz\Transformer\Transformers\MessageBagTransformer;
use Gobiz\Transformer\Transformers\PaginatorTransformer;

class TransformerServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * @var array
     */
    protected $baseTransformers = [
        Validator::class => ValidationErrorTransformer::class,
        LengthAwarePaginator::class => PaginatorTransformer::class,
        MessageBag::class => MessageBagTransformer::class,
    ];

    /**
     * Register service
     */
    public function register()
    {
        $this->app->singleton(TransformerManagerInterface::class, function () {
            return $this->makeTransformerManager();
        });
    }

    /**
     * @return TransformerManager
     */
    protected function makeTransformerManager()
    {
        $transformers = new TransformerManager(new DefaultTransformer());

        foreach ($this->getTransformers() as $class => $transformer) {
            $transformers->map($class, $this->normalizeTransformer($transformer));
        }

        foreach ($this->getTransformerFinders() as $finder) {
            $transformers->finder($this->normalizeTransformerFinder($finder));
        }

        return $transformers;
    }

    /**
     * @return array
     */
    protected function getTransformers()
    {
        $configuredTransformers = config('api.transformers', []);
        $baseTransformers = Arr::except($this->baseTransformers, array_keys($configuredTransformers));

        return array_merge($configuredTransformers, $baseTransformers);
    }

    /**
     * @param string|TransformerInterface $transformer
     * @return TransformerInterface
     * @throws InvalidArgumentException
     */
    protected function normalizeTransformer($transformer)
    {
        if ($transformer instanceof TransformerInterface) {
            return $transformer;
        }

        if (is_string($transformer)) {
            return new ClassNameTransformer($this->app, $transformer);
        }

        throw new InvalidArgumentException('The transformer invalid');
    }

    /**
     * @return array
     */
    protected function getTransformerFinders()
    {
        $finders = config('api.transformer_finders', []);
        $finders[] = new ModelTransformerFinder();

        return $finders;
    }

    /**
     * @param string|TransformerFinderInterface $finder
     * @return TransformerFinderInterface
     * @throws InvalidArgumentException
     */
    protected function normalizeTransformerFinder($finder)
    {
        if ($finder instanceof TransformerFinderInterface) {
            return $finder;
        }

        if (is_string($finder)) {
            return new ClassNameTransformerFinder($this->app, $finder);
        }

        throw new InvalidArgumentException('The transformer finder invalid');
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [
            TransformerManagerInterface::class,
        ];
    }
}