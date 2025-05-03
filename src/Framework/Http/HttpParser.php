<?php

namespace App\Framework\Http;


class HttpParser
{
    public static function parse(array $request): HttpRequest
    {
        if ($request["REQUEST_METHOD"] == "GET") {
            parse_str($request["QUERY_STRING"], $data);
            return new HttpRequest($data);
        }

        if ($request["REQUEST_METHOD"] == "POST")
            return new HttpRequest($_POST);

        $data = [];

        if ($request["CONTENT_TYPE"] == "application/x-www-form-urlencoded")
            parse_str(file_get_contents('php://input'), $data);

        return new HttpRequest($data);
    }
}