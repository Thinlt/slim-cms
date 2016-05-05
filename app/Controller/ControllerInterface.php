<?php

namespace Controller;

interface ControllerInterface {
    public function execute($app);

    public function _run($app);
}
