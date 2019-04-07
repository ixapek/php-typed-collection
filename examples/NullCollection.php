<?php
/**
 * Created by PhpStorm.
 * User: ixapek
 * Date: 06.04.2019
 * Time: 0:01
 */

namespace ixapek\TypedCollection;


class NullCollection implements ITypedCollection
{
    use TypedCollectionTrait;

    public function __construct()
    {
        $this->setType();
    }

    /**
     * @param string $type
     * @return NullCollection
     */
    public function setType(): NullCollection
    {
        $this->type = ITypedCollection::COLLECTION_TYPE_NULL;
        return $this;
    }
}