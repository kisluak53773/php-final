<?php

declare(strict_types= 1);

namespace App;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use App\Exceptions\ContainerException;
use ReflectionParameter;
use ReflectionUnionType;
use ReflectionNamedType;

class Container implements ContainerInterface
{
    private array $entries = [];

    public function get(string $id): mixed
    {
        if ($this->has($id)) {
            $id = $this->entries[$id];
        }

        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    public function set(string $id, string $concrete): void
    {
        $this->entries[$id] = $concrete;
    }

    public function resolve(string $id): mixed
    {
        $reflectedClass = new ReflectionClass($id);

        if (! $reflectedClass->isInstantiable()) {
            throw new ContainerException('Class' . $id . 'is not instainable');
        }

        $constructor = $reflectedClass->getConstructor();

        if (! $constructor) {
            return new $id;
        }

        $parameters = $constructor->getParameters();

        if (! $parameters) {
            return new $id;
        }

        $dependencies = array_map(function (ReflectionParameter $param) use($id) {
            $name = $param->getName();
            $type = $param->getType();

            if(! $type){
                throw new ContainerException('Fiald to resolve class' . $id . 
                    'because ' . $name . 'lacks type hinting');
            }

            if ($type instanceof ReflectionUnionType) {
                throw new ContainerException('Failed to resolve class "' . $id . 
                    '" because of union type for param "' . $name . '"');
            }

            if ($type instanceof ReflectionNamedType && ! $type->isBuiltin()) {
                return $this->get($type->getName());
            }

            throw new ContainerException('Failed to resolve class "' . $id . 
                '" because invalid param "' . $name . '"');
        }, $parameters);

        return $reflectedClass->newInstanceArgs($dependencies);
    }
}