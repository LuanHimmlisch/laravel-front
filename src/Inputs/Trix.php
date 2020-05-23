<?php

namespace WeblaborMx\Front\Inputs;

class Trix extends Input
{
	public $show_on_index = false;
	public $variables = [
		'width' => 1000,
		'height' => 1000,
		'directory' => 'trix'
	];
	
	public function form()
	{
		$this->attributes['data-type'] = 'wysiwyg';
		$this->attributes['data-lang'] = \App::getLocale();
		$this->attributes['data-upload-url'] = url('laravel-front/upload-image').'?variables='.json_encode($this->variables);
		return \Form::textarea($this->column, $this->default_value, $this->attributes);
	}

	public function originalSize($width, $height)
	{
		$this->variables['width'] = $width;
		$this->variables['height'] = $height;
		return $this;
	}

	public function setDirectory($directory)
	{
		$this->variables['directory'] = $directory;
		return $this;
	}
}
