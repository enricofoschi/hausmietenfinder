<?php
/**
 * Created by PhpStorm.
 * User: Main
 * Date: 09.01.15
 * Time: 08:32
 */

class IndexController  extends ControllerBase {
    public function indexAction() {

    }

    public function route404Action() {
        $this->view->disable();
        $this->response->setContent('File not found');
        $this->response->setStatusCode(404, 'Not Found');
        $this->response->send();
    }

    public function error500Action($exception) {
        parent::Error($exception);
    }
}
