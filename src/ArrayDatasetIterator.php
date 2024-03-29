<?php

namespace ByJG\AnyDataset\Lists;

use ByJG\AnyDataset\Core\GenericIterator;
use ByJG\AnyDataset\Core\IteratorFilter;
use ByJG\AnyDataset\Core\Row;
use InvalidArgumentException;

class ArrayDatasetIterator extends GenericIterator
{

    /**
     * @var array
     */
    protected $rows;

    /**
     * Enter description here...
     *
     * @var array
     */
    protected $keys;

    /**
      /* @var int
     */
    protected $index;

    /**
     * @var Row
     */
    protected $currentRow;

    /**
     * @var IteratorFilter
     */
    protected $filter;
    /**
     * @var mixed|string
     */
    protected $propertyIndexName;
    /**
     * @var mixed|string
     */
    protected $propertyKeyName;

    /**
     * @param array $rows
     * @param IteratorFilter $filter
     */
    public function __construct($rows, $filter, $propertyIndexName = "__id", $propertyKeyName = "__key")
    {
        if (!is_array($rows)) {
            throw new InvalidArgumentException("ArrayDatasetIterator must receive an array");
        }
        $this->index = 0;
        $this->currentRow = null;
        $this->rows = $rows;
        $this->keys = array_keys($rows);
        $this->filter = $filter;

        $this->propertyIndexName = $propertyIndexName;
        $this->propertyKeyName = $propertyKeyName;

    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->rows);
    }

    /**
     * @return bool
     * @throws \ByJG\Serializer\Exception\InvalidArgumentException
     */
    public function hasNext()
    {
        if (!empty($this->currentRow)) {
            return true;
        }

        $ix = $this->index++;

        if ($ix >= count($this->rows)) {
            return false;
        }

        $key = $this->keys[$ix];
        $cols = $this->rows[$key];

        $arr = [];
        if (!empty($this->propertyIndexName)) {
            $arr[$this->propertyIndexName] = $ix;
        }
        if (!empty($this->propertyKeyName)) {
            $arr[$this->propertyKeyName] = $key;
        }
        foreach ($cols as $key => $value) {
            $arr[strtolower($key)] = $value;
        }

        $row = new Row($arr);

        if (empty($this->filter) || $this->filter->match([$row])) {
            $this->currentRow = $row;
            return true;
        }

        return $this->hasNext();
    }

    /**
     * @return Row
     * @throws \ByJG\Serializer\Exception\InvalidArgumentException
     */
    public function moveNext()
    {
        if (!$this->hasNext()) {
            return null;
        }

        $row = $this->currentRow;
        $this->currentRow = null;

        return $row;
    }

    public function key()
    {
        return $this->index;
    }
}
