<?php
/**
 * Created by PhpStorm.
 * User: ixapek
 * Date: 06.04.2019
 * Time: 0:11
 */

namespace ixapek\TypedCollection;


class IntCollection extends TypedCollection
{
    /**
     * IntCollection constructor.
     * @throws UnsupportedTypeException
     */
    public function __construct()
    {
        $this->setType(ITypedCollection::COLLECTION_TYPE_INT);
    }
}