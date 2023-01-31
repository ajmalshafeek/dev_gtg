<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Message_model extends CI_Model
{


    public function employee_details($id)
    {

        $this->db->select('gtg_employees.*');
        $this->db->from('gtg_employees');
        $this->db->where('gtg_pms.id', $id);
        $this->db->join('gtg_pms', 'gtg_employees.id = gtg_pms.sender_id', 'left');
        $query = $this->db->get();
        return $query->row_array();
    }
}
