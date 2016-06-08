<?php

namespace View\Page;

class Head extends \View\View {

    protected static $link = array();
    protected static $css = array();
    protected static $js = array();
    protected static $meta = array();
    protected static $title = '';

    public function __construct()
    {
        $this->addMeta(array('http-equiv'=>'X-UA-Compatible', 'content'=>'IE=edge'));
        $this->addMeta('viewport', 'width=device-width, initial-scale=1');

        $this->addJs('//ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js');
        $this->addJs('//ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular.min.js');
        $this->addJs('//ajax.googleapis.com/ajax/libs/angular_material/1.0.7/angular-material.min.js');
        $this->setTitle(\App::getInstance()->config('site_name'));
        parent::__construct();
    }


    public function addCss($url){
        static::$css[] = $this->getCssFileUrl($url);
        return $this;
    }

    public function addJs($url){
        static::$js[] = $this->getJsFileUrl($url);
        return $this;
    }

    public function getCssFileUrl($fileName){
        return $this->addItem('css', $fileName);
    }

    public function getJsFileUrl($fileName){
        return $this->addItem('js', $fileName);
    }

    public function addItem($type, $fileName){
        if(!$type){
            return '';
        }
        return $this->getSkinUrl($type, $fileName);
    }

    public function addMeta($name, $content = ''){
        if(func_num_args() == 1 && is_array($name)){
            static::$meta[] = $name;
        }else{
            static::$meta[] = array(
                'name'  =>  $name,
                'content' => $content
            );
        }
        return $this;
    }

    public function addLink($href, $rel, $args = array()){
        static::$link[] = array(
            'href'  =>   $href,
            'rel'   =>   $rel,
            'args'  =>   $args
        );
        return $this;
    }

    public function getJsHtml(){
        $js = '';
        foreach (static::$js as $url) {
            $js .= '<script type="text/javascript" src="'.$url.'"></script>'.PHP_EOL;
        }
        return $js;
    }

    public function getCssHtml(){
        $css = '';
        foreach (static::$css as $url) {
            $css .= '<link rel="stylesheet" type="text/css" href="'.$url.'" />'.PHP_EOL;
        }
        return $css;
    }

    public function getMetaHtml(){
        $html = '';
        foreach (static::$meta as $meta) {
            $html .= '<meta ';
            foreach ($meta as $key => $value) {
                $html .= $key.'="'.$value.'" ';
            }
            $html .= '/>'.PHP_EOL;
        }
        return $html;
    }

    public function getLinkHtml(){
        $html = '';
        foreach (static::$link as $link) {
            $html .= '<link rel="'.$link['rel'].'" href="'.$link['href'].'" ';
            foreach (static::$link['args'] as $key => $arg) {
                if(!is_numeric($key)){
                    $html .= $key.'="'.$arg.'" ';
                }else{
                    $html .= $arg.' ';
                }
            }
            $html .= ' />'.PHP_EOL;
        }
        return $html;
    }

    public function setTitle($title = ''){
        static::$title = $title;
        return $this;
    }

    public function getTitle(){
        return static::$title;
    }

    public function getTitleHtml(){
        return '<title>'.static::$title.'</title>'.PHP_EOL;
    }
}


