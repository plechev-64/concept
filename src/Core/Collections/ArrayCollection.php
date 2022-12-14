<?php

namespace USP\Core\Collections;

use Closure;
use ArrayIterator;
use USP\Core\Collections\Expr\ClosureExpressionVisitor;

class ArrayCollection implements Collection{

	/**
	 * An array containing the entries of this collection.
	 *
	 * @var array
	 */
	private array $elements;

	/**
	 * Initializes a new ArrayCollection.
	 *
	 * @param array $elements
	 */
	public function __construct(array $elements = array())
	{
		$this->elements = $elements;
	}

	/**
	 * {@inheritDoc}
	 */
	public function toArray()
	{
		return $this->elements;
	}

	/**
	 * {@inheritDoc}
	 */
	public function first()
	{
		return reset($this->elements);
	}

	/**
	 * {@inheritDoc}
	 */
	public function last()
	{
		return end($this->elements);
	}

	/**
	 * {@inheritDoc}
	 */
	public function key()
	{
		return key($this->elements);
	}

	/**
	 * {@inheritDoc}
	 */
	public function next()
	{
		return next($this->elements);
	}

	/**
	 * {@inheritDoc}
	 */
	public function current()
	{
		return current($this->elements);
	}

	/**
	 * {@inheritDoc}
	 */
	public function remove($key)
	{
		if ( ! isset($this->elements[$key]) && ! array_key_exists($key, $this->elements)) {
			return null;
		}

		$removed = $this->elements[$key];
		unset($this->elements[$key]);

		return $removed;
	}

	/**
	 * {@inheritDoc}
	 */
	public function removeElement($element)
	{
		$key = array_search($element, $this->elements, true);

		if ($key === false) {
			return false;
		}

		unset($this->elements[$key]);

		return true;
	}

	/**
	 * Required by interface ArrayAccess.
	 *
	 * {@inheritDoc}
	 */
	public function offsetExists($offset)
	{
		return $this->containsKey($offset);
	}

	/**
	 * Required by interface ArrayAccess.
	 *
	 * {@inheritDoc}
	 */
	public function offsetGet($offset)
	{
		return $this->get($offset);
	}

	/**
	 * Required by interface ArrayAccess.
	 *
	 * {@inheritDoc}
	 */
	public function offsetSet($offset, $value)
	{
		if ( ! isset($offset)) {
			return $this->add($value);
		}

		$this->set($offset, $value);
	}

	/**
	 * Required by interface ArrayAccess.
	 *
	 * {@inheritDoc}
	 */
	public function offsetUnset($offset)
	{
		return $this->remove($offset);
	}

	/**
	 * {@inheritDoc}
	 */
	public function containsKey($key)
	{
		return isset($this->elements[$key]) || array_key_exists($key, $this->elements);
	}

	/**
	 * {@inheritDoc}
	 */
	public function contains($element)
	{
		return in_array($element, $this->elements, true);
	}

	/**
	 * {@inheritDoc}
	 */
	public function exists(Closure $p)
	{
		foreach ($this->elements as $key => $element) {
			if ($p($key, $element)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function indexOf($element)
	{
		return array_search($element, $this->elements, true);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get($key)
	{
		return $this->elements[ $key ] ?? null;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getKeys()
	{
		return array_keys($this->elements);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getValues()
	{
		return array_values($this->elements);
	}

	/**
	 * {@inheritDoc}
	 */
	public function count()
	{
		return count($this->elements);
	}

	/**
	 * {@inheritDoc}
	 */
	public function set($key, $value)
	{
		$this->elements[$key] = $value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function add($value)
	{
		$this->elements[] = $value;

		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isEmpty()
	{
		return empty($this->elements);
	}

	/**
	 * Required by interface IteratorAggregate.
	 *
	 * {@inheritDoc}
	 */
	public function getIterator()
	{
		return new ArrayIterator($this->elements);
	}

	/**
	 * {@inheritDoc}
	 */
	public function map(Closure $func)
	{
		return new static(array_map($func, $this->elements));
	}

	/**
	 * {@inheritDoc}
	 */
	public function filter(Closure $p)
	{
		return new static(array_filter($this->elements, $p));
	}

	/**
	 * {@inheritDoc}
	 */
	public function forAll(Closure $p)
	{
		foreach ($this->elements as $key => $element) {
			if ( ! $p($key, $element)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function partition(Closure $p)
	{
		$matches = $noMatches = array();

		foreach ($this->elements as $key => $element) {
			if ($p($key, $element)) {
				$matches[$key] = $element;
			} else {
				$noMatches[$key] = $element;
			}
		}

		return array(new static($matches), new static($noMatches));
	}

	/**
	 * Returns a string representation of this object.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return __CLASS__ . '@' . spl_object_hash($this);
	}

	/**
	 * {@inheritDoc}
	 */
	public function clear()
	{
		$this->elements = array();
	}

	/**
	 * {@inheritDoc}
	 */
	public function slice($offset, $length = null)
	{
		return array_slice($this->elements, $offset, $length, true);
	}

	/**
	 * {@inheritDoc}
	 */
	public function matching(Criteria $criteria)
	{
		$expr     = $criteria->getWhereExpression();
		$filtered = $this->elements;

		if ($expr) {
			$visitor  = new ClosureExpressionVisitor();
			$filter   = $visitor->dispatch($expr);
			$filtered = array_filter($filtered, $filter);
		}

		if ($orderings = $criteria->getOrderings()) {
			foreach (array_reverse($orderings) as $field => $ordering) {
				$next = ClosureExpressionVisitor::sortByField($field, $ordering == Criteria::DESC ? -1 : 1);
			}

			uasort($filtered, $next);
		}

		$offset = $criteria->getFirstResult();
		$length = $criteria->getMaxResults();

		if ($offset || $length) {
			$filtered = array_slice($filtered, (int)$offset, $length);
		}

		return new static($filtered);
	}

}