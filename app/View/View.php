<?php

namespace View;

class View extends \View\ViewAbstract {

    protected $_childs = array();

    public function addChild($name, $child, $position = 'after'){
        //check root childs
        $root = $this->getRoot();
        if($root->getChildGlobal($name)){
            throw new \Exception('Can not add child existed!');
        }
        $root->addChildGlobal($name, $child);
        $child->setName($name); //add name to it's self
        if($position == 'before'){
            $arrayKey = array($name);
            $arrayVal = array($child);
            foreach ($this->_childs as $key => $item) {
                $arrayKey[] = $key;
                $arrayVal[] = $item;
            }
            $this->_childs = array_combine($arrayKey, $arrayVal);
        }else{
            $this->_childs[$name] = $child;
        }
        return $this;
    }

    /**
     * Add child to existed name
     * @param $name
     * @param $child
     */
    public function updateChild($name, $child, $position = 'after'){
        $root = $this->getRoot();
        if($root->getChildGlobal($name)){
            $root->addChildGlobal($name, $child);
            $this->_childs[$name] = $child;
        }else{
            $this->addChild($name, $child, $position);
        }
        return $this;
    }

    /**
     * @param $name
     * @return bool | \View\View
     */
    public function getChild($name){
        if(isset($this->_childs[$name])){
            return $this->_childs[$name];
        }
        return false;
    }

    public function getAllChilds(){
        return $this->_childs;
    }

    /**
     * Remove child view and view in root
     * @param $name
     * @return $this
     */
    public function remove($name){
        if(isset($this->_childs[$name])){
            unset($this->_childs[$name]);
            $this->getRoot()->removeChild($name);
        }
        return $this;
    }

    //set this as child of other view
    /**
     * @param $name     @param $name     root.header.nav[.childName]
     * @param $childName
     * @param string $position  'before'|'after'
     * @param null $childPosName
     * @return bool|\Slim\View|Page\Root
     * @throws \Exception
     */
    public function reference($name, $childName = null, $position = 'after', $childPosName = null){
        if($name == 'root'){
            $root = $this->getRoot();
            if($childName){
                $root->updateChild($childName, $this, $position);
            }
            $root->updatePosition($childName, $position, $childPosName);
            return $root;
        }
        //find other child from root
        $viewRefer = $this->getRoot()->getChildGlobal($name);
        if(!$viewRefer){
            throw new \Exception('No view to reference.');
        }
        if($childName){
            $viewRefer->updateChild($childName, $this, $position);
        }
        $viewRefer->updatePosition($childName, $position, $childPosName);
        return $viewRefer;
    }

    protected function updatePosition($childName, $position = 'after', $childPosName = null){
        if($position && $childPosName){
            if(isset($this->_childs[$childPosName])){
                $A = array();
                $B = array();
                if($position == 'before'){
                    $switch = 'A';
                    foreach ($this->_childs as $key => $child) {
                        if($key == $childPosName){
                            $switch = 'B';
                        }
                        if($switch == 'A'){
                            $A[$key] = $child;
                        }
                        if($switch == 'B'){
                            $B[$key] = $child;
                        }
                    }
                }elseif($position == 'after'){
                    $switch = 'A';
                    foreach ($this->_childs as $key => $child) {
                        if($switch == 'A'){
                            $A[$key] = $child;
                        }
                        if($switch == 'B'){
                            $B[$key] = $child;
                        }
                        if($key == $childPosName){
                            $switch = 'B';
                        }
                    }
                }
                $A[$childName] = $this->getChild($childName);
                foreach($B as $key => $b){
                    $A[$key] = $b;
                }
                $this->_childs = $A;
            }
        }
        return $this;
    }

    public function getRoot(){
        $root = \View\Page\Root::getSingleton();
        if(!($root instanceOf \View\Page\Root)){
            $root = new \View\Page\Root();
        }
        return $root;
    }

    /**
     * Get HTML of child views
     * @param null $name
     * @return string
     */
    public function getChildHtml($name = null){
        if($name){
            $child = $this->getChild($name);
            if($child){
                return $child->fetch();
            }
            return '';
        }
        $html = '';
        $all = $this->getAllChilds();
        foreach($all as $child){
            $html .= $child->fetch();
        }
        return $html;
    }

    public function childHtml($name = null){
        echo $this->getChildHtml($name);
    }

    public function getImageUrl($fileName){
        return $this->getSkinUrl('images', $fileName);
    }

    public function getSkinUrl($type, $file){
        $type = strtolower($type);
        $templateDir = $this->getTemplatesDirectory();
        $filePath = BP.DS.dirname($templateDir).DS.$type.DS.str_replace('/', DS, $file); //web/template/../js
        $fileLink = trim(str_replace(DS, '/', dirname($templateDir).DS.$type.DS.$file), '/');
        if(!is_file($filePath)){
            $fileLink = trim(
                    str_replace(DS, '/', 'app'.DS.'View'.DS.dirname($templateDir)
                        .DS.$type.DS.str_replace('/', DS, $file)
                    ), '/');
            $filePath = BP.DS.'app'.DS.'View'.DS.dirname($templateDir).DS.$type.DS.$file; //app/View/web/template/../js
        }
        if(!is_file($filePath)){
            return $file;
        }
        $app = \App::getInstance();
        $req = $app->request;
        $rootUri = $req->getRootUri();
        $url = $_SERVER['HTTP_HOST'];
        if(trim($rootUri, '/')){
            $url .= '/'.trim($rootUri, '/');
        }
        $url .= '/'.$fileLink;
        if(substr($url, 0, 9) != 'https://' || substr($url, 0, 8) != 'http://'){
            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
                $url = 'https://'.$url;
            }else{
                $url = 'http://'.$url;
            }
        }
        return $url;
    }
}


