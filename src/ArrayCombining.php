<?php

namespace Inilim\ArrayCombining;

final class ArrayCombining
{
    /**
     * @template One of array
     * 
     * @param One[] $arrayOne
     * @param array<mixed>[] $arrayMany
     * @param string|int $keyArrayOne
     * @param string|int $keyArrayMany
     * @param string|int $finalKey
     * @param (string|int)[] $exceptKeysFromArrayOne
     * @param (string|int)[] $exceptKeysFromArrayMany
     * @throws \InvalidArgumentException
     * @return One[]
     */
    function oneToMany(
        array $arrayOne,
        array $arrayMany,
        $keyArrayOne,
        $keyArrayMany,
        $finalKey,
        array $exceptKeysFromArrayOne  = [],
        array $exceptKeysFromArrayMany = []
    ): array {

        $t = $this->last($arrayOne);

        if ($t === false) {
            return [];
        }

        if (!\is_array($t)) {
            throw new \InvalidArgumentException('invalid value "$arrayOne" not multidimensional array');
        }

        if (!\array_key_exists($keyArrayOne, $t)) {
            throw new \InvalidArgumentException(\sprintf(
                'invalid value "$arrayOne" not found key $keyArrayOne: "%s"',
                $keyArrayOne
            ));
        }

        if (
            !(\is_int($t[$keyArrayOne]) || \is_string($t[$keyArrayOne]))
        ) {
            throw new \InvalidArgumentException(\sprintf(
                'invalid value "$arrayOne" value by key $keyArrayOne: "%s" must be a integer or a string',
                $keyArrayOne
            ));
        }

        // ---------------------------------------------
        // 
        // ---------------------------------------------

        $hasExceptOne  = !!$exceptKeysFromArrayOne;
        if ($hasExceptOne) {
            $exceptKeysFromArrayOne = \array_flip($exceptKeysFromArrayOne);
        }
        $hasExceptMany = !!$exceptKeysFromArrayMany;
        if ($hasExceptMany) {
            $exceptKeysFromArrayMany = \array_flip($exceptKeysFromArrayMany);
        }

        // ---------------------------------------------
        // 
        // ---------------------------------------------

        $arrayMany = $this->mapToGroups(
            $arrayMany,
            static function ($item) use ($keyArrayMany, $hasExceptMany, $exceptKeysFromArrayMany) {
                return [
                    $item[$keyArrayMany] ?? -1 => $hasExceptMany ? \array_diff_key($item, $exceptKeysFromArrayMany) : $item
                ];
            }
        );
        unset($arrayMany[-1]);

        // ---------------------------------------------
        // 
        // ---------------------------------------------

        $result = [];
        foreach ($arrayOne as $aOne) {
            $t = $aOne;

            $t[$finalKey] = [];

            $kConn = $t[$keyArrayOne] ?? null;

            if ($kConn !== null && \array_key_exists($kConn, $arrayMany)) {
                $t[$finalKey] = $arrayMany[$kConn];
            }

            if ($hasExceptOne) {
                $t = \array_diff_key($t, $exceptKeysFromArrayOne);
            }
            $result[] = $t;
        }

        return $result;
    }

    /**
     * @template Primary of array
     * 
     * @param Primary[] $arrayPrimary
     * @param array<mixed>[] $arraySecondary
     * @param string|int $keyArrayPrimary
     * @param string|int $keyArraySecondary
     * @param string|int $finalKey
     * @param (string|int)[] $exceptKeysFromArrayPrimary
     * @param (string|int)[] $exceptKeysFromArraySecondary
     * @throws \InvalidArgumentException
     * @return Primary[]
     */
    function oneToOne(
        array $arrayPrimary,
        array $arraySecondary,
        $keyArrayPrimary,
        $keyArraySecondary,
        $finalKey,
        array $exceptKeysFromArrayPrimary   = [],
        array $exceptKeysFromArraySecondary = []
    ): array {
        return \array_map(function ($item) use ($finalKey) {
            $last = $this->last($item[$finalKey] ?? []);
            $item[$finalKey] = $last === false ? null : $last;
            return $item;
        }, $this->oneToMany(
            $arrayPrimary,
            $arraySecondary,
            $keyArrayPrimary,
            $keyArraySecondary,
            $finalKey,
            $exceptKeysFromArrayPrimary,
            $exceptKeysFromArraySecondary
        ));
    }

    /**
     * @template TValue
     * @param TValue[] $array
     * @return TValue|false
     */
    protected function last(array $array)
    {
        return \end($array);
    }

    /**
     * @template TValue
     * @param TValue[] $array
     * @param callable(TValue):array<mixed> $callback
     */
    protected function mapToGroups(array $array, callable $callback): array
    {
        return \array_reduce(
            \array_map($callback, $array),
            static function ($groups, $pair) {
                $groups[\key($pair)][] = \reset($pair);
                return $groups;
            }
        );
    }
}
