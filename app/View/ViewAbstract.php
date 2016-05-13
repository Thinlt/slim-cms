<?php

namespace View;

abstract class ViewAbstract extends \Slim\View {
    public $_name;
    public $_template;
    public $_data;

    public function __construct()
    {
        parent::__construct();
        $this->_init();
    }

    protected function _init(){
        return $this;
    }

    public function setName($name){
        $this->_name = $name;
        return $this;
    }

    public function getName(){
        return $this->_name;
    }

    public function setTemplate($template){
        $template = str_replace('/', '\\', $template);
        $app = \App::getInstance();
        $this->templatesDirectory = $this->getTemplatesDirectory();
        if(!is_file($this->getTemplatePathname($template))){
            $this->templatesDirectory = $app->config('templates.path');
            $this->setTemplatesDirectory('app'.DS.'View'.DS.$this->templatesDirectory);
        }
        $this->_template = $template;
        return $this;
    }

    public function getTemplatesDirectory()
    {
        $app = \App::getInstance();
        if(!$this->templatesDirectory && !$app->config('templates.path')){
            return 'web'.DS.'template';
        }elseif($this->templatesDirectory){
            return $this->templatesDirectory;
        }else{
            return $app->config('templates.path');
        }
    }

    public function getConfigTemplageDirectory(){
        $app = \App::getInstance();
        return $app->config('templates.path');
    }

    public function getTemplatePathname($file)
    {
        if($this->_template){
            return BP.DS.parent::getTemplatePathname($this->_template);
        }
        return BP.DS.parent::getTemplatePathname($file);
    }


    public function display($template = null, $data = null)
    {
        if($template){
           $this->setTemplate($template);
        }
        parent::display($this->_template, $data);
    }

    /**
     * get display html
     * @param null $template
     * @param null $data
     * @return string
     */
    public function fetch($template = null, $data = null)
    {
        if($template){
            $this->_template = $template;
        }
        if(!$this->_template){
            $this->setTemplate(strtolower($this->_getPathDir()).'.html');
        }
        if($data){
            $this->_data = $data;
        }
        $html = $this->toHtml();
        $path_hint = \App::getInstance()->config('path_hint');
        if($path_hint){
            $html = sprintf('<div class="path-hint" style="position:relative;border:1px dotted red;margin:6px 2px;
padding:18px 2px 2px;zoom:1">
<div style="position:absolute;left:0;top:0;padding:2px 5px;color:#fff;font-style:normal;font-variant:normal;
font-weight:400;font-stretch:normal;font-size:11px;line-height:normal;font-family:Arial;z-index:998;
text-align:left!important;background:red">%s</div>
<div style="position:absolute;right:0;top:0;padding:2px 5px;color:blue;font-style:normal;font-variant:normal;
font-weight:400;font-stretch:normal;font-size:11px;line-height:normal;font-family:Arial;z-index:998;
text-align:left!important;background:red">%s</div>%s</div>',
                    get_class($this), $this->_template, $html);
        }
        return $html;
    }

    /**
     * get html view
     * @param null $template
     * @param null $data
     * @return string
     */
    public function getHtml($template = null, $data = null){
        return $this->fetch($template, $data);
    }

    public function toHtml(){
        $beforeHtml = $this->_beforeToHtml();
        if(is_string($beforeHtml) && $beforeHtml != ''){
            return $beforeHtml;
        }
        return parent::fetch($this->getTemplatePathname($this->_template), $this->_data);
    }

    protected function _beforeToHtml(){

    }

    //get path from view class directory
    protected function _getPathDir(){
        $basePath = BP;
        $reflector = new \ReflectionClass(get_class($this));
        $curFile = $reflector->getFileName();
        $curPath = dirname($curFile).DS.basename($curFile, '.php');
        return trim(str_replace($basePath.DS.'app'.DS.'View', '', $curPath), DS);
    }

    public function getUrl($path = '', $params = array()){
        return \App::getInstance()->getUrl($path, $params);
    }
}


