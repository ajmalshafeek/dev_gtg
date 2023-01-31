<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Invoices_model extends CI_Model
{
    var $table = 'gtg_invoices';
    var $column_order = array(null, 'tid', 'name', 'invoicedate', 'total', 'status', null);
    var $column_search = array('tid', 'name', 'invoicedate', 'total');
    var $order = array('tid' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }




    public function invoice_details($id)
    {
        $this->db->select('gtg_invoices.*,gtg_customers.*,gtg_invoices.loc as loc,gtg_invoices.id AS iid,gtg_customers.id AS cid,gtg_terms.id AS termid,gtg_terms.title AS termtit,gtg_terms.terms AS terms');
        $this->db->from($this->table);
        $this->db->where('gtg_invoices.id', $id);


        $this->db->join('gtg_customers', 'gtg_invoices.csd = gtg_customers.id', 'left');
        $this->db->join('gtg_terms', 'gtg_terms.id = gtg_invoices.term', 'left');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function invoice_products($id)
    {

        $this->db->select('*');
        $this->db->from('gtg_invoice_items');
        $this->db->where('tid', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function invoice_transactions($id)
    {

        $this->db->select('*');
        $this->db->from('gtg_transactions');
        $this->db->where('tid', $id);
        $this->db->where('ext', 0);
        $query = $this->db->get();
        return $query->result_array();
    }


    private function _get_datatables_query()
    {

        $this->db->select('gtg_invoices.id,gtg_invoices.tid,gtg_invoices.invoicedate,gtg_invoices.invoiceduedate,gtg_invoices.total,gtg_invoices.status,gtg_invoices.multi,gtg_customers.name');
        $this->db->from($this->table);
        $this->db->where('gtg_invoices.csd', $this->session->userdata('user_details')[0]->cid);
        //     $this->db->where('gtg_invoices.i_class');
        $this->db->join('gtg_customers', 'gtg_invoices.csd=gtg_customers.id', 'left');

        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {

        $this->_get_datatables_query();
        $this->db->where('gtg_invoices.csd', $this->session->userdata('user_details')[0]->cid);
        // $this->db->where('gtg_invoices.i_class', 0);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $this->db->where('gtg_invoices.csd', $this->session->userdata('user_details')[0]->cid);
        //     $this->db->where('gtg_invoices.i_class', 0);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        $this->db->where('gtg_invoices.csd', $this->session->userdata('user_details')[0]->cid);
        //      $this->db->where('gtg_invoices.i_class', 0);
        return $this->db->count_all_results();
    }


    public function billingterms()
    {
        $this->db->select('id,title');
        $this->db->from('gtg_terms');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function employee($id)
    {
        $this->db->select('gtg_employees.name,gtg_employees.sign,gtg_users.roleid');
        $this->db->from('gtg_employees');
        $this->db->where('gtg_employees.id', $id);
        $this->db->join('gtg_users', 'gtg_employees.id =gtg_users.id', 'left');
        $query = $this->db->get();
        return $query->row_array();
    }
}
