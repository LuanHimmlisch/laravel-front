<?php

namespace WeblaborMx\Front\Traits;

trait InputRelationship
{
	use HasMassiveEditions;

	public $create_link;
	public $edit_link = '{key}/edit';
	public $show_link;
	public $with = [];
	public $filter_query;
	public $filter_collection;
	public $force_query;
	public $lense;
	public $hide_columns;
	public $block_edition = false;
	public $headings;

	/*
	 * Functions 
	 */

	public function setCreateLink($function)
	{
		if(!$this->showOnHere()) {
			return $this;
		}
		$this->create_link_accessed = true;
		$this->create_link = $function($this->create_link);
		$this->massive_edit_link = $function($this->massive_edit_link);
		return $this;
	}

	public function setEditLink($function)
	{
		if(!$this->showOnHere()) {
			return $this;
		}
		$this->edit_link_accessed = true;
		$this->edit_link = $function($this->edit_link);
		return $this;
	}

	public function setShowLink($function)
	{
		if(!$this->showOnHere()) {
			return $this;
		}
		$this->show_link_accessed = true;
		$this->show_link = $function($this->show_link);
		return $this;
	}

	public function with($with)
	{
		$this->with = $with;
		return $this;
	}

	public function hideCreateButton()
	{
		$this->create_link = null;
		return $this;
	}

	public function setRequest($request)
	{
		if(!$this->showOnHere()) {
			return $this;
		}
		request()->request->add($request);
		return $this;
	}

	// Filter on the query

	public function filterQuery($query)
	{
		$this->filter_query = $query;
		return $this;
	}

	// Filter on the collection, after the get() 

	public function filterCollection($query)
	{
		$this->filter_collection = $query;
		return $this;
	}

	// Same that filterQuery but now dont access to the globalIndexQuery

	public function forceQuery($query)
	{
		$this->force_query = $query;
		return $this;
	}

	public function setLense($lense)
	{
		$this->lense = $lense;
		return $this;
	}

	public function hideColumns($hide_columns)
	{
		$this->hide_columns = $hide_columns;
		return $this;
	}

	public function setMassiveClass($class)
	{
		if(!is_null($class)) {
			$class = new $class;
		}
		$this->massive_class = $class;
		return $this;
	}

	public function enableMassive($value = true)
	{
		$this->show_massive = $value;
		return $this;
	}

	public function blockEdition($block_edition = true)
	{
		$this->block_edition = $block_edition;
		return $this;
	}

	public function getFront()
	{
		return $this->front;
	}
}	
