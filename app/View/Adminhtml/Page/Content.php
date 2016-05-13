<?php

namespace View\Adminhtml\Page;

class Content extends \View\Page\Content {

    public function __construct()
    {
        parent::__construct();
    }


    protected function _init()
    {
        $this->addChild('content.header', new \View\Adminhtml\Page\ContentHeader());
        $this->addChild('content.bottom', new \View\Adminhtml\Page\ContentBottom());
    }

}


