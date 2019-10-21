<?php

namespace WeblaborMx\Front\Inputs;

class Check extends Input
{
	public function form()
	{
		return \Form::checkbox($this->column, $this->default_value);
	}

	public function getValue($object)
	{
		$column = $this->column;
		if(!is_string($column) && is_callable($column)) {
			$return = $column($object);
		} else {
			$return = $object->$column;	
		}
		if($return) {
			return '<i class="fa fa-check"></i>';
		}
        return '<i class="far fa-times-circle"></i>';
	}
}
