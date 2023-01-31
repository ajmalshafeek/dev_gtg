<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Restservice_model extends CI_Model
{

    public function customers($id = '')
    {

        $this->db->select('*');
        $this->db->from('gtg_customers');
        if ($id != '') {

            $this->db->where('id', $id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function delete_customer($id)
    {
        return $this->db->delete('gtg_customers', array('id' => $id));
    }

    public function products($id = '')
    {

        $this->db->select('*');
        $this->db->from('gtg_products');
        if ($id != '') {

            $this->db->where('id', $id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function invoice($id)
    {
        $this->db->select('gtg_invoices.*,gtg_customers.*,gtg_invoices.id AS iid,gtg_customers.id AS cid,gtg_terms.id AS termid,gtg_terms.title AS termtit,gtg_terms.terms AS terms');
        $this->db->from('gtg_invoices');
        $this->db->where('gtg_invoices.id', $id);
        $this->db->join('gtg_customers', 'gtg_invoices.csd = gtg_customers.id', 'left');
        $this->db->join('gtg_terms', 'gtg_terms.id = gtg_invoices.term', 'left');
        $query = $this->db->get();
        $invoice = $query->row_array();
        $loc = location($invoice['loc']);
        $this->db->select('gtg_invoice_items.*');
        $this->db->from('gtg_invoice_items');
        $this->db->where('gtg_invoice_items.tid', $id);
        $query = $this->db->get();
        $items = $query->result_array();
        return array(array('invoice' => $invoice, 'company' => $loc, 'items' => $items, 'currency' => currency($invoice['loc'])));
    }
}
