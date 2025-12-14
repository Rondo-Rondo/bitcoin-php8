<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Transaction\Mutator;

/**
 * PHP 8.4 compatibility fix:
 * SplFixedArray no longer implements Iterator, it now implements IteratorAggregate.
 * We must track position ourselves instead of delegating to SplFixedArray.
 */
abstract class AbstractCollectionMutator implements \Iterator, \ArrayAccess, \Countable
{
    /**
     * @var \SplFixedArray
     */
    protected \SplFixedArray $set;

    /**
     * @var int
     */
    protected int $position = 0;

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->set->toArray();
    }

    /**
     * @return bool
     */
    public function isNull(): bool
    {
        return count($this->set) === 0;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->set->count();
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->set[$this->position];
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @return void
     */
    public function next(): void
    {
        ++$this->position;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return $this->set->offsetExists($this->position);
    }

    /**
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->set->offsetExists($offset);
    }

    /**
     * @param int $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        if (!$this->offsetExists($offset)) {
            throw new \InvalidArgumentException('Offset does not exist');
        }

        $this->set->offsetUnset($offset);
    }

    /**
     * @param int $offset
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        if (!$this->set->offsetExists($offset)) {
            throw new \OutOfRangeException('Nothing found at this offset');
        }
        return $this->set->offsetGet($offset);
    }

    /**
     * @param int $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->set->offsetSet($offset, $value);
    }
}
