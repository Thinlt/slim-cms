<?php

namespace controllers;


class Test extends \Controller\ControllerAbstract {
    public function execute($app)
    {

        $test_object = new \Model\Test();

        $test_object->setData(array(
            'test' => 'test tesst',
            'test_2' => 123
        ))->save();

        $test_object->setData(array(
            'test' => 'test 1234',
            'test_2' => 234
        ))->save();

//        $test_object->getCollection()->load();
//        var_dump($test_object->getCollection()->getCount());

        $id = $app->request()->get('id');

        var_dump($test_object->load($id)->getData());

        var_dump($test_object->getVersionInstalled());

    }


}

