<?php
/**
 * Created by PhpStorm.
 * User: ixapek
 * Date: 01.04.2019
 * Time: 22:38
 */

require_once dirname(__DIR__).'/src/InvalidTypeException.php';
require_once dirname(__DIR__).'/src/ITypedCollection.php';
require_once dirname(__DIR__).'/src/TypedCollectionTrait.php';
require_once dirname(__DIR__).'/src/UnsupportedTypeException.php';

require_once 'TypedCollection.php';
require_once 'CollectionFactory.php';
require_once 'IntCollection.php';
require_once 'NullCollection.php';
require_once 'ObjectCollection.php';

use ixapek\TypedCollection\ITypedCollection;
use ixapek\TypedCollection\CollectionFactory;

$intCollection = CollectionFactory::init(ITypedCollection::COLLECTION_TYPE_INT);
$stdClassCollection = CollectionFactory::init(ITypedCollection::COLLECTION_TYPE_OBJECT, stdClass::class);

$arrayOfInt = [1,5,234,521,2];
$intCollectionFromArray = CollectionFactory::initFrom($arrayOfInt);

$stdObject = new stdClass();
$stdClassCollectionFromObject = CollectionFactory::initFor($stdObject);

$errorCollection = CollectionFactory::init(ITypedCollection::COLLECTION_TYPE_INT, stdClass::class);
$errorCollection->offsetSet(0, '10');

var_dump(
    $errorCollection
    );