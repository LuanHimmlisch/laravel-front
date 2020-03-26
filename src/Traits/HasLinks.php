<?php

namespace WeblaborMx\Front\Traits;

use WeblaborMx\Front\Texts\Button;

trait HasLinks
{
	public function index_links()
    {
        return [];
    }

    public function links()
    {
        return [];
    }

    public function getLinks($object)
    {
        $links = [];

        // Show index actions
        foreach($this->getActions() as $action) {
            $link[] = Button::make($action->button_text)->addLink($this->base_url."/{$object->getKey()}/action/{$action->slug}");
        }

        // Show links added manually
        foreach($this->links() as $link => $text) {
            $links[] = Button::make($text)->addLink($link);
        }

        // Add update button
        if( \Auth::user()->can('update', $object) ) {
            $extraUrl = str_replace(request()->url(), '', request()->fullUrl());
            $links[] = Button::make('<span class="fa fa-edit"></span> '. __('Edit'))->addLink("{$this->base_url}/{$object->getKey()}/edit{$extraUrl}");
        }

        // Add delete button
        if( \Auth::user()->can('delete', $object) ) {
            $links[] = Button::make('<i class="fa fa-times pr-2"></i> '.__('Delete'))
                ->setExtra("data-type='confirm' title='".__('Delete')."' data-info='".__('Do you really want to remove this item?')."' data-button-yes='".__('Yes')."' data-button-no='".__('No')."' data-action='".url($this->base_url.'/'.$object->getKey())."' data-redirection='".url($this->base_url)."' data-variables='{ \"_method\": \"delete\", \"_token\": \"{ csrf_token() }\" }'")
                ->setType('btn-danger');
        }

        return $links;
    }

    public function getIndexLinks()
    {
    	$links = [];

    	// Show create button
    	if($this->show_create_button_on_index && \Auth::user()->can('create', $this->getModel())) {
            $links[] = Button::make('<span class="fa fa-plus"></span> '. __('Create') .' '.$this->label)->addLink($this->base_url.'/create');
    	}

    	// Show index actions
	    foreach($this->getIndexActions() as $action) {
            $links[] = Button::make($action->button_text)->addLink($this->base_url."/action/{$action->slug}");
	    }

        // Show links added manually
        foreach($this->index_links() as $link => $text) {
            $links[] = Button::make($text)->addLink($link);
        }
	    return $links;
    }
}
