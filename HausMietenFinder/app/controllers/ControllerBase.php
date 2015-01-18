<?php

use Phalcon\Mvc\Controller;
use DevDes\Helpers\UI\CSSHelper;
use DevDes\Helpers\UI\JSHelper;
use DevDes\Helpers\Core\CoreHelper;

class ControllerBase extends Controller
{
    public function initialize()
    {
        // Basic Properties
        $this->view->main_properties = array(
            "base_url" => CoreHelper::GetBaseUrl(),
            "base_url_api" => CoreHelper::GetBaseUrl() . '-',
            "base_url_css" => CSSHelper::GetUrl(),
            "base_url_js" => JSHelper::GetUrl(),
            "action_name" =>  $this->getDI()->getDispatcher()->getActionName(),
            "controller_name" => $this->getDI()->getDispatcher()->getControllerName()
        );

        $this->view->main_properties_json = json_encode($this->view->main_properties);
        
        //Javascript in the header
        $this->view->static_assets = array(
            "headerJSLTIE9" => JSHelper::GetConcatenatedTags("ltie9"),
            "headerCSS" => CSSHelper::GetConcatenatedTags("main"),
            "footerJS" => JSHelper::GetConcatenatedTags("main")
        );
    }

    public function Error($message) {
        $this->view->disable();
        $this->response->setContent("<h1>Error</h1><p>" . htmlentities($message) . "</p>");
        $this->response->setStatusCode(500, $message);
        $this->response->send();
    }

    public function SendJson($object) {
        $this->view->disable();
        $this->response->setHeader("Content-Type", "application/json");
        $this->response->setContent(json_encode($object));
        $this->response->send();
    }
}
