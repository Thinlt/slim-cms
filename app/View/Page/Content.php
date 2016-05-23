<?php

namespace View\Page;

class Content extends \View\View {

    public function __construct()
    {
        parent::__construct();
    }

    public function getPageClass(){
        $head = $this->reference('head');
        $title = strtolower($head->getTitle());
        $title = preg_replace('/\s+/', '-', $title);
        return $title;
    }

}


