<?php


if (!defined('BASEPATH')) exit('No direct script access allowed');

class Printer
{
    function __construct()
    {
        $this->PI = &get_instance();
    }

    function check($id = 0)
    {
        $this->PI->db->where('type', 1);
        $this->PI->db->where('val4', $id);
        $this->PI->db->order_by('id', 'DESC');
        $query = $this->PI->db->get('gtg_config');
        $result = $query->row_array();
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
}
