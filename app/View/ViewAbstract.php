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

    /**
     * set template name: sub_dir/file.html
     * @param $template
     * @return $this
     */
    public function setTemplate($template){
        $this->_template = $template;
        return $this;
    }

    /**
     * get template name include replaced Directory Separator
     * @return string
     */
    public function getTemplate(){
        return trim(str_replace('\\', DS, $this->_template), DS);
    }

    public function getTemplatesDirectory()
    {
        $app = \App::getInstance();
        if($app->config('templates.path')){
            return $app->config('templates.path');
        }
        return 'web'.DS.'template';
    }

    public function getConfigTemplageDirectory(){
        $app = \App::getInstance();
        return $app->config('templates.path');
    }

    /**
     * get full path template file
     * automatic called from fetch
     * @param string $template
     * @return string
     */
    public function getTemplatePathname($template)
    {
        $root = BP;
        if($template){
            $this->setTemplate($template);
        }
        if($this->_template){
            $tmpDS = $this->getTemplate();
            $tmpDir = trim($this->getTemplatesDirectory(), DS);
            $file = $root.DS.str_replace('\\', DS, $tmpDir).DS.$tmpDS;
            if(is_file($file)){
                return $file;
            }
            //get into directory app/View
            return $root.DS.'app'.DS.'View'.DS.str_replace('\\', DS, $tmpDir).DS.$tmpDS;
        }

        return '';

    }


    public function display($template = null, $data = null)
    {
        if($template){
           $this->setTemplate($template);
        }
        parent::display($this->_template, $data);
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
        return parent::fetch($this->getTemplate(), $this->_data);
    }

    protected function _beforeToHtml(){

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
            $this->setTemplate(strtolower($this->_getClassTemplate()).'.html');
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
                get_class($this), $this->getTemplate(), $html);
        }
        return $html;
    }

    //get path from view class directory
    protected function _getClassTemplate(){
        $basePath = BP;
        $reflector = new \ReflectionClass(get_class($this));
        $curFile = $reflector->getFileName();
        $curPath = dirname($curFile).DS.basename($curFile, '.php');
        return trim(str_replace($basePath.DS.'app'.DS.'View', '', $curPath), DS);
    }

    /**
     * get url in view
     * @param string $path
     * @param array $params
     * @return mixed
     */
    public function getUrl($path = '', $params = array()){
        return \App::getInstance()->getUrl($path, $params);
    }
}


