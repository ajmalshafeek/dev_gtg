<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Export_model extends CI_Model
{


    public function customers()
    {


        $this->db->select('*');
        $this->db->from('gtg_customers');

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
}
