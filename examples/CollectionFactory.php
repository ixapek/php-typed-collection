<?php
/**
 * Created by PhpStorm.
 * User: ixapek
 * Date: 05.04.2019
 * Time: 23:56
 */

namespace ixapek\TypedCollection;


use Exception;

class CollectionFactory
{
    /**
     * @param int         $type
     * @param string|null $instanceName
     * @param bool        $throwException
     * @return ITypedCollection
     */
    public static function init(int $type, string $instanceName = null, bool $throwException = true): ITypedCollection
    {
        try {
            $instance = new TypedCollection();
            $instance->setType($type);

            if (null !== $instanceName) {
                $instance->setInstanceName($instanceName);
            }
        } catch (Exception $e) {
            $instance = new NullCollection();
        }

        return $instance->setThrowException($throwException);
    }

    /**
     * @param int|string|float|array|resource|object|null|callable $element
     * @param int|string|null                                      $offset
     * @param bool                                                 $throwException
     * @return ITypedCollection
     */
    public static function initFor($element, $offset = 0, bool $throwException = true): ITypedCollection
    {
        try {
            if (true === is_int($element)) {
                $instance = new IntCollection();
            } elseif (true === is_object($element)) {
                $instance = new ObjectCollection(get_class($element));
            } else {
                throw new UnsupportedTypeException("Unsupported collection type");
            }

            if (null !== $offset) {
                $instance->offsetSet($offset, $element);
            }
        } catch (Exception $e) {
            $instance = new NullCollection();
        }

        return $instance->setThrowException($throwException);
    }

    /**
     * @param array    $challenger
     * @param int|null $type
     * @param bool     $throwException
     * @return ITypedCollection
     */
    public static function initFrom(array $challenger, int $type = null, bool $throwException = true): ITypedCollection
    {

        $firstElement = reset($challenger);
        if (null === $type) {
            $instance = self::initFor($firstElement, key($challenger), $throwException);
        } else {
            $instanceName = null;
            if ($type === ITypedCollection::COLLECTION_TYPE_OBJECT) {
                $instanceName = get_class($firstElement);
            }
            $instance = self::init($type, $instanceName, $throwException);
        }

        foreach ($challenger as $offset => $value) {
            $instance->offsetSet($offset, $value);
        }

        return $instance;
    }
}