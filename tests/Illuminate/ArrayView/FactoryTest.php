<?php

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public static $objects = array();

    public static function setUpBeforeClass()
    {
        $author = new stdClass();
        $author->name = 'Huy Ta';

        $article = new stdClass();
        $article->title = 'Array View';
        $article->author = $author;
        self::$objects['article'] = $article;
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage View [viewNotFound] not found
     */
    public function testViewNotFound()
    {
        $results = arrayView('viewNotFound');
    }

    /**
     * ============================ Test Set Method ============================
     */

    public function testSetValueToKey()
    {
        $results = arrayView('testSet.setValue', array(
            'title'   => 'Example',
        ));
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('title', $results);
        $this->assertEquals('Example', $results['title']);
        $this->assertArrayHasKey('version', $results);
        $this->assertEquals('1.0', $results['version']);
    }

    public function testSetValueIsFunction()
    {
        $results = arrayView('testSet.setFunction', array(
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
        $results = arrayView('testSet.setWithoutKey', array(
            'author'   => 'John Doe',
        ));
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('author', $results);
        $this->assertEquals('John Doe', $results['author']);
    }

    public function testSetUseObject()
    {
        $results = arrayView('testSet.article', array(
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
    
    public function testEach()
    {
        
    }
}
