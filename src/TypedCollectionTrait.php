<?php
/**
 * Created by PhpStorm.
 * User: ixapek
 * Date: 29.03.2019
 * Time: 18:57
 */

namespace ixapek\TypedCollection;


use Throwable;

trait TypedCollectionTrait
{
    /** @var array $holder Really, this is array */
    protected $holder = [];
    /** @var int $type Collection type */
    protected $type;
    /** @var string|null $instanceName Classname for type ITypedCollection::COLLECTION_TYPE_OBJECT */
    protected $instanceName;

    /**
     * @var bool $throwException In strict mode using $instance for non-objects or adding values of incompatible type
     *                            methods throw InvalidTypeException.
     *                           With non-strict this operations do nothing and silent,
     *                            exclude unsupported types for ->setType() method
     */
    protected $throwException = true;

    /** @var array $typeChecker Available type indexes with function for type checking */
    protected static $typeChecker = [
        ITypedCollection::COLLECTION_TYPE_INT      => 'is_int',
        ITypedCollection::COLLECTION_TYPE_FLOAT    => 'is_float',
        ITypedCollection::COLLECTION_TYPE_NUMERIC  => 'is_numeric',
        ITypedCollection::COLLECTION_TYPE_STRING   => 'is_string',
        ITypedCollection::COLLECTION_TYPE_BOOL     => 'is_bool',
        ITypedCollection::COLLECTION_TYPE_NULL     => 'is_null',
        ITypedCollection::COLLECTION_TYPE_ARRAY    => 'is_array',
        ITypedCollection::COLLECTION_TYPE_OBJECT   => 'is_object',
        ITypedCollection::COLLECTION_TYPE_RESOURCE => 'is_resource',
        ITypedCollection::COLLECTION_TYPE_SCALAR   => 'is_scalar',
        ITypedCollection::COLLECTION_TYPE_CALLABLE => 'is_callable',
    ];

    /**
     * @return string
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return TypedCollectionTrait
     * @throws UnsupportedTypeException
     */
    public function setType(int $type): self
    {
        if (false === $this->existsType($type)) {
            throw new UnsupportedTypeException("Function for check type with index $type not registered");
        }

        if (false === is_callable(self::$typeChecker[$type])) {
            throw new UnsupportedTypeException("Function for check type with index $type isn't callable");
        }

        $this->type = $type;
        return $this;
    }

    /**
     * @param int $type
     * @return bool
     */
    public function existsType(int $type):bool{
        return array_key_exists($type, self::$typeChecker);
    }

    /**
     * @return bool
     */
    public function isThrowException(): bool
    {
        return $this->throwException;
    }

    /**
     * @param bool $throwException
     * @return self
     */
    public function setThrowException(bool $throwException): self
    {
        $this->throwException = $throwException;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getInstanceName(): string
    {
        return $this->instanceName;
    }

    /**
     * @param string|null $instanceName
     * @return self
     * @throws InvalidTypeException
     */
    public function setInstanceName(string $instanceName): self
    {
        $typeIsObject = ($this->getType() === ITypedCollection::COLLECTION_TYPE_OBJECT);
        if (false === $typeIsObject) {
            $this->processInvalidType("InstanceName need only for objects");
        }

        $classExists = class_exists($instanceName);
        if (false === $classExists) {
            $this->processInvalidType("Class $instanceName not found");
        }

        if (true === ($typeIsObject && $classExists)) {
            $this->instanceName = $instanceName;
        }

        return $this;
    }

    /**
     * @param mixed $value
     * @return bool
     * @throws InvalidTypeException
     */
    protected function checkInstance($value): bool
    {
        $checkType = $this->checkInstanceType($this->getType(), $value);
        if (false === $checkType) {
            $this->processInvalidType("Instance isn't compatible with collection type");
        }

        $checkInstance = (
            $this->getType() !== ITypedCollection::COLLECTION_TYPE_OBJECT ||
            true === ($value instanceof $this->instanceName)
        );

        if (false === $checkInstance) {
            $this->processInvalidType("Instance isn't compatible with collection type");
        }

        return ($checkType && $checkInstance);
    }

    public function checkInstanceType(int $type, $value):bool{
        return call_user_func(self::$typeChecker[$type], $value);
    }

    /**
     * @param string|InvalidTypeException $throw
     * @throws InvalidTypeException
     */
    protected function processInvalidType($throw)
    {
        if (true === $this->isThrowException()) {
            if (true === is_string($throw)) {
                $throw = new InvalidTypeException($throw);
            } elseif (false === ($throw instanceof Throwable)) {
                $throw = new InvalidTypeException("Exception isn't throwable");
            }

            throw $throw;
        }
    }


    /**
     * Return the current element
     * @link  https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return current($this->holder);
    }

    /**
     * Move forward to next element
     * @link  https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        next($this->holder);
    }

    /**
     * Return the key of the current element
     * @link  https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return key($this->holder);
    }

    /**
     * Checks if current position is valid
     * @link  https://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return false !== $this->current();
    }

    /**
     * Rewind the Iterator to the first element
     * @link  https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        reset($this->holder);
    }

    /**
     * Whether a offset exists
     * @link  https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset An offset to check for.
     * @return boolean true on success or false on failure.
     *                      The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->holder[$offset]);
    }

    /**
     * Offset to retrieve
     * @link  https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset The offset to retrieve.
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return isset($this->holder[$offset]) ? $this->holder[$offset] : null;
    }

    /**
     * Offset to set
     * @link  https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value  The value to set.
     * @return void
     * @since 5.0.0
     * @throws InvalidTypeException
     */
    public function offsetSet($offset, $value)
    {
        if ($this->checkInstance($value)) {
            if (is_null($offset)) {
                $this->holder[] = $value;
            } else {
                $this->holder[$offset] = $value;
            }
        }
    }

    /**
     * Offset to unset
     * @link  https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset The offset to unset.
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->holder[$offset]);
    }
}