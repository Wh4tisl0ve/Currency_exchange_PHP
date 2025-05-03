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
            "QUERY_STRING" => "query=test&search=test"
        ];
        $httpRequest = HttpParser::parse($request);

        $expectedResult = ["query" => "test", "search" => "test"];

        $this->assertInstanceOf(HttpRequest::class, $httpRequest);
        $this->assertEquals($expectedResult, $httpRequest->getData());
    }

    public function testParsePostHttp()
    {
        $request = ["REQUEST_METHOD" => "POST"];
        $_POST = ["query" => "test", "search" => "test"];

        $httpRequest = HttpParser::parse($request);

        $expectedResult = ["query" => "test", "search" => "test"];

        $this->assertInstanceOf(HttpRequest::class, $httpRequest);
        $this->assertEquals($expectedResult, $httpRequest->getData());
    }

    public function testParseOtherHttp()
    {
        $request = [
            "REQUEST_METHOD" => "DELETE",
            "CONTENT_TYPE" => "application/x-www-form-urlencoded",
        ];

        $httpRequest = HttpParser::parse($request);

        $expectedResult = [];

        $this->assertInstanceOf(HttpRequest::class, $httpRequest);
        $this->assertEquals($expectedResult, $httpRequest->getData());
    }
}