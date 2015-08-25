<?php

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public static $objects = array();

    public static function setUpBeforeClass()
    {
        $author = new stdClass();
        $author->name = 'John Doe';
        $author->gender = 'male';

        $article = new stdClass();
        $article->title = 'Example Title';
        $article->author = $author;
        self::$objects['article'] = $article;
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage View [viewNotFound] not found.
     */
    public function testViewNotFound()
    {
        $results = arrayView('viewNotFound');
    }

    public function testSetViewPaths()
    {
        $factory = new ChickenCoder\ArrayView\Factory;
        $this->assertEquals([], $factory->getViewPaths());
        $factory->setViewPaths([__DIR__]);
        $this->assertEquals([__DIR__], $factory->getViewPaths());
    }

    /**
     * ============================ Test Set Method ============================
     */

    public function testSetValueToKey()
    {
        $results = arrayView('testSet/setValue', array(
            'title'   => 'Example',
        ));
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('title', $results);
        $this->assertEquals('Example', $results['title']);
        $this->assertArrayHasKey('version', $results);
        $this->assertEquals('1.0', $results['version']);
        $this->assertEquals(null, $results['description']);
        $this->assertInternalType('object', $results['author']);
    }

    public function testSetValueIsFunction()
    {
        $results = arrayView('testSet/setFunction', array(
            'author'   => 'John Doe',
        ));
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('author', $results);
        $this->assertArrayHasKey('name', $results['author']);
        $this->assertEquals('John Doe', $results['author']['name']);
        $this->assertArrayHasKey('location', $results['author']);
        $this->assertEquals('en', $results['author']['location']);
    }

    public function testSetWithoutKey()
    {
        $results = arrayView('testSet/setWithoutKey', array(
            'author'   => 'John Doe',
        ));
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('author', $results);
        $this->assertEquals('John Doe', $results['author']);
    }

    public function testSetUseObject()
    {
        $results = arrayView('testSet/article', array(
            'article'   => self::$objects['article'],
        ));
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('title', $results);
        $this->assertEquals(self::$objects['article']->title, $results['title']);
        $this->assertArrayHasKey('author', $results);
        $this->assertArrayHasKey('name', $results['author']);
        $this->assertEquals(self::$objects['article']->author->name, $results['author']['name']);
    }

    public function testUseViewDirectViewFolder()
    {
        $results = arrayView(__DIR__.'/../otherViews/test.array.php', array(
            'article'   => self::$objects['article'],
        ));
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('title', $results);
        $this->assertEquals(self::$objects['article']->title, $results['title']);
        $this->assertArrayHasKey('author', $results);
        $this->assertArrayHasKey('name', $results['author']);
        $this->assertEquals(self::$objects['article']->author->name, $results['author']['name']);

        // have not extension
        $results = arrayView(__DIR__.'/../otherViews/test', array(
            'article'   => self::$objects['article'],
        ));
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('title', $results);
        $this->assertEquals(self::$objects['article']->title, $results['title']);
        $this->assertArrayHasKey('author', $results);
        $this->assertArrayHasKey('name', $results['author']);
        $this->assertEquals(self::$objects['article']->author->name, $results['author']['name']);
    }

    /**
     * ============================ Test Each Method ============================
     */

    public function testEachWithEmptyArray()
    {
        $results = arrayView('testEach/test', [ 'numbers' => [] ]);
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('numbers', $results);
        $this->assertInternalType('array', $results['numbers']);
        $this->assertEquals(0, count($results['numbers']));
    }

    public function testEachWithArray()
    {
        $results = arrayView('testEach/test', [ 'numbers' => ['one', 'two'] ]);
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('numbers', $results);
        $this->assertInternalType('array', $results['numbers']);
        $this->assertEquals(2, count($results['numbers']));
        $this->assertArrayHasKey('number', $results['numbers'][0]);
        $this->assertEquals('one', $results['numbers'][0]['number']);
        $this->assertArrayHasKey('number', $results['numbers'][1]);
        $this->assertEquals('two', $results['numbers'][1]['number']);
    }

    /**
     * ============================ Test Partial Method ============================
     */

    public function testPartial()
    {
        $results = arrayView('testPartial/article', array(
            'article'   => self::$objects['article'],
        ));
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('title', $results);
        $this->assertEquals(self::$objects['article']->title, $results['title']);
        $this->assertArrayHasKey('author', $results);
        $this->assertArrayHasKey('name', $results['author']);
        $this->assertEquals(self::$objects['article']->author->name, $results['author']['name']);
        $this->assertArrayHasKey('gender', $results['author']);
        $this->assertEquals(self::$objects['article']->author->gender, $results['author']['gender']);
    }

    /**
     * ============================ Test Extract Method ============================
     */

    public function testExtract()
    {
        $results = arrayView('testExtract/test', array(
            'article'   => [
                'title' => 'Example Title',
                'body' => 'Example Body',
                'created' => '2015-07-16'
            ]
        ));
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('title', $results);
        $this->assertEquals('Example Title', $results['title']);
        $this->assertArrayHasKey('created', $results);
        $this->assertEquals('2015-07-16', $results['created']);
        $this->assertArrayNotHasKey('body', $results);
    }

    /**
     * ============================ Test Helper Method ============================
     */

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Helper [abcdef] not found.
     */
    public function testHelperNotFound()
    {
        $results = arrayView('testHelper/testNotFound', array(
            'title' => 'example title'
        ));
    }

    public function testHelper()
    {
        $results = arrayView('testHelper/test', array(
            'title' => 'example title'
        ));
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('title', $results);
        $this->assertEquals('EXAMPLE TITLE', $results['title']);
    }

    public function testUserHelperDirectViewFolder()
    {
        $results = arrayView(__DIR__.'/../otherViews/helper.array.php', array(
            'title' => 'EXAMPLE TITLE'
        ));
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('title', $results);
        $this->assertEquals('example title', $results['title']);

        // have not extension
        $results = arrayView(__DIR__.'/../otherViews/helper', array(
            'title' => 'EXAMPLE TITLE'
        ));
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('title2', $results);
        $this->assertEquals('example title', $results['title2']);
    }

    /**
     * @expectedException BadFunctionCallException
     * @expectedExceptionMessage Helper [helperInvalid] is invalid.
     */
    public function testHelperInvalid()
    {
        $results = arrayView('testHelper/testHelperInvalid', array(
            'title' => 'example title'
        ));
    }
}
