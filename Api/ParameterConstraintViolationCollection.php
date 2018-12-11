<?php

namespace Chaplean\Bundle\ApiClientBundle\Api;

use Chaplean\Bundle\ApiClientBundle\Exception\UnexpectedTypeException;

/**
 * Class ParameterConstraintViolationCollection.
 *
 * @package   Chaplean\Bundle\ApiClientBundle\API
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2018 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class ParameterConstraintViolationCollection implements \Iterator
{
    /**
     * @var array
     */
    protected $violations = [];

    /**
     * @var array
     */
    protected $children = [];

    /**
     * @var integer
     */
    protected $violationIndex;

    /**
     * @var \ArrayIterator
     */
    protected $childIndex;

    /**
     * @var boolean
     */
    protected $isIteratingOverChildren;

    /**
     * ParameterConstraintViolationCollection constructor.
     */
    public function __construct()
    {
        $this->rewind();
    }

    /**
     * Add a new ParameterConstraintViolation in the iterator
     * If $violation is a string, it's automatically wrapped in a ParameterConstraintViolation
     *
     * @param ParameterConstraintViolation|string $violation
     *
     * @return self
     */
    public function add($violation)
    {
        if (is_string($violation)) {
            $violation = new ParameterConstraintViolation($violation);
        }

        if (!$violation instanceof ParameterConstraintViolation) {
            throw new UnexpectedTypeException($violation, ParameterConstraintViolation::class . ' or a string');
        }

        $this->violations[] = $violation;

        return $this;
    }

    /**
     * @param string                                 $name
     * @param ParameterConstraintViolationCollection $violations
     *
     * @return self
     */
    public function addChild($name, ParameterConstraintViolationCollection $violations)
    {
        $existingViolations = $this->children[$name] ?? new ParameterConstraintViolationCollection();
        $existingViolations->merge($violations);

        if (!$existingViolations->isEmpty()) {
            $this->children[$name] = $existingViolations;
        }

        return $this;
    }

    /**
     * @param ParameterConstraintViolationCollection $violations
     *
     * @return self
     */
    public function merge(ParameterConstraintViolationCollection $violations)
    {
        foreach ($violations->violations as $violation) {
            $this->add($violation);
        }

        foreach ($violations->children as $name => $child) {
            if (!$child->isEmpty()) {
                $this->addChild($name, $child);
            }
        }

        return $this;
    }

    /**
     * Return wether or not this iterator is empty
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->violations) && empty($this->children);
    }

    /**
     * Returns the current key
     *
     * @return string
     */
    public function key()
    {
        if ($this->isIteratingOverChildren) {
            $child = $this->childIndex->current();
            $childKey = $child !== null ? '.' . $child->key() : '';

            return $this->childIndex->key() . $childKey;
        } else {
            return (string) $this->violationIndex;
        }
    }

    /**
     * Return the current element
     *
     * @return ParameterConstraintViolation|null
     */
    public function current()
    {
        if (!$this->valid()) {
            return null;
        }

        if ($this->isIteratingOverChildren) {
            return $this->childIndex->current()
                ->current();
        } else {
            return $this->violations[$this->violationIndex];
        }
    }

    /**
     * Go to the next element and return it
     *
     * @return ParameterConstraintViolation|null
     */
    public function next()
    {
        if ($this->isIteratingOverChildren) {
            $current = $this->childIndex->current();
            if ($current !== null) {
                if ($current->next() === null) {
                    $this->childIndex->next();
                };
            } else {
                $this->childIndex->next();
            }
        } else {
            $this->violationIndex++;

            if (!$this->valid()) {
                $this->isIteratingOverChildren = true;
                $this->childIndex = new \ArrayIterator($this->children);
            }
        }

        return $this->current();
    }

    /**
     * Wether or not the current element is valid (exists) in the iterator
     *
     * @return boolean
     */
    public function valid()
    {
        if ($this->isIteratingOverChildren) {
            return $this->childIndex->valid();
        } else {
            $isValid = $this->violationIndex < count($this->violations);

            if (!$isValid) {
                $this->isIteratingOverChildren = true;
                $this->childIndex = new \ArrayIterator($this->children);

                return $this->valid();
            } else {
                return true;
            }
        }
    }

    /**
     * Reset the iterator to the first element
     *
     * @return self
     */
    public function rewind()
    {
        foreach ($this->children as $child) {
            $child->rewind();
        }

        $this->violationIndex = 0;
        $this->childIndex = null;
        $this->isIteratingOverChildren = false;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode(iterator_to_array($this));
    }
}
