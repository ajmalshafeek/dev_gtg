<?php

defined('BASEPATH') or exit('No direct script access allowed');


class Printer_model extends CI_Model
{
    var $table = 'gtg_config';

    public function __construct()
    {
        parent::__construct();
    }

    public function printers_list()
    {
        $this->db->select('*');
        $this->db->from('gtg_config');
        $this->db->where('type', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function printer_details($id)
    {
        $this->db->select('*');
        $this->db->from('gtg_config');
        $this->db->where('id', $id);
        $this->db->where('type', 1);
        $query = $this->db->get();
        return $query->row_array();
    }


    public function create($p_name, $p_type, $p_connect, $lid, $mode)
    {
        $data = array(
            'type' => 1,
            'val1' => $p_name,
            'val2' => $p_type,
            'val3' => $p_connect,
            'val4' => $lid,
            'other' => $mode
        );
        if ($this->db->insert('gtg_config', $data)) {
            echo json_encode(array('status' => 'Success', 'message' =>
            $this->lang->line('ADDED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
            $this->lang->line('ERROR')));
        }
    }

    public function edit($id, $p_name, $p_type, $p_connect, $lid, $mode)
    {
        $data = array(
            'type' => 1,
            'val1' => $p_name,
            'val2' => $p_type,
            'val3' => $p_connect,
            'val4' => $lid,
            'other' => $mode
        );


        $this->db->set($data);
        $this->db->where('id', $id);

        if ($this->db->update('gtg_config')) {
            echo json_encode(array('status' => 'Success', 'message' =>
            $this->lang->line('UPDATED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
            $this->lang->line('ERROR')));
        }
    }
}
