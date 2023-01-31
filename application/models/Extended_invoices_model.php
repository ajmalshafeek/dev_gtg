<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Extended_invoices_model extends CI_Model
{
    var $table = 'gtg_invoice_items';
    var $column_order = array(null, 'gtg_invoices.tid', 'gtg_customers.name', 'gtg_invoices.invoicedate', 'gtg_invoice_items.subtotal', 'gtg_invoice_items.qty', 'gtg_invoice_items.discount', 'gtg_invoice_items.tax');
    var $column_search = array('gtg_invoices.tid', 'gtg_customers.name', 'gtg_invoices.invoicedate', 'gtg_invoice_items.subtotal', 'gtg_invoice_items.qty', 'gtg_invoice_items.tax');
    var $order = array('gtg_invoices.tid' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }






    private function _get_datatables_query($opt = '')
    {
        $this->db->select('gtg_invoices.id,gtg_invoices.tid,gtg_invoices.invoicedate,gtg_invoices.invoiceduedate,gtg_invoice_items.subtotal,gtg_invoice_items.qty,gtg_invoice_items.product,gtg_invoice_items.discount,gtg_invoice_items.tax,gtg_customers.name');
        $this->db->from($this->table);
        //$this->db->where('gtg_invoices.i_class', 1);
        $this->db->where('gtg_invoices.status !=', 'canceled');
        if ($opt) {
            $this->db->where('gtg_invoices.eid', $opt);
        }
        if ($this->input->post('start_date') && $this->input->post('end_date')) // if datatable send POST for search
        {
            $this->db->where('DATE(gtg_invoices.invoicedate) >=', datefordatabase($this->input->post('start_date')));
            $this->db->where('DATE(gtg_invoices.invoicedate) <=', datefordatabase($this->input->post('end_date')));
        }
        if ($this->aauth->get_user()->loc) {
            $this->db->where('gtg_invoices.loc', $this->aauth->get_user()->loc);
        } elseif (!BDATA) {
            $this->db->where('gtg_invoices.loc', 0);
        }
        $this->db->join('gtg_invoices', 'gtg_invoices.id=gtg_invoice_items.tid', 'left');
        $this->db->join('gtg_customers', 'gtg_invoices.csd=gtg_customers.id', 'left');

        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
            if ($this->input->post('search')['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $this->input->post('search')['value']);
                } else {
                    $this->db->or_like($item, $this->input->post('search')['value']);
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

    function get_datatables($opt = '')
    {
        $this->_get_datatables_query($opt);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);

        $query = $this->db->get();

        //  $this->db->join('gtg_invoices', 'gtg_invoices.id=gtg_invoice_items.tid', 'left');
        return $query->result();
    }

    function count_filtered($opt = '')
    {
        $this->_get_datatables_query($opt);
        if ($opt) {
            $this->db->where('eid', $opt);
        }

        //       $this->db->join('gtg_invoices', 'gtg_invoices.id=gtg_invoice_items.tid', 'left');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($opt = '')
    {
        $this->db->select('gtg_invoice_items.id');
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
}
