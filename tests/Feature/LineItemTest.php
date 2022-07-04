<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\LineItem;

class LineItemTest extends TestCase
{
    public function test_line_item_default()
    {
        $inputLine = [
            'item_id' => 1,
            'description' => 'desc_1',
            'unit_price' => 12,
            'qty' => 1,
            'discount_type' => 'percent',
            'discount_value' => 20,
            'tax_percent' => 20
        ];

        $line = new LineItem($inputLine);

        $this->assertEquals(12, $line->subTotal());
        $this->assertEquals(2.4, $line->discountAmount());
        $this->assertEquals(9.6, $line->netPrice());
        $this->assertEquals(1.92, $line->taxAmount());
        $this->assertEquals(11.52, $line->totalAmount());
    }

    public function test_line_item_default_2()
    {
        $inputLine = [
            'item_id' => 1,
            'description' => 'desc_1',
            'unit_price' => 10,
            'qty' => 1,
            'discount_type' => 'percent',
            'discount_value' => 20,
            'tax_percent' => 20
        ];

        $line = new LineItem($inputLine);

        $this->assertEquals(10, $line->subTotal());
        $this->assertEquals(2, $line->discountAmount());
        $this->assertEquals(8, $line->netPrice());
        $this->assertEquals(1.6, $line->taxAmount());
        $this->assertEquals(9.6, $line->totalAmount());
    }

    public function test_line_item_tax_type_exclusive_discount_value()
    {
        $inputLine = [
            'item_id' => 1,
            'description' => 'desc_1',
            'unit_price' => 100,
            'qty' => 1,
            'discount_type' => 'value',
            'discount_value' => 5,
            'tax_percent' => 10
        ];

        $document = [
            'tax_type' => 'exclusive'
        ];

        $line = new LineItem($inputLine, $document);

        $this->assertEquals(100, $line->subTotal());
        $this->assertEquals(5, $line->discountAmount());
        $this->assertEquals(95, $line->netPrice());
        $this->assertEquals(9.5, $line->taxAmount());
        $this->assertEquals(104.5, $line->totalAmount());
    }

    public function test_line_item_tax_type_exclusive()
    {
        $inputLine = [
            'item_id' => 1,
            'description' => 'desc_1',
            'unit_price' => 100,
            'qty' => 1,
            'discount_type' => 'percent',
            'discount_value' => 5,
            'tax_percent' => 10
        ];

        $document = [
            'tax_type' => 'exclusive'
        ];

        $line = new LineItem($inputLine, $document);

        $this->assertEquals(100, $line->subTotal());
        $this->assertEquals(5, $line->discountAmount());
        $this->assertEquals(95, $line->netPrice());
        $this->assertEquals(9.5, $line->taxAmount());
        $this->assertEquals(104.5, $line->totalAmount());
    }

    public function test_line_item_tax_type_inclusive()
    {
        $inputLine = [
            'item_id' => 1,
            'description' => 'desc_1',
            'unit_price' => 100,
            'qty' => 1,
            'discount_type' => 'percent',
            'discount_value' => 5,
            'tax_percent' => 10
        ];

        $document = [
            'tax_type' => 'inclusive'
        ];

        $line = new LineItem($inputLine, $document);

        $this->assertEquals(100, $line->subTotal());
        $this->assertEquals(5, $line->discountAmount());
        $this->assertEquals(86.36, $line->netPrice());
        $this->assertEquals(8.64, $line->taxAmount());
        $this->assertEquals(95, $line->totalAmount());
    }
}
