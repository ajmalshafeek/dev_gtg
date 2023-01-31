<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Clientgroup_model extends CI_Model
{


    public function details($id)
    {

        $this->db->select('*');
        $this->db->from('gtg_cust_group');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function recipients($id)
    {

        $this->db->select('id,name,email');
        $this->db->from('gtg_customers');
        $this->db->where('gid', $id);
        $query = $this->db->get();
        return $query->result_array();
    }


    public function add($group_name, $group_desc)
    {
        $data = array(
            'title' => $group_name,
            'summary' => $group_desc
        );

        if ($this->db->insert('gtg_cust_group', $data)) {
            $this->aauth->applog("[Group Created] $group_name ID " . $this->db->insert_id(), $this->aauth->get_user()->username);
            echo json_encode(array('status' => 'Success', 'message' =>
            $this->lang->line('ADDED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
            $this->lang->line('ERROR')));
        }
    }


    public function editgroupupdate($gid, $group_name, $group_desc)
    {
        $data = array(
            'title' => $group_name,
            'summary' => $group_desc
        );


        $this->db->set($data);
        $this->db->where('id', $gid);

        if ($this->db->update('gtg_cust_group')) {
            $this->aauth->applog("[Group updated] $group_name ID " . $gid, $this->aauth->get_user()->username);
            echo json_encode(array('status' => 'Success', 'message' =>
            $this->lang->line('UPDATED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
            $this->lang->line('ERROR')));
        }
    }

    public function editgroupdiscountupdate($gid, $disc_rate)
    {
        $data = array(
            'disc_rate' => $disc_rate
        );
        $this->db->set($data);
        $this->db->where('id', $gid);

        if ($this->db->update('gtg_cust_group')) {

            $data = array(
                'discount_c' => $disc_rate
            );
            $this->db->set($data);
            $this->db->where('gid', $gid);
            $this->db->update('gtg_customers');

            $this->aauth->applog("[Group discount updated] %" . $disc_rate . " GID-" . $gid, $this->aauth->get_user()->username);
            echo json_encode(array('status' => 'Success', 'message' =>
            $this->lang->line('UPDATED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
            $this->lang->line('ERROR')));
        }
    }
}
