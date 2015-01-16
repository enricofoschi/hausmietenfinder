<?php

use Phalcon\Mvc\Controller;
use DevDes\Helpers\UI\CSSHelper;
use DevDes\Helpers\UI\JSHelper;

class AssetsController extends ControllerBase {

    /**
     * @param $content_type string header content type
     * @param $content string content to set in the response
     */
    protected function outputContent($content_type, $content) {
        $this->view->disable();
        $this->response->setHeader("Content-Type", $content_type);
        $this->response->setContent($content);
        $this->response->send();
    }

    /**
     * @param $path_parts string func_get_args() parts of the path
     * @return null|string null if an exception occurred (e.g. hacking), the requested file path otherwise
     */
    protected function getPath($path_parts) {
        $path = implode('/', $path_parts);

        if(strpos($path, '..') !== false || strpos($path, '//') !== false) {
            parent::Error("Could not find requested file");
            return null;
        }

        return $path;
    }

    public function getCSSAction() {
        $path = $this->getPath(func_get_args());

        if($path) {
            $this->outputContent("text/css", CSSHelper::GetFileContent($path));
        }
    }

    public function getJSAction() {

        $path = $this->getPath(func_get_args());

        if($path) {
            $content = JSHelper::GetFileContent($path);

            if(!$content) {
                parent::Error("Nothing to show here");
            } else {
                $this->outputContent("text/javascript", $content);
            }
        }
    }

    public function getConcatenatedCSSAction($identifier)
    {
        $this->outputContent("text/css", CSSHelper::GetConcatenatedContent($identifier));
    }

    public function getConcatenatedJSAction($identifier)
    {
        $this->outputContent("text/javascript", JSHelper::GetConcatenatedContent($identifier));
    }
}