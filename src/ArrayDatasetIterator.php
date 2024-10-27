<?php

namespace ByJG\AnyDataset\Lists;

use ByJG\AnyDataset\Core\GenericIterator;
use ByJG\AnyDataset\Core\IteratorFilter;
use ByJG\AnyDataset\Core\Row;

class ArrayDatasetIterator extends GenericIterator
{

    /**
     * @var array
     */
    protected array $rows;

    /**
     * Enter description here...
     *
     * @var array
     */
    protected array $keys;

    /**
      /* @var int
     */
    protected int $index;

    /**
     * @var Row
     */
    protected ?Row $currentRow;

    /**
     * @var IteratorFilter|null
     */
    protected ?IteratorFilter $filter;
    /**
     * @var string|null
     */
    protected ?string $propertyIndexName;
    /**
     * @var string|null
     */
    protected ?string $propertyKeyName;

    /**
     * @param array $rows
     * @param IteratorFilter|null $filter
     * @param string|null $propertyIndexName
     * @param string|null $propertyKeyName
     */
    public function __construct(array $rows, ?IteratorFilter $filter, ?string $propertyIndexName = "__id", ?string $propertyKeyName = "__key")
    {
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
    public function count(): int
    {
        return count($this->rows);
    }

    /**
     * @return bool
     * @throws \ByJG\Serializer\Exception\InvalidArgumentException
     */
    public function hasNext(): bool
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
     * @return Row|null
     * @throws \ByJG\Serializer\Exception\InvalidArgumentException
     */
    public function moveNext(): ?Row
    {
        if (!$this->hasNext()) {
            return null;
        }

        $row = $this->currentRow;
        $this->currentRow = null;

        return $row;
    }

    public function key(): int
    {
        return $this->index;
    }
}
