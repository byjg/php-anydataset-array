<?php

namespace ByJG\AnyDataset\Lists;

use ByJG\AnyDataset\Core\GenericIterator;
use ByJG\AnyDataset\Core\IteratorFilter;
use ByJG\AnyDataset\Core\Row;

class ArrayDataset
{

    /**
     * @var array
     */
    protected array $array;
    /**
     * @var string|null
     */
    protected ?string $propertyIndexName;
    /**
     * @var string|null
     */
    protected ?string $propertyKeyName;

    /**
     * Constructor Method
     *
     * @param array $array
     * @param string $property The name of the field if the item is not an array or object
     */
    public function __construct(array $array, string $property = "value", ?string $propertyIndexName = "__id", ?string $propertyKeyName = "__key")
    {
        $this->propertyIndexName = $propertyIndexName;
        $this->propertyKeyName = $propertyKeyName;

        $this->array = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->array[$key] = $value;
            } elseif (!is_object($value)) {
                $this->array[$key] = array($property => $value);
            } else {
                $result = array("__class" => get_class($value));
                $methods = get_class_methods($value);
                foreach ($methods as $method) {
                    if (str_starts_with($method, "get")) {
                        $result[substr($method, 3)] = $value->{$method}();
                    }
                }
                $this->array[$key] = $result;
                $props = get_object_vars($value);
                $this->array[$key] += $props;
            }
        }
    }

    /**
     * Return a GenericIterator
     *
     * @param IteratorFilter|null $filter
     * @return GenericIterator
     */
    public function getIterator(IteratorFilter $filter = null): GenericIterator
    {
        return new ArrayDatasetIterator($this->array, $filter, $this->propertyIndexName, $this->propertyKeyName);
    }

    /**
     *
     * @param string $field
     * @return void
     */
    public function sort(string $field): void
    {
        if (count($this->array) == 0) {
            return;
        }

        $this->array = $this->quickSortExec($this->array, $field);
    }

    /**
     * @param Row[] $seq
     * @param string $field
     * @return array
     */
    protected function quickSortExec(array $seq, string $field): array
    {
        if (!count($seq)) {
            return $seq;
        }

        $key = $seq[0];
        $left = $right = array();

        $cntSeq = count($seq);
        for ($i = 1; $i < $cntSeq; $i ++) {
            if (($seq[$i][$field] ?? null) <= ($key[$field] ?? null)) {
                $left[] = $seq[$i];
            } else {
                $right[] = $seq[$i];
            }
        }

        return array_merge(
            $this->quickSortExec($left, $field),
            [ $key ],
            $this->quickSortExec($right, $field)
        );
    }

}
