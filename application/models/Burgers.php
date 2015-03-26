<?php

class Burgers extends CI_Model
{
    /**
     * holds the root element of a SimpleXMLElement for a burger of an order.
     */
    protected $xml;

    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * retrieves the burger from the data path.
     *
     * @param  $xml_burger SimpleXMLElement root of a burger.
     *
     * @return a buger object representing the burger described by
     *   {$xml_burger}.
     */
    public function get($xml_burger)
    {
        $this->xml = $xml_burger;
        return clone $this;
    }

    /**
     * returns the name of the burger.
     *
     * @return the name of the burger.
     */
    public function get_name()
    {
        return $this->xml->name;
    }

    /**
     * returns the name of the patty being used in this burger.
     *
     * @return the name of the patty being used in this burger.
     */
    public function get_patty()
    {
        $patty_code = (string) $this->xml->patty['type'];
        $patty = $this->menu->get_patty($patty_code);
        return $patty->name;
    }

    /**
     * returns an array of size 2 that has the cheeses for this burger. elements
     *   of the array may be empty, meaning there is no cheese there. the zeroth
     *   element is the bottom cheese, and the first element is the top cheese.
     *
     * @return an array of size 2 that has the cheeses for this burger. elements
     *   of the array may be empty, meaning there is no cheese there. the zeroth
     *   element is the bottom cheese, and the first element is the top cheese.
     */
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

    /**
     * returns all the topping names used in this berger in an array of strings.
     *
     * @return all the sauce topping used in this berger in an array of strings.
     */
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

    /**
     * returns all the sauce names used in this berger in an array of strings.
     *
     * @return all the sauce names used in this berger in an array of strings.
     */
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

    /**
     * calculates and returns the total amount needed to purchase this burger.
     *
     * @return the total amount needed to purchase this burger.
     */
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
