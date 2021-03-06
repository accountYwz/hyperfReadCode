<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Hyperf\Di\Definition;

use http\Env\Response;
use Hyperf\Di\ReflectionManager;
use ReflectionFunctionAbstract;
use function class_exists;
use function interface_exists;
use function is_callable;
use function is_string;
use function method_exists;

class DefinitionSource implements DefinitionSourceInterface
{
    /**
     * @var array
     */
    private $source;

    public function __construct(array $source)
    {

        $this->source = $this->normalizeSource($source);

    }

    /**
     * Returns the DI definition for the entry name.
     */
    public function getDefinition(string $name): ?DefinitionInterface
    {
        return $this->source[$name] ?? $this->source[$name] = $this->autowire($name);
    }

    /**
     * @return array definitions indexed by their name
     */
    public function getDefinitions(): array
    {
        return $this->source;
    }

    /**
     * @param array|callable|string $definition
     */
    public function addDefinition(string $name, $definition): self
    {
        $this->source[$name] = $this->normalizeDefinition($name, $definition);
        return $this;
    }

    public function clearDefinitions(): void
    {
        $this->source = [];
    }

    /**
     * Read the type-hinting from the parameters of the function.
     */
    private function getParametersDefinition(ReflectionFunctionAbstract $constructor): array
    {
        $parameters = [];

        foreach ($constructor->getParameters() as $index => $parameter) {
            // Skip optional parameters.
            if ($parameter->isOptional()) {
                continue;
            }
            //var_dump('-----$parameter-----------------');
            //var_dump($parameter);
            //比如为啥这里获取到manager就能获取到CacheManager?
            $parameterClass = $parameter->getClass();
            //var_dump('------$parameterClass------------');
            //var_dump($parameterClass);
            if ($parameterClass) {
                //var_dump('------$parameterClass->getName()------------');

                //var_dump($parameterClass->getName());
                $parameters[$index] = new Reference($parameterClass->getName());
            }
        }

        return $parameters;
    }

    /**
     * 将用户定义源规范化为标准定义源。
     * Normaliaze the user definition source to a standard definition souce.
     */
    private function normalizeSource(array $source): array
    {
        $definitions = [];

        foreach ($source as $identifier => $definition) {
           $normalizedDefinition = $this->normalizeDefinition($identifier, $definition);
//                var_dump('--------$normalizeSource-----------');
//                var_dump($normalizedDefinition);
           if (! is_null($normalizedDefinition)) {
               $definitions[$identifier] = $normalizedDefinition;
           }

        }
        return $definitions;
    }

    /**
     * @param array|callable|string $definition
     */
    private function normalizeDefinition(string $identifier, $definition): ?DefinitionInterface
    {
        if (is_string($definition) && class_exists($definition)) {
            if (method_exists($definition, '__invoke')) {
                return new FactoryDefinition($identifier, $definition, []);
            }
            //依赖中的接口走这里
            return $this->autowire($identifier, new ObjectDefinition($identifier, $definition));
        }
        if (is_callable($definition)) {
            return new FactoryDefinition($identifier, $definition, []);
        }
        return null;
    }

    private function autowire(string $name, ObjectDefinition $definition = null): ?ObjectDefinition
    {
        $className = $definition ? $definition->getClassName() : $name;
        if (! class_exists($className) && ! interface_exists($className)) {
            return $definition;
        }

        $definition = $definition ?: new ObjectDefinition($name);

        //var_dump('-----------$definition100000000000-----------');
        //var_dump($definition);
        //var_dump('---------------------$className-----------------------');
        //var_dump($className);
        /**
         * Constructor.
         */
        $class = ReflectionManager::reflectClass($className);
        //获取类的构造函数
        $constructor = $class->getConstructor();
        //var_dump('---------$constructor--------');
        //var_dump($constructor);

        if ($constructor && $constructor->isPublic()) {
            $constructorInjection = new MethodInjection('__construct', $this->getParametersDefinition($constructor));
            //var_dump('---------$constructorInjection--------');

            //var_dump($constructorInjection);
            $definition->completeConstructorInjection($constructorInjection);
        }

        return $definition;
    }
}
