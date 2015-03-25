<?php

/**
 * Our homepage. Show the most recently added quote.
 *
 * controllers/Welcome.php
 *
 * ------------------------------------------------------------------------
 */
class Welcome extends Application {

    function __construct()
    {
    parent::__construct();
    }

    //-------------------------------------------------------------
    //  Homepage: show a list of the orders on file
    //-------------------------------------------------------------

    function index()
    {
        // get all the order files from the data directory
        $files = directory_map('./data/');

        // filter out all files except for order files
        $order_files = array();
        foreach ($files as $key => $file)
        {
            $test = '.xml';
            if(substr_compare($file,$test,strlen($file)-strlen($test),
                strlen($test)) === 0 && $file !== 'menu.xml')
            {
                // prepare the order
                $order_file = $file;
                $order_file = substr($order_file,0,strlen($order_file)-4);

                // add the order to array of orders
                $order_files[] = $order_file;
            }
        }

        // build a list of orders
        $orders = array();
        foreach($order_files as $key => $order_file)
        {
            // build the order
            $order = new stdclass();
            $order->order = $order_file;
            $order->url   = 'welcome/order/'.$order_file;

            // add the order to array of orders
            $orders[] = $order;
        }
        $this->data['orders'] = $orders;

        // Present the list to choose from
        $this->data['pagebody'] = 'homepage';
        $this->render();
    }

    //-------------------------------------------------------------
    //  Show the "receipt" for a specific order
    //-------------------------------------------------------------

    function order($filename)
    {
        // Build a receipt for the chosen order

        // Present the list to choose from
        $this->data['pagebody'] = 'justone';
        $this->render();
    }


}
