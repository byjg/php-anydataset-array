<?php

namespace Tests\AnyDataset\Lists;

use ByJG\AnyDataset\Core\Enum\Relation;
use ByJG\AnyDataset\Core\IteratorFilter;
use ByJG\AnyDataset\Core\IteratorInterface;
use ByJG\AnyDataset\Core\Row;
use ByJG\AnyDataset\Lists\ArrayDataset;
use PHPUnit\Framework\TestCase;
use Tests\AnyDataset\Sample\ModelGetter;
use Tests\AnyDataset\Sample\ModelPublic;
use UnexpectedValueException;

require_once "Sample/ModelGetter.php";
require_once "Sample/ModelPublic.php";

class ArrayDatasetTest extends TestCase
{

    protected $fieldNames;
    protected $SAMPLE1 = array("ProdA", "ProdB", "ProdC");
    protected $SAMPLE2 = array("A" => "ProdA", "B" => "ProdB", "C" => "ProdC");
    protected $SAMPLE3 = array("A" => array('code' => 1000, 'name' => "ProdA"),
        "B" => array('code' => 1001, 'name' => "ProdB"),
        "C" => array('code' => 1002, 'name' => "ProdC"));

    public function testcreateArrayIteratorSample1()
    {
        $arrayDataset = new ArrayDataset($this->SAMPLE1);
        $arrayIterator = $arrayDataset->getIterator();


        $this->assertTrue($arrayIterator instanceof IteratorInterface); //, "Resultant object must be an interator");
        $this->assertTrue($arrayIterator->hasNext()); //, "hasNext() method must be true");
        $this->assertEquals($arrayIterator->Count(), 3); //, "Count() method must return 3");
    }

    public function testcreateArrayIteratorSample2()
    {
        $arrayDataset = new ArrayDataset($this->SAMPLE2);
        $arrayIterator = $arrayDataset->getIterator();

        $this->assertTrue($arrayIterator instanceof IteratorInterface); // "Resultant object must be an interator");
        $this->assertTrue($arrayIterator->hasNext()); // "hasNext() method must be true");
        $this->assertEquals($arrayIterator->Count(), 3); //, "Count() method must return 3");
    }

    public function testcreateArrayIteratorSample3()
    {
        $arrayDataset = new ArrayDataset($this->SAMPLE3);
        $arrayIterator = $arrayDataset->getIterator();

        $this->assertTrue($arrayIterator instanceof IteratorInterface); // "Resultant object must be an interator");
        $this->assertTrue($arrayIterator->hasNext()); // "hasNext() method must be true");
        $this->assertEquals($arrayIterator->Count(), 3); //, "Count() method must return 3");
    }

    public function testnavigateArrayIteratorSample1()
    {
        $arrayDataset = new ArrayDataset($this->SAMPLE1);
        $arrayIterator = $arrayDataset->getIterator();

        $count = 0;

        if ($arrayIterator->hasNext()) {
            $sr = $arrayIterator->moveNext();
            $this->assertField($sr, "__id", 0);
            $this->assertField($sr, "__key", 0);
            $this->assertField($sr, "value", 'ProdA');
            $count++;
        }
        if ($arrayIterator->hasNext()) {
            $sr = $arrayIterator->moveNext();
            $this->assertField($sr, "__id", 1);
            $this->assertField($sr, "__key", 1);
            $this->assertField($sr, "value", 'ProdB');
            $count++;
        }
        if ($arrayIterator->hasNext()) {
            $sr = $arrayIterator->moveNext();
            $this->assertField($sr, "__id", 2);
            $this->assertField($sr, "__key", 2);
            $this->assertField($sr, "value", 'ProdC');
            $count++;
        }
        $this->assertTrue(!$arrayIterator->hasNext()); //, 'I did not expected more records');
        $this->assertEquals($count, 3); //, "Count records mismatch. Need to process 3 records.");
    }

    public function testnavigateArrayIteratorSample2()
    {
        $arrayDataset = new ArrayDataset($this->SAMPLE2);
        $arrayIterator = $arrayDataset->getIterator();

        $count = 0;

        if ($arrayIterator->hasNext()) {
            $sr = $arrayIterator->moveNext();
            $this->assertField($sr, "__id", 0);
            $this->assertField($sr, "__key", 'A');
            $this->assertField($sr, "value", 'ProdA');
            $count++;
        }
        if ($arrayIterator->hasNext()) {
            $sr = $arrayIterator->moveNext();
            $this->assertField($sr, "__id", 1);
            $this->assertField($sr, "__key", 'B');
            $this->assertField($sr, "value", 'ProdB');
            $count++;
        }
        if ($arrayIterator->hasNext()) {
            $sr = $arrayIterator->moveNext();
            $this->assertField($sr, "__id", 2);
            $this->assertField($sr, "__key", 'C');
            $this->assertField($sr, "value", 'ProdC');
            $count++;
        }
        $this->assertTrue(!$arrayIterator->hasNext()); //, 'I did not expected more records');
        $this->assertEquals($count, 3); //, "Count records mismatch. Need to process 3 records.");
    }

    public function testnavigateArrayIteratorSample3()
    {
        $arrayDataset = new ArrayDataset($this->SAMPLE3);
        $arrayIterator = $arrayDataset->getIterator();

        $count = 0;

        if ($arrayIterator->hasNext()) {
            $sr = $arrayIterator->moveNext();
            $this->assertField($sr, "__id", 0);
            $this->assertField($sr, "__key", 'A');
            $this->assertField($sr, "code", 1000);
            $this->assertField($sr, "name", 'ProdA');
            $count++;
        }
        if ($arrayIterator->hasNext()) {
            $sr = $arrayIterator->moveNext();
            $this->assertField($sr, "__id", 1);
            $this->assertField($sr, "__key", 'B');
            $this->assertField($sr, "code", 1001);
            $this->assertField($sr, "name", 'ProdB');
            $count++;
        }
        if ($arrayIterator->hasNext()) {
            $sr = $arrayIterator->moveNext();
            $this->assertField($sr, "__id", 2);
            $this->assertField($sr, "__key", 'C');
            $this->assertField($sr, "code", 1002);
            $this->assertField($sr, "name", 'ProdC');
            $count++;
        }
        $this->assertTrue(!$arrayIterator->hasNext()); //, 'I did not expected more records');
        $this->assertEquals($count, 3); //, "Count records mismatch. Need to process 3 records.");
    }

    public function testcreateFromModel1()
    {
        $model = array(
            new ModelPublic(1, 'ProdA'),
            new ModelPublic(2, 'ProdB'),
            new ModelPublic(3, 'ProdC')
        );

        $arrayDataset = new ArrayDataset($model);
        $arrayIterator = $arrayDataset->getIterator();


        $this->assertTrue($arrayIterator instanceof IteratorInterface); //, "Resultant object must be an interator");
        $this->assertTrue($arrayIterator->hasNext()); //, "hasNext() method must be true");
        $this->assertEquals($arrayIterator->Count(), 3); //, "Count() method must return 3");
    }

    public function testnavigateFromModel1()
    {
        $model = array(
            new ModelPublic(1, 'ProdA'),
            new ModelPublic(2, 'ProdB'),
            new ModelPublic(3, 'ProdC')
        );

        $arrayDataset = new ArrayDataset($model);
        $arrayIterator = $arrayDataset->getIterator();

        $count = 0;

        if ($arrayIterator->hasNext()) {
            $sr = $arrayIterator->moveNext();
            $this->assertField($sr, "__id", 0);
            $this->assertField($sr, "__key", 0);
            $this->assertField($sr, "__class", "Tests\\AnyDataset\\Sample\\ModelPublic");
            $this->assertField($sr, "id", 1);
            $this->assertField($sr, "name", 'ProdA');
            $count++;
        }
        if ($arrayIterator->hasNext()) {
            $sr = $arrayIterator->moveNext();
            $this->assertField($sr, "__id", 1);
            $this->assertField($sr, "__key", 1);
            $this->assertField($sr, "__class", "Tests\\AnyDataset\\Sample\\ModelPublic");
            $this->assertField($sr, "id", 2);
            $this->assertField($sr, "name", 'ProdB');
            $count++;
        }
        if ($arrayIterator->hasNext()) {
            $sr = $arrayIterator->moveNext();
            $this->assertField($sr, "__id", 2);
            $this->assertField($sr, "__key", 2);
            $this->assertField($sr, "__class", "Tests\\AnyDataset\\Sample\\ModelPublic");
            $this->assertField($sr, "id", 3);
            $this->assertField($sr, "name", 'ProdC');
            $count++;
        }
        $this->assertTrue(!$arrayIterator->hasNext()); //, 'I did not expected more records');
        $this->assertEquals($count, 3); //, "Count records mismatch. Need to process 3 records.");
    }

    public function testnavigateFilterFromModel1()
    {
        $model = array(
            new ModelPublic(1, 'ProdA'),
            new ModelPublic(2, 'ProdB'),
            new ModelPublic(3, 'ProdC')
        );

        $arrayDataset = new ArrayDataset($model);
        $filter = new IteratorFilter();
        $filter->addRelation("name", Relation::EQUAL, "ProdB");
        $arrayIterator = $arrayDataset->getIterator($filter);

        $count = 0;

        if ($arrayIterator->hasNext()) {
            $sr = $arrayIterator->moveNext();
            $this->assertField($sr, "__id", 1);
            $this->assertField($sr, "__key", 1);
            $this->assertField($sr, "__class", "Tests\\AnyDataset\\Sample\\ModelPublic");
            $this->assertField($sr, "id", 2);
            $this->assertField($sr, "name", 'ProdB');
            $count++;
        }
        $this->assertFalse($arrayIterator->hasNext()); //, 'I did not expected more records');
        $this->assertEquals($count, 1); //, "Count records mismatch. Need to process 3 records.");
    }

    public function testcreateFromModel2()
    {
        $model = array(
            new ModelGetter(1, 'ProdA'),
            new ModelGetter(2, 'ProdB'),
            new ModelGetter(3, 'ProdC')
        );

        $arrayDataset = new ArrayDataset($model);
        $arrayIterator = $arrayDataset->getIterator();


        $this->assertTrue($arrayIterator instanceof IteratorInterface); //, "Resultant object must be an interator");
        $this->assertTrue($arrayIterator->hasNext()); //, "hasNext() method must be true");
        $this->assertEquals($arrayIterator->Count(), 3); //, "Count() method must return 3");
    }

    public function testnavigateFromModel2()
    {
        $model = array(
            "A" => new ModelGetter(1, 'ProdA'),
            "B" => new ModelGetter(2, 'ProdB'),
            "C" => new ModelGetter(3, 'ProdC')
        );

        $arrayDataset = new ArrayDataset($model);
        $arrayIterator = $arrayDataset->getIterator();

        $count = 0;

        if ($arrayIterator->hasNext()) {
            $sr = $arrayIterator->moveNext();
            $this->assertField($sr, "__id", 0);
            $this->assertField($sr, "__key", "A");
            $this->assertField($sr, "__class", ModelGetter::class);
            $this->assertField($sr, "id", 1);
            $this->assertField($sr, "name", 'ProdA');
            $count++;
        }
        if ($arrayIterator->hasNext()) {
            $sr = $arrayIterator->moveNext();
            $this->assertField($sr, "__id", 1);
            $this->assertField($sr, "__key", "B");
            $this->assertField($sr, "__class", ModelGetter::class);
            $this->assertField($sr, "id", 2);
            $this->assertField($sr, "name", 'ProdB');
            $count++;
        }
        if ($arrayIterator->hasNext()) {
            $sr = $arrayIterator->moveNext();
            $this->assertField($sr, "__id", 2);
            $this->assertField($sr, "__key", "C");
            $this->assertField($sr, "__class", ModelGetter::class);
            $this->assertField($sr, "id", 3);
            $this->assertField($sr, "name", 'ProdC');
            $count++;
        }
        $this->assertTrue(!$arrayIterator->hasNext()); //, 'I did not expected more records');
        $this->assertEquals($count, 3); //, "Count records mismatch. Need to process 3 records.");
    }

    public function testnavigateFilterFromModel2()
    {
        $model = array(
            "A" => new ModelGetter(1, 'ProdA'),
            "B" => new ModelGetter(2, 'ProdB'),
            "C" => new ModelGetter(3, 'ProdC')
        );

        $arrayDataset = new ArrayDataset($model);
        $filter = new IteratorFilter();
        $filter->addRelation("name", Relation::EQUAL, "ProdB");
        $arrayIterator = $arrayDataset->getIterator($filter);

        $count = 0;

        if ($arrayIterator->hasNext()) {
            $sr = $arrayIterator->moveNext();
            $this->assertField($sr, "__id", 1);
            $this->assertField($sr, "__key", "B");
            $this->assertField($sr, "__class", ModelGetter::class);
            $this->assertField($sr, "id", 2);
            $this->assertField($sr, "name", 'ProdB');
            $count++;
        }
        $this->assertFalse($arrayIterator->hasNext()); //, 'I did not expected more records');
        $this->assertEquals($count, 1); //, "Count records mismatch. Need to process 3 records.");
    }

    /**
     * @param Row $row
     * @param $field
     * @param $value
     */
    public function assertField($row, $field, $value)
    {
        $this->assertEquals($value, $row->get($field));
    }

    public function testEmptyArray()
    {
        $dataset = new ArrayDataset([]);

        $iterator = $dataset->getIterator();

        $this->assertEquals(0, $iterator->count());
    }

    public function testPropertyKeyName()
    {
        $dataset = new ArrayDataset($this->SAMPLE1, "value", "id", "name");

        $iterator = $dataset->getIterator();

        $row = $iterator->moveNext();
        $this->assertField($row, "id", 0);
        $this->assertField($row, "name", 0);
        $this->assertField($row, "value", "ProdA");

    }

    public function testPropertyKeyNameEmpty()
    {
        $dataset = new ArrayDataset($this->SAMPLE1, "value", null, null);

        $iterator = $dataset->getIterator();

        $row = $iterator->moveNext();
        $this->assertEquals(["value" => 'ProdA'], $row->toArray());
    }
}

