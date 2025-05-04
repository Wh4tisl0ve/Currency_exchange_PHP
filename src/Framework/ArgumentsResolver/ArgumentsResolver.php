<?php

namespace App\Framework\ArgumentsResolver;

use App\Framework\ArgumentsResolver\Exception\ArgumentValueNotFound;

class ArgumentsResolver
{
    public static function resolveArguments(array $reflectionMethodParams, array $inputArguments): array
    {
        return array_map(
            fn($methodParam) => ArgumentsResolver::findArgumentValue($methodParam, $inputArguments),
            $reflectionMethodParams
        );
    }

    /**
     * @throws ArgumentValueNotFound
     */
    private static function findArgumentValue($methodParam, array $arguments): mixed
    {
        $paramName = $methodParam->getName();
        $paramType = $methodParam->getType();

        foreach ($arguments as $argument) {
            if (!$paramType->isBuiltin() && $paramType->getName() === get_class($argument)) {
                return $argument;
            }
            if (is_array($argument) && array_key_exists($paramName, $argument)) {
                return $argument[$paramName];
            }
            if ($methodParam->isDefaultValueAvailable()) {
                return $methodParam->getDefaultValue();
            }
        }
        throw new ArgumentValueNotFound("Не найдено значения для параметра $paramName");
    }
}