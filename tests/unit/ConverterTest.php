<?php
declare(strict_types=1);

namespace Tests;

use CsvConverter\Converter;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->converter = new Converter;
    }

    public function testSetFileHeaders()
    {
        $this->converter->setFileHeaders($this->getFieldNames());
        $this->assertEquals($this->converter->getFileHeaders(), $this->getFieldNames());
    }

    public function testGetDataFieldsWithFieldNames()
    {
        $array = $this->getArray();
        $response = $this->converter->getDataFields($array[0], $this->getFieldNames());

        $this->assertEquals($response[0], $array[0][$this->getFieldNames()[0]]);
        $this->assertEquals(count($response), count($this->getFieldNames()));
    }

    public function testGetDataFieldsWithoutFieldNames()
    {
        $array = $this->getArray();
        $response = $this->converter->getDataFields($array[0]);

        $this->assertEquals(count($response), count($array[0]));
    }

    public function testConvertArrayOfArraysWithHeadersAndFields()
    {
        $response = $this->converter->convertArray($this->getArray(), $this->getFieldNames());

        $this->assertNotEmpty($response);
    }

    public function testConvertArrayOfObjectWithHeadersAndFields()
    {
        $response = $this->converter->convertArray($this->getObject(), $this->getFieldNames());

        $this->assertNotEmpty($response);
    }

    private function getFieldNames()
    {
        return [
          'name', 'computer', 'height'
        ];
    }

    private function getArray()
    {
        return [
            [
                'name' => 'Dano',
                'computer' => 'mac',
                'height' => '6 foot 5',
                'title' => 'Developer'
            ],
            [
                'name' => 'Clint',
                'computer' => 'mac',
                'height' => '5 feet 10',
                'title' => 'Water Boy'
            ],
            [
                'name' => 'Tanner',
                'computer' => 'winows',
                'height' => '5 feet 11',
                'title' => 'Intern'
            ]
        ];
    }

    private function getObject()
    {
        return [
            (object)[
                'name' => 'Dano',
                'computer' => 'mac',
                'height' => '6 foot 5',
                'title' => 'Developer'
            ],
            (object)[
                'name' => 'Clint',
                'computer' => 'mac',
                'height' => '5 feet 10',
                'title' => 'Water Boy'
            ],
            (object)[
                'name' => 'Tanner',
                'computer' => 'winows',
                'height' => '5 feet 11',
                'title' => 'Intern'
            ]
        ];
    }
}
