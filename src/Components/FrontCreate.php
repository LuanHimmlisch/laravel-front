<?php

namespace WeblaborMx\Front\Components;

use WeblaborMx\Front\Front;

class FrontCreate extends Component
{
	public $front_class;
	public $query;
	public $lense;

	public function __construct($front_class, $column = null, $extra = null, $source = null)
	{
		$front_class = '\App\Front\\'.$front_class;
		$this->source = $source;
		$this->front_class = new $front_class($this->source);
		$this->show_before = \Auth::check() ? \Auth::user()->can('create', $this->front_class->getModel()) : true;
	}

	public function form()
	{
		$front = $this->front_class;
		if(isset($this->lense)) {
			$front = $front->getLense($this->lense);
		}
		return view('front::crud.partial-create', compact('front'))->render();
	}

	public function setRequest($request)
	{
		request()->request->add($request);
		return $this;
	}

	public function query($query)
	{
		$this->query = $query;
		return $this;
	}

	public function setLense($lense)
	{
		$this->lense = $lense;
		return $this;
	}
}
