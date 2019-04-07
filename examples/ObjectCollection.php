<?php
/**
 * Created by PhpStorm.
 * User: ixapek
 * Date: 01.04.2019
 * Time: 22:36
 */

namespace ixapek\TypedCollection;

class ObjectCollection extends TypedCollection
{
    /**
     * SimpleTypedCollection constructor.
     * @param string $instanceName
     * @throws InvalidTypeException
     * @throws UnsupportedTypeException
     */
    public function __construct(string $instanceName)
    {
        $this
            ->setType(ITypedCollection::COLLECTION_TYPE_OBJECT)
            ->setInstanceName($instanceName);
    }
}