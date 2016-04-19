<?php


namespace Controller;


abstract class ControllerAbstract implements \Controller\ControllerInterface {
    abstract function execute($app);
}

