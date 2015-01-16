<?php

namespace DevDes\Helpers\Net;

class RequestHelper {

    public static function IsInDebugMode() {
        $request = new \Phalcon\Http\Request();

        return $request->get("debug") == "1";
    }
}