<?php

class Burgers extends CI_Model
{
    /**
     * holds the root element of a SimpleXMLElement for a burger of an order.
     */
    protected $xml;

    // constructor
    public function __construct()
    {
        parent::__construct();
    }

    public function get($xml_burger)
    {
        $this->xml = $xml_burger;
        return clone $this;
    }

    public function get_name()
    {
        return $this->xml->name;
    }

    public function get_patty()
    {
        return $this->xml->patty['type'];
    }

    public function get_cheeses()
    {
        $cheeses = array(null,null);
        $cheese_index = 0;
        foreach($this->xml->children() as $child)
        {
            if($child->getName() === 'cheese')
            {
                $cheeses[$cheese_index] = $child['type'];
            }
            if($child->getName() === 'patty')
            {
                $cheese_index = 1;
            }
        }
        return $cheeses;
    }

    public function get_toppings()
    {
        $toppings = array();
        foreach($this->xml->topping as $topping)
        {
            $toppings[] = $topping['type'];
        }
        return $toppings;
    }

    public function get_sauces()
    {
        $sauces = array();
        foreach($this->xml->sauce as $sauce)
        {
            $sauces[] = $sauce['type'];
        }
        return $sauces;
    }
}
