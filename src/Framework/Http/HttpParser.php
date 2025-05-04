<?php

namespace App\Framework\Http;


class HttpParser
{
    public static function parse(array $request): HttpRequest
    {
        $data = [];
        if ($request["REQUEST_METHOD"] == "GET")
            parse_str($request["QUERY_STRING"], $data);

        if ($request["REQUEST_METHOD"] == "POST")
            $data = $_POST;

        if ($request["CONTENT_TYPE"] == "application/x-www-form-urlencoded")
            parse_str(file_get_contents('php://input'), $data);

        return new HttpRequest($data, $request["REQUEST_URI"], $request["REQUEST_METHOD"]);
    }
}