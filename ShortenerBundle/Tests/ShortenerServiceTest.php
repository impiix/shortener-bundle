<?php

namespace ShortenerBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class ShortenerServiceTest extends WebTestCase
{

    public function testEncodeAndDecode()
    {
        $path = "/test";
        $service = $this->getContainer()->get("shortener.service");
        $code = $service->encode($path, false);
        $returnedPath = $service->decode($code);
        $this->assertEquals($path, $returnedPath);

        $path = md5(rand());
        $service = $this->getContainer()->get("shortener.service");
        $code = $service->encode($path, false);
        $returnedPath = $service->decode($code);
        $this->assertEquals($path, $returnedPath);
    }

    public function testEncode()
    {
        $service = $this->getContainer()->get("shortener.service");
        $class = new \ReflectionClass($service);
        $createCodeMethod = $class->getMethod("createCode");
        $grabIdMethod = $class->getMethod("grabId");
        $grabIdMethod->setAccessible(true);
        $createCodeMethod->setAccessible(true);
        $id = 42355534534;
        $code = $createCodeMethod->invokeArgs($service, [$id]);
        $rid = $grabIdMethod->invokeArgs($service, [$code]);
        $this->assertEquals($id, $rid);

    }
}
