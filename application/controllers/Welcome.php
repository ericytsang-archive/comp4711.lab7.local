<?php

/**
 * Our homepage. Show the most recently added quote.
 *
 * controllers/Welcome.php
 *
 * ------------------------------------------------------------------------
 */
class Welcome extends MY_Controller {

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
        $files = directory_map(DATAPATH);

        // filter out all files except for order files
        $orders = $this->orders->all();

        // build a list of orders
        $view_orders = array();
        foreach($orders as $order)
        {
            // build the order
            $ordr = new stdclass();
            $ordr->order    = $order->get_order_name();
            $ordr->url      = 'welcome/order/'.$order->get_order_name();
            $ordr->customer = $order->get_customer_name();

            // add the order to array of orders
            $view_orders[] = $ordr;
        }
        $this->data['orders'] = $view_orders;

        // Present the list to choose from
        $this->data['pagebody'] = 'homepage';
        $this->render();
    }

    //-------------------------------------------------------------
    //  Show the "receipt" for a specific order
    //-------------------------------------------------------------

    function order($filename)
    {
        // build a receipt for the chosen order
        $order = $this->orders->get($filename);

        // prepare burgers for the view
        $order_burgers = $order->get_burgers();
        $view_burgers = array();
        foreach($order_burgers as $key => $burger)
        {
            // prepare the burger
            $bgr = new stdclass();
            $bgr->num      = $key+1;
            $bgr->patty    = $burger->get_patty();
            $bgr->cheeses  = $burger->get_cheeses();
            $bgr->toppings = to_comma_list($burger->get_toppings());
            $bgr->sauces   = to_comma_list($burger->get_sauces());

            // post processing for burger cheese
            if($bgr->cheeses[0] !== null)
                $bgr->cheeses[0] .= ' (bottom)';
            if($bgr->cheeses[1] !== null)
                $bgr->cheeses[1] .= ' (top)';
            $bgr->cheeses = to_comma_list($bgr->cheeses);

            if($bgr->cheeses !== '')
            {
                $colon_li_params = array();
                $colon_li_params['label'] = 'Cheeses';
                $colon_li_params['text']  = $bgr->cheeses;
                $bgr->cheeses = $this->parser->parse('colon_li',$colon_li_params,true);
            }

            // add the burger to list of burgers
            $view_burgers[] = $bgr;
        }

        // inject view parameters for justone
        $this->data['order_name'] = $order->get_order_name();
        $this->data['customer']   = $order->get_customer_name();
        $this->data['order_type'] = $order->get_order_type();
        $this->data['burgers']    = $view_burgers;

        // present the list to choose from
        $this->data['pagebody'] = 'justone';
        $this->render();
    }


}
