<?php

namespace App;


class LineItem
{
    /**
     * @var array
     */
    protected $input = [];

    /**
     * @var array
     */
    protected $document = [];

    /**
     * Construct the box with the given input.
     *
     * @param array $input
     */
    public function __construct($input = [], $document = [])
    {
        $this->input = $input;
        $this->document = $document;
    }

    /**
     * @return float
     */
    public function subTotal()
    {
        $data = $this->input;
        return round(($data['unit_price'] * $data['qty']), 2);
    }

    /**
     * @return float
     */
    public function discountAmount()
    {
        $data = $this->input;

        switch ($data['discount_type']) {
            case 'percent':
                return round((($this->subTotal() * $data['discount_value']) / 100), 2);
                break;
            
            case 'value':
                return round($data['discount_value']);
                break;
        }
    }

    /**
     * @return float
     */
    public function netPrice()
    {
        $data = $this->input;
        $document = $this->document;

        if ($document && $document['tax_type'] == 'inclusive') {
            return round((($this->subTotal() - $this->discountAmount()) - (($this->subTotal() - $this->discountAmount()) / (1 + $data['tax_percent']))), 2);
        }
        return round(($this->subTotal() - $this->discountAmount()), 2);
    }

    /**
     * @return float
     */
    public function taxAmount()
    {
        $data = $this->input;

        return round((($this->netPrice() * $data['tax_percent']) / 100), 2);
    }

    /**
     * @return float
     */
    public function totalAmount()
    {
        return round(($this->netPrice() + $this->taxAmount()), 2);
    }
}
