<?php

class Orders extends CI_Model
{
    /**
     * root of order XML document.
     */
    protected $xml = null;

    /**
     * holds the name of the order.
     */
    protected $order_name = null;

    // constructor
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * returns an array of all order names available.
     *
     * @return an array of all order names available.
     */
    public function all()
    {
        // get all the order files from the data directory
        $files = directory_map(DATAPATH);

        // filter out all files except for order files
        $orders = array();
        foreach ($files as $key => $file)
        {
            // don't try to parse file if extension is not xml
            $test = '.xml';
            if(substr_compare($file,$test,strlen($file)-strlen($test),
                strlen($test)) !== 0)
            {
                continue;
            }

            // parse file, and if it is an order, add it to array of orders
            try
            {
                $xml = simplexml_load_file(DATAPATH.$file);
                if($xml->getName() === 'order')
                {
                    // prepare the order
                    $order_file = $file;
                    $order_file = substr($order_file,0,strlen($order_file)-4);

                    // add the order to array of orders
                    $orders[] = $this->get($order_file);
                }
            }
            catch (Exception $e)
            {
                echo $e;
            }
        }

        return $orders;
    }

    /**
     * retrieves the order from the data directory, parses it, and returns an
     *   order object.
     *
     * @return returns an order object that has entity level methods.
     */
    public function get($order_name)
    {
        // parse the XML
        $this->xml = simplexml_load_file(DATAPATH.$order_name.'.xml');

        // save order name
        $this->order_name = $order_name;

        return clone $this;
    }

    public function get_order_name()
    {
        return $this->order_name;
    }

    public function get_order_type()
    {
        return $this->xml['type'];
    }

    public function get_customer_name()
    {
        return (string) $this->xml->customer[0];
    }

    public function get_notes()
    {
        $notes = array();
        foreach($this->xml->note as $note)
        {
            $notes[] = (string) $note;
        }
        return $notes;
    }

    public function get_burgers()
    {
        $burgers = array();
        foreach($this->xml->burger as $xml_burger)
        {
            $burgers[] = $this->burgers->get($xml_burger);
        }
        return $burgers;
    }

    public function get_total()
    {
        $total = 0;
        $burgers = $this->get_burgers();
        foreach($burgers as $burger)
        {
            $total += $burger->get_total();
        }
        return $total;
    }
}
