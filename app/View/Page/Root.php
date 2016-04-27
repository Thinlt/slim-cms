<?php

namespace View\Page;

class Root extends \View\View {

    protected static $views = array(); //All views

    public function __construct()
    {
        parent::__construct();
        // Make default if first instance
        if (is_null($this->getSingleton())) {
            static::$views['root'] = $this;
        }

        $this->addChild('head', new \View\Page\Head());
        $this->addChild('header', new \View\Page\Header());
        $this->addChild('content', new \View\Page\Content());
        $this->addChild('footer', new \View\Page\Footer());

    }

    public function addChildGlobal($name, $child){
        static::$views[$name] = $child;
        return $this;
    }

    public function getChildGlobal($name){
        if(isset(static::$views[$name])){
            return static::$views[$name];
        }
        return false;
    }

    public function removeChild($name){
        if(isset(static::$views[$name])){
            unset(static::$views[$name]);
        }
        return $this;
    }

    public function updateChildGlobalPosition($childName, $position = 'after', $childPos = null){
        if($position && $childPos){
            if(isset(static::$_childGlobals[$childPos])){
                $A = array();
                $B = array();
                if($position == 'before'){
                    $switch = 'A';
                    foreach (static::$_childGlobals as $key => $childGlobal) {
                        if($key == $childPos){
                            $switch = 'B';
                        }
                        if($switch == 'A'){
                            $A[$key] = $childGlobal;
                        }
                        if($switch == 'B'){
                            $B[$key] = $childGlobal;
                        }
                    }
                }elseif($position == 'after'){
                    $switch = 'A';
                    foreach (static::$_childGlobals as $key => $childGlobal) {
                        if($switch == 'A'){
                            $A[$key] = $childGlobal;
                        }
                        if($switch == 'B'){
                            $B[$key] = $childGlobal;
                        }
                        if($key == $childPos){
                            $switch = 'B';
                        }
                    }
                }
                array_push($A, $this->getChildGlobal($childName));
                foreach($B as $b){
                    array_push($A, $b);
                }
                static::$_childGlobals = $A;
            }
        }
        return $this;
    }

    public function getHeadHtml(){
        if($this->getChild('head')){
            return $this->getChild('head')->fetch();
        }
        return '';
    }

    public function getHeaderHtml(){
        if($this->getChild('header')){
            return $this->getChild('header')->fetch();
        }
        return '';
    }

    public function getContentHtml(){
        if($this->getChild('content')){
            return $this->getChild('content')->fetch();
        }
        return '';
    }

    public function getFooterHtml(){
        if($this->getChild('footer')){
            return $this->getChild('footer')->fetch();
        }
        return '';
    }


    /**
     * Singleton of View root
     * @param string $name
     * @return null
     */
    public static function getSingleton($name = 'root')
    {
        if(isset(static::$views[$name])){
            return static::$views[$name];
        }
        return null;
    }

}


