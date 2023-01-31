<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Chart_model extends CI_Model
{

    public function productcat($type, $c1 = '', $c2 = '')
    {
        switch ($type) {
            case 'week':
                $day1 = date("Y-m-d", strtotime(' - 7 days'));
                $day2 = date('Y-m-d');
                break;
            case 'month':
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
            case 'year':
                $day1 = date("Y-m-d", strtotime(' - 1 years'));
                $day2 = date('Y-m-d');
                break;

            case 'custom':
                $day1 = datefordatabase($c1);
                $day2 = datefordatabase($c2);
                break;

            default:
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
        }
        $this->db->select_sum('gtg_invoice_items.qty');
        $this->db->select_sum('gtg_invoice_items.subtotal');
        $this->db->select('gtg_invoice_items.pid');
        $this->db->select('gtg_product_cat.title');
        $this->db->from('gtg_invoice_items');
        $this->db->group_by('gtg_product_cat.id');
        $this->db->join('gtg_invoices', 'gtg_invoices.id = gtg_invoice_items.tid', 'left');
        $this->db->join('gtg_products', 'gtg_products.pid = gtg_invoice_items.pid', 'left');
        $this->db->join('gtg_product_cat', 'gtg_product_cat.id = gtg_products.pcat', 'left');
        $month = date('Y-m');
        $today = date('Y-m-d');
        $this->db->where('DATE(gtg_invoices.invoicedate) >=', $day1);
        $this->db->where('DATE(gtg_invoices.invoicedate) <=', $day2);
        if ($this->aauth->get_user()->loc) {
            $this->db->group_start();
            $this->db->where('gtg_invoices.loc', $this->aauth->get_user()->loc);
            if (BDATA) $this->db->or_where('gtg_invoices.loc', 0);
            $this->db->group_end();
        } elseif (!BDATA) {
            $this->db->where('gtg_invoices.loc', 0);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function trendingproducts($type, $c1 = '', $c2 = '')
    {
        switch ($type) {
            case 'week':
                $day1 = date("Y-m-d", strtotime(' - 7 days'));
                $day2 = date('Y-m-d');
                break;
            case 'month':
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
            case 'year':
                $day1 = date("Y-m-d", strtotime(' - 1 years'));
                $day2 = date('Y-m-d');
                break;

            case 'custom':
                $day1 = datefordatabase($c1);
                $day2 = datefordatabase($c2);
                break;

            default:
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
        }

        $this->db->select_sum('gtg_invoice_items.qty');
        $this->db->select('gtg_products.product_name');
        $this->db->from('gtg_invoice_items');
        $this->db->group_by('gtg_invoice_items.pid');
        $this->db->join('gtg_invoices', 'gtg_invoices.id = gtg_invoice_items.tid', 'left');
        $this->db->join('gtg_products', 'gtg_products.pid = gtg_invoice_items.pid', 'left');

        $this->db->where('DATE(gtg_invoices.invoicedate) >=', $day1);
        $this->db->where('DATE(gtg_invoices.invoicedate) <=', $day2);
        if ($this->aauth->get_user()->loc) {
            $this->db->group_start();
            $this->db->where('gtg_invoices.loc', $this->aauth->get_user()->loc);
            if (BDATA) $this->db->or_where('gtg_invoices.loc', 0);
            $this->db->group_end();
        } elseif (!BDATA) {
            $this->db->where('gtg_invoices.loc', 0);
        }
        $this->db->order_by('gtg_invoice_items.qty', 'DESC');
        $this->db->limit(100);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function profitchart($type, $c1 = '', $c2 = '')
    {
        switch ($type) {
            case 'week':
                $day1 = date("Y-m-d", strtotime(' - 7 days'));
                $day2 = date('Y-m-d');
                break;
            case 'month':
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
            case 'year':
                $day1 = date("Y-m-d", strtotime(' - 1 years'));
                $day2 = date('Y-m-d');
                break;

            case 'custom':
                $day1 = datefordatabase($c1);
                $day2 = datefordatabase($c2);
                break;

            default:
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
        }

        $this->db->select_sum('gtg_metadata.col1');
        $this->db->select('gtg_metadata.d_date');
        $this->db->from('gtg_metadata');
        $this->db->group_by('gtg_metadata.d_date');
        $month = date('Y-m');
        $today = date('Y-m-d');
        $this->db->where('DATE(gtg_metadata.d_date) >=', $day1);
        $this->db->where('DATE(gtg_metadata.d_date) <=', $day2);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function customerchart($type, $c1 = '', $c2 = '')
    {
        switch ($type) {
            case 'week':
                $day1 = date("Y-m-d", strtotime(' - 7 days'));
                $day2 = date('Y-m-d');
                break;
            case 'month':
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
            case 'year':
                $day1 = date("Y-m-d", strtotime(' - 1 years'));
                $day2 = date('Y-m-d');
                break;

            case 'custom':
                $day1 = datefordatabase($c1);
                $day2 = datefordatabase($c2);
                break;

            default:
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
        }
        $this->db->select_sum('gtg_invoices.total');
        $this->db->select('gtg_customers.name');
        $this->db->from('gtg_invoices');
        $this->db->group_by('gtg_invoices.csd');
        $this->db->join('gtg_customers', 'gtg_customers.id = gtg_invoices.csd', 'left');
        $month = date('Y-m');
        $today = date('Y-m-d');
        $this->db->where('DATE(gtg_invoices.invoicedate) >=', $day1);
        $this->db->where('DATE(gtg_invoices.invoicedate) <=', $day2);
        if ($this->aauth->get_user()->loc) {
            $this->db->group_start();
            $this->db->where('gtg_invoices.loc', $this->aauth->get_user()->loc);
            if (BDATA) $this->db->or_where('gtg_invoices.loc', 0);
            $this->db->group_end();
        } elseif (!BDATA) {
            $this->db->where('gtg_invoices.loc', 0);
        }
        $this->db->order_by('gtg_invoices.total', 'DESC');
        $this->db->limit(100);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }


    public function incomechart($type, $c1 = '', $c2 = '')
    {
        switch ($type) {
            case 'week':
                $day1 = date("Y-m-d", strtotime(' - 7 days'));
                $day2 = date('Y-m-d');
                break;
            case 'month':
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
            case 'year':
                $day1 = date("Y-m-d", strtotime(' - 1 years'));
                $day2 = date('Y-m-d');
                break;

            case 'custom':
                $day1 = datefordatabase($c1);
                $day2 = datefordatabase($c2);
                break;

            default:
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
        }
        $this->db->select_sum('credit');
        $this->db->select('date');
        $this->db->from('gtg_transactions');
        $this->db->group_by('date');
        $month = date('Y-m');
        $today = date('Y-m-d');
        $this->db->where('DATE(date) >=', $day1);
        $this->db->where('DATE(date) <=', $day2);
        $this->db->where('type', 'Income');
        if ($this->aauth->get_user()->loc) {
            $this->db->group_start();
            $this->db->where('loc', $this->aauth->get_user()->loc);
            if (BDATA) $this->db->or_where('loc', 0);
            $this->db->group_end();
        } elseif (!BDATA) {
            $this->db->where('loc', 0);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function expenseschart($type, $c1 = '', $c2 = '')
    {
        switch ($type) {
            case 'week':
                $day1 = date("Y-m-d", strtotime(' - 7 days'));
                $day2 = date('Y-m-d');
                break;
            case 'month':
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
            case 'year':
                $day1 = date("Y-m-d", strtotime(' - 1 years'));
                $day2 = date('Y-m-d');
                break;

            case 'custom':
                $day1 = datefordatabase($c1);
                $day2 = datefordatabase($c2);
                break;

            default:
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
        }
        $this->db->select_sum('debit');
        $this->db->select('date');
        $this->db->from('gtg_transactions');
        $this->db->group_by('date');
        $month = date('Y-m');
        $today = date('Y-m-d');
        $this->db->where('DATE(date) >=', $day1);
        $this->db->where('DATE(date) <=', $day2);
        $this->db->where('type', 'Expense');
        if ($this->aauth->get_user()->loc) {
            $this->db->group_start();
            $this->db->where('loc', $this->aauth->get_user()->loc);
            if (BDATA) $this->db->or_where('loc', 0);
            $this->db->group_end();
        } elseif (!BDATA) {
            $this->db->where('loc', 0);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function incexp($type, $c1 = '', $c2 = '')
    {
        switch ($type) {
            case 'week':
                $day1 = date("Y-m-d", strtotime(' - 7 days'));
                $day2 = date('Y-m-d');
                break;
            case 'month':
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
            case 'year':
                $day1 = date("Y-m-d", strtotime(' - 1 years'));
                $day2 = date('Y-m-d');
                break;

            case 'custom':
                $day1 = datefordatabase($c1);
                $day2 = datefordatabase($c2);
                break;

            default:
                $day1 = date("Y-m-d", strtotime(' - 30 days'));
                $day2 = date('Y-m-d');
                break;
        }
        $this->db->select_sum('debit');
        $this->db->select_sum('credit');
        $this->db->select('type');
        $this->db->from('gtg_transactions');
        $this->db->group_by('type');
        $month = date('Y-m');
        $today = date('Y-m-d');
        $this->db->where('DATE(date) >=', $day1);
        $this->db->where('DATE(date) <=', $day2);
        if ($this->aauth->get_user()->loc) {
            $this->db->group_start();
            $this->db->where('loc', $this->aauth->get_user()->loc);
            if (BDATA) $this->db->or_where('loc', 0);
            $this->db->group_end();
        } elseif (!BDATA) {
            $this->db->where('loc', 0);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
}
