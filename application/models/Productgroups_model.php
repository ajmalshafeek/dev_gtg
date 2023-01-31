<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Productgroups_model extends CI_Model
{
    var $table = 'gtg_product_groups';
    var $column_order = array(null, 'gtg_product_groups.id', null); //set column field database for datatable orderable
    var $column_search = array(null, 'gtg_product_groups.name', null);  //set column field database for datatable searchable
    var $order = array('gtg_product_groups.id' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query($id = '', $w = '', $sub = '')
    {
        $this->db->select('*');
        $this->db->from($this->table);
        if ($this->aauth->get_user()->loc) {

            $this->db->where('loc', $this->aauth->get_user()->loc);
            if (BDATA) $this->db->or_where('loc', 0);
        } elseif (!BDATA) {
            $this->db->where('loc', 0);
        }

        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
            $search = $this->input->post('search');
            $value = $search['value'];
            if ($value) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $value);
                } else {
                    $this->db->or_like($item, $value);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $search = $this->input->post('order');
        if ($search) // here order processing
        {
            $this->db->order_by($this->column_order[$search['0']['column']], $search['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($id = '', $w = '', $sub = '')
    {
        if ($id > 0) {
            $this->_get_datatables_query($id, $w, $sub);
        } else {
            $this->_get_datatables_query();
        }
        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($id, $w = '', $sub = '')
    {
        if ($id > 0) {
            $this->_get_datatables_query($id, $w, $sub);
        } else {
            $this->_get_datatables_query();
        }

        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);

        if ($this->aauth->get_user()->loc) {

            $this->db->where('loc', $this->aauth->get_user()->loc);
            if (BDATA) $this->db->or_where('loc', 0);
        } elseif (!BDATA) {
            $this->db->where('loc', 0);
        }
        return $this->db->count_all_results();
    }

    public function addnew($warehouse, $group_name, $taxrate, $v_stock, $products_l, $image, $disrate)
    {
        $ware_valid = $this->valid_warehouse($warehouse);


        if ($ware_valid['loc'] == $this->aauth->get_user()->loc or !$this->aauth->get_user()->loc) {

            $data = array(
                'name' => $group_name,
                'tax_rate' => $taxrate,
                'discount_rate' => $disrate,
                'image' => $image,
                'loc' => $this->aauth->get_user()->loc,
            );


            $this->db->trans_start();
            if ($this->db->insert('gtg_product_groups', $data)) {
                $pid = $this->db->insert_id();

                $this->aauth->applog("[New Product Group] -$group_name ID " . $pid, $this->aauth->get_user()->username);
                echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('ADDED') . "  <a href='add' class='btn btn-blue btn-lg'><span class='fa fa-plus-circle' aria-hidden='true'></span>  </a> <a href='" . base_url('productgroups') . "' class='btn btn-grey-blue btn-lg'><span class='fa fa-list-alt' aria-hidden='true'></span>  </a>"));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                $this->lang->line('ERROR')));
            }

            if (is_array($products_l)) {
                $data = array();
                foreach ($products_l as $key => $value) {
                    if (numberClean($v_stock[$key]) > 0.00) {
                        $data['group_id'] = $pid;
                        $data['qty'] = numberClean($v_stock[$key]);
                        $data['rid'] = $value;
                        $this->db->insert('gtg_group_relation', $data);
                    }
                }
            }

            $this->db->trans_complete();
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
            $this->lang->line('ERROR')));
        }
    }


    public function valid_warehouse($warehouse)
    {
        $this->db->select('id,loc');
        $this->db->from('gtg_warehouse');
        $this->db->where('id', $warehouse);
        $query = $this->db->get();
        $row = $query->row_array();
        return $row;
    }
}
