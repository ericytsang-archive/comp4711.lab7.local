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
        $patty_code = (string) $this->xml->patty['type'];
        $patty = $this->menu->get_patty($patty_code);
        return $patty->name;
    }

    public function get_cheeses()
    {
        $cheeses = array(null,null);
        $cheese_index = 0;
        foreach($this->xml->children() as $child)
        {
            if($child->getName() === 'cheese')
            {
                $cheese_code = (string) $child['type'];
                $cheese = $this->menu->get_cheese($cheese_code);
                $cheeses[$cheese_index] = $cheese->name;
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
            $topping_code = (string) $topping['type'];
            $topping = $this->menu->get_topping($topping_code);
            $toppings[] = $topping->name;
        }
        return $toppings;
    }

    public function get_sauces()
    {
        $sauces = array();
        foreach($this->xml->sauce as $sauce)
        {
            $sauce_code = (string) $sauce['type'];
            $sauce = $this->menu->get_sauce($sauce_code);
            $sauces[] = $sauce->name;
        }
        return $sauces;
    }

    public function get_total()
    {
        $total = 0;
        foreach($this->xml->children() as $child)
        {
            if($child->getName() === 'patty')
            {
                $code = (string) $child['type'];
                $menu_item = $this->menu->get_patty($code);
                $total += $menu_item->price;
            }
            if($child->getName() === 'cheese')
            {
                $code = (string) $child['type'];
                $menu_item = $this->menu->get_cheese($code);
                $total += $menu_item->price;
            }
            if($child->getName() === 'topping')
            {
                $code = (string) $child['type'];
                $menu_item = $this->menu->get_topping($code);
                $total += $menu_item->price;
            }
            if($child->getName() === 'sauce')
            {
                $code = (string) $child['type'];
                $menu_item = $this->menu->get_sauce($code);
                $total += $menu_item->price;
            }
        }
        return $total;
    }
}
