<?php

namespace Tests\Framework\HttpParser;

use App\Framework\Http\HttpParser;
use App\Framework\Http\HttpRequest;
use PHPUnit\Framework\TestCase;

class HttpParserTest extends TestCase
{
    public function testParseGetHttp()
    {
        $request = [
            "REQUEST_METHOD" => "GET",
            "QUERY_STRING" => "query=test&search=test",
            "REQUEST_URI" => "/",
            "CONTENT_TYPE" => ""
        ];
        $httpRequest = HttpParser::parse($request);

        $expectedResult = ["query" => "test", "search" => "test"];

        $this->assertEquals($expectedResult, $httpRequest->getData());
    }

    public function testParsePostHttp()
    {
        $request = ["REQUEST_METHOD" => "POST", "REQUEST_URI" => "/", "CONTENT_TYPE" => "application/x-www-form-urlencoded"];
        $_POST = ["query" => "test", "search" => "test"];

        $httpRequest = HttpParser::parse($request);

        $expectedResult = ["query" => "test", "search" => "test"];

        $this->assertEquals($expectedResult, $httpRequest->getData());
    }

    public function testParseOtherHttp()
    {
        $request = [
            "REQUEST_METHOD" => "DELETE",
            "CONTENT_TYPE" => "application/x-www-form-urlencoded",
            "REQUEST_URI" => "/"
        ];

        $httpRequest = HttpParser::parse($request);

        $expectedResult = [];

        $this->assertEquals($expectedResult, $httpRequest->getData());
    }
}