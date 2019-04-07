<?php
/**
 * Created by PhpStorm.
 * User: ixapek
 * Date: 29.03.2019
 * Time: 19:42
 */

namespace ixapek\TypedCollection;


interface ITypedCollection extends \ArrayAccess, \Iterator
{
    const COLLECTION_TYPE_INT = 0;
    const COLLECTION_TYPE_FLOAT = 1;
    const COLLECTION_TYPE_NUMERIC = 2;
    const COLLECTION_TYPE_STRING = 3;
    const COLLECTION_TYPE_BOOL = 4;
    const COLLECTION_TYPE_NULL = 5;
    const COLLECTION_TYPE_ARRAY = 6;
    const COLLECTION_TYPE_OBJECT = 7;
    const COLLECTION_TYPE_RESOURCE = 8;
    const COLLECTION_TYPE_SCALAR = 9;
    const COLLECTION_TYPE_CALLABLE = 10;

    /**
     * @return int
     */
    public function getType():int;

    /**
     * @param int $type
     * @param int|string|float|array|resource|object|null|callable    $value
     * @return bool
     */
    public function checkInstanceType(int $type, $value):bool;

    /**
     * @param int $type
     * @return bool
     */
    public function existsType(int $type):bool;
}