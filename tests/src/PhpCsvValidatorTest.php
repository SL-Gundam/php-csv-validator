<?php

/**
 * PHPUnit Test for Class PhpCsvValidator
 *
 * @author Sven Kuegler <sven.kuegler@gmail.com>
 */
class PhpCsvValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PhpCsvValidator
     */
    protected $object;

    /**
     * Init Class
     */
    protected function setUp() {
        $this->object = new PhpCsvValidator();
    }

    /**
     * @covers PhpCsvValidator
     * @covers PhpCsvValidator::getScheme
     */
    public function testConstructorParams() {
        $test = new PhpCsvValidator(new PhpCsvValidatorScheme("{\"label\":\"Test\", \"skipFirstLine\": 0, \"regex\": \"/(.*)/\"}"));
        $this->assertEquals("Test", $test->getScheme()->label);
        $this->assertEquals(0, $test->getScheme()->skipFirstLine);
        $this->assertEquals("/(.*)/", $test->getScheme()->regex);
    }

    /**
     * @expectedException PhpCsvValidatorException
     */
    public function testConstructorException() {
        $test = new PhpCsvValidator("foo bar");
        $test->getScheme();
    }

    /**
     * @covers PhpCsvValidator::isValidRow
     */
    public function testIsValidRow()
    {
        $this->object->loadSchemeFromFile("tests/files/example-scheme1.json");

        $this->assertEquals(true, $this->object->isValidRow("test;test;test;test"));
        $this->assertEquals(false, $this->object->isValidRow("test;test;test;test;test"));

        $this->object->loadSchemeFromFile("tests/files/example-scheme2.json");

        $this->assertEquals(false, $this->object->isValidRow("test;test;test;test"));
        $this->assertEquals(true, $this->object->isValidRow('1;"Bob";"bob@example.com";25877'));
    }

    /**
     * @covers PhpCsvValidator::isValidFile
     * @covers PhpCsvValidator::loadSchemeFromFile
     */
    public function testIsValidFile()
    {
        $this->object->loadSchemeFromFile("tests/files/example-scheme2.json");
        $this->assertEquals(true,$this->object->isValidFile("tests/files/example.csv"));
    }

    /**
     * @covers PhpCsvValidator::isValidFile
     * @covers PhpCsvValidator::loadSchemeFromFile
     * @expectedException PhpCsvValidatorException
     */
    public function testIsValidFileException()
    {
        $this->object->loadSchemeFromFile("tests/files/example-scheme2.json");
        $this->assertEquals(true,$this->object->isValidFile("path/to/invalid/file.csv"));
    }

    /**
     * @covers PhpCsvValidator::loadSchemeFromFile
     * @covers PhpCsvValidator::setScheme
     * @covers PhpCsvValidator::getScheme
     */
    public function testLoadSchemeFromFile()
    {
        $this->object->loadSchemeFromFile("tests/files/example-scheme1.json");

        $this->assertEquals(true, ($this->object->getScheme() instanceof PhpCsvValidatorScheme));
        $this->assertEquals("Example Scheme 1", $this->object->getScheme()->label);
    }

    /**
     * @covers PhpCsvValidator::loadSchemeFromFile
     * @expectedException PhpCsvValidatorException
     */
    public function testLoadSchemeFromFileException()
    {
        $this->object->loadSchemeFromFile("path/to/invalid/filename.json");
    }

    /**
     * @covers PhpCsvValidator::getScheme
     * @covers PhpCsvValidator::setScheme
     */
    public function testGetScheme()
    {
        $this->object->setScheme(new PhpCsvValidatorScheme("{\"label\":\"Test\", \"skipFirstLine\": 0, \"regex\": \"/(.*)/\"}"));

        $this->assertEquals("Test", $this->object->getScheme()->label);
        $this->assertEquals(0, $this->object->getScheme()->skipFirstLine);
        $this->assertEquals("/(.*)/", $this->object->getScheme()->regex);
    }

    /**
     * @covers PhpCsvValidator::setErrorMessages
     * @covers PhpCsvValidator::getErrorMessages
     */
    public function testErrorMessages()
    {
        $this->object->setErrorMessages("Message 1");
        $this->object->setErrorMessages("Message 2");
        $this->object->setErrorMessages("Message 3");

        $this->assertEquals(3, count($this->object->getErrorMessages()));
    }
}
