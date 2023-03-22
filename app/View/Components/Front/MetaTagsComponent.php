<?php

namespace App\View\Components\Front;

use App\Libraries\MetaTagsManager;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MetaTagsComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('front.components.meta-tags', [
            'title' => MetaTagsManager::title(),
            'description' => MetaTagsManager::description(),
            'image' => MetaTagsManager::image(),
            'follow' => MetaTagsManager::follow(),
            'index' => MetaTagsManager::index(),
        ]);
    }
}
