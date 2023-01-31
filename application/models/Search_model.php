<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Search_model extends CI_Model
{

    public function autoSearch($name)
    {


        $query = $this->db->query("SELECT pid,product_name,product_price FROM gtg_products WHERE UPPER(product_name) LIKE '" . strtoupper($name) . "%'");

        $result = $query->result_array();

        return $result;
    }
}
