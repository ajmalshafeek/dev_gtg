<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Categories_model extends CI_Model
{

    public function category_list($type = 0, $rel = 0)
    {
        $query = $this->db->query("SELECT id,title
FROM gtg_product_cat WHERE c_type='$type' AND rel_id='$rel'

ORDER BY id DESC");
        return $query->result_array();
    }

    public function warehouse_list()
    {
        $where = '';


        if (!BDATA) $where = "WHERE  (loc=0) ";
        if ($this->aauth->get_user()->loc) {
            $where = "WHERE  (loc=" . $this->aauth->get_user()->loc . " ) ";
            if (BDATA) $where = "WHERE  (loc=" . $this->aauth->get_user()->loc . " OR gtg_warehouse.loc=0) ";
        }


        $query = $this->db->query("SELECT id,title
FROM gtg_warehouse $where

ORDER BY id DESC");
        return $query->result_array();
    }

    public function category_stock()
    {
        $whr = '';
        if (!BDATA) $whr = "WHERE  (gtg_warehouse.loc=0) ";
        if ($this->aauth->get_user()->loc) {
            $whr = "WHERE  (gtg_warehouse.loc=" . $this->aauth->get_user()->loc . " ) ";
            if (BDATA) $whr = "WHERE  (gtg_warehouse.loc=" . $this->aauth->get_user()->loc . " OR gtg_warehouse.loc=0) ";
        }

        $query = $this->db->query("SELECT c.*,p.pc,p.salessum,p.worthsum,p.qty FROM gtg_product_cat AS c LEFT JOIN ( SELECT gtg_products.pcat,COUNT(gtg_products.pid) AS pc,SUM(gtg_products.product_price*gtg_products.qty) AS salessum, SUM(gtg_products.fproduct_price*gtg_products.qty) AS worthsum,SUM(gtg_products.qty) AS qty FROM gtg_products LEFT JOIN gtg_warehouse ON gtg_products.warehouse=gtg_warehouse.id  $whr GROUP BY gtg_products.pcat ) AS p ON c.id=p.pcat WHERE c.c_type=0");
        return $query->result_array();
    }

    public function category_sub_stock($id = 0)
    {
        $whr = '';
        if (!BDATA) $whr = "WHERE  (gtg_warehouse.loc=0) ";
        if ($this->aauth->get_user()->loc) {
            $whr = "WHERE  (gtg_warehouse.loc=" . $this->aauth->get_user()->loc . " ) ";
            if (BDATA) $whr = "WHERE  (gtg_warehouse.loc=" . $this->aauth->get_user()->loc . " OR gtg_warehouse.loc=0) ";
        }

        $whr2 = '';

        $query = $this->db->query("SELECT c.*,p.pc,p.salessum,p.worthsum,p.qty,p.sub_id FROM gtg_product_cat AS c LEFT JOIN ( SELECT gtg_products.sub_id,COUNT(gtg_products.pid) AS pc,SUM(gtg_products.product_price*gtg_products.qty) AS salessum, SUM(gtg_products.fproduct_price*gtg_products.qty) AS worthsum,SUM(gtg_products.qty) AS qty FROM gtg_products LEFT JOIN gtg_warehouse ON gtg_products.warehouse=gtg_warehouse.id  $whr GROUP BY gtg_products.sub_id ) AS p ON c.id=p.sub_id WHERE c.c_type=1 AND c.rel_id='$id'");
        return $query->result_array();
    }

    public function warehouse()
    {
        $where = '';
        if ($this->aauth->get_user()->loc) {
            $where = ' WHERE c.loc=' . $this->aauth->get_user()->loc;

            if (BDATA) $where = ' WHERE c.loc=' . $this->aauth->get_user()->loc . ' OR c.loc=0';
        } elseif (!BDATA) {
            $where = ' WHERE  c.loc=0';
        }
        $query = $this->db->query("SELECT c.*,p.pc,p.salessum,p.worthsum,p.qty FROM gtg_warehouse AS c LEFT JOIN ( SELECT warehouse,COUNT(pid) AS pc,SUM(product_price*qty) AS salessum, SUM(fproduct_price*qty) AS worthsum,SUM(qty) AS qty FROM  gtg_products GROUP BY warehouse ) AS p ON c.id=p.warehouse  $where");
        return $query->result_array();
    }

    public function cat_ware($id, $loc = 0)
    {
        $qj = '';
        if ($loc) $qj = "AND w.loc='$loc'";
        $query = $this->db->query("SELECT c.id AS cid, w.id AS wid,c.title AS catt,w.title AS watt FROM gtg_products AS p LEFT JOIN gtg_product_cat AS c ON p.pcat=c.id LEFT JOIN gtg_warehouse AS w ON p.warehouse=w.id WHERE
p.pid='$id' $qj ");
        return $query->row_array();
    }


    public function addnew($cat_name, $cat_desc, $cat_type = 0, $cat_rel = 0)
    {
        if (!$cat_type) $cat_type = 0;
        if (!$cat_rel) $cat_rel = 0;
        $data = array(
            'title' => $cat_name,
            'extra' => $cat_desc,
            'c_type' => $cat_type,
            'rel_id' => $cat_rel
        );

        if ($cat_type) {
            $url = "<a href='" . base_url('productcategory/add_sub') . "' class='btn btn-blue btn-lg'><span class='fa fa-plus-circle' aria-hidden='true'></span>  </a> <a href='" . base_url('productcategory/view?id=' . $cat_rel) . "' class='btn btn-grey-blue btn-lg'><span class='fa fa-list-alt' aria-hidden='true'></span>  </a>";
        } else {
            $url = "<a href='" . base_url('productcategory/add') . "' class='btn btn-blue btn-lg'><span class='fa fa-plus-circle' aria-hidden='true'></span>  </a> <a href='" . base_url('productcategory') . "' class='btn btn-grey-blue btn-lg'><span class='fa fa-list-alt' aria-hidden='true'></span>  </a>";
        }

        if ($this->db->insert('gtg_product_cat', $data)) {
            $this->aauth->applog("[Category Created] $cat_name ID " . $this->db->insert_id(), $this->aauth->get_user()->username);
            echo json_encode(array('status' => 'Success', 'message' =>
            $this->lang->line('ADDED') . " $url"));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
            $this->lang->line('ERROR')));
        }
    }

    public function addwarehouse($cat_name, $cat_desc, $lid)
    {
        $data = array(
            'title' => $cat_name,
            'extra' => $cat_desc,
            'loc' => $lid
        );

        if ($this->db->insert('gtg_warehouse', $data)) {
            $this->aauth->applog("[WareHouse Created] $cat_name ID " . $this->db->insert_id(), $this->aauth->get_user()->username);
            $url = "<a href='" . base_url('productcategory/addwarehouse') . "' class='btn btn-blue btn-lg'><span class='fa fa-plus-circle' aria-hidden='true'></span>  </a> <a href='" . base_url('productcategory/warehouse') . "' class='btn btn-grey-blue btn-lg'><span class='fa fa-list-alt' aria-hidden='true'></span>  </a>";
            echo json_encode(array('status' => 'Success', 'message' =>
            $this->lang->line('ADDED') . $url));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
            $this->lang->line('ERROR')));
        }
    }

    public function edit($catid, $product_cat_name, $product_cat_desc, $cat_type, $cat_rel, $old_cat_type)
    {
        if (!$cat_rel) $cat_rel = 0;
        $data = array(
            'title' => $product_cat_name,
            'extra' => $product_cat_desc,
            'c_type' => $cat_type,
            'rel_id' => $cat_rel
        );
        $this->db->set($data);
        $this->db->where('id', $catid);
        if ($this->db->update('gtg_product_cat')) {
            if ($cat_type != $old_cat_type && $cat_type && $cat_type) {
                $data = array('pcat' => $cat_rel);
                $this->db->set($data);
                $this->db->where('sub_id', $catid);
                $this->db->update('gtg_products');
            }
            $this->aauth->applog("[Category Edited] $product_cat_name ID " . $catid, $this->aauth->get_user()->username);
            echo json_encode(array('status' => 'Success', 'message' =>
            $this->lang->line('UPDATED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
            $this->lang->line('ERROR')));
        }
    }

    public function editwarehouse($catid, $product_cat_name, $product_cat_desc, $lid)
    {
        $data = array(
            'title' => $product_cat_name,
            'extra' => $product_cat_desc,
            'loc' => $lid
        );


        $this->db->set($data);
        $this->db->where('id', $catid);

        if ($this->db->update('gtg_warehouse')) {
            $this->aauth->applog("[Warehouse Edited] $product_cat_name ID " . $catid, $this->aauth->get_user()->username);
            echo json_encode(array('status' => 'Success', 'message' =>
            $this->lang->line('UPDATED')));
        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
            $this->lang->line('ERROR')));
        }
    }

    public function sub_cat($id = 0)
    {
        $this->db->select('*');
        $this->db->from('gtg_product_cat');
        $this->db->where('rel_id', $id);
        $this->db->where('c_type', 1);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function sub_cat_curr($id = 0)
    {
        $this->db->select('*');
        $this->db->from('gtg_product_cat');
        $this->db->where('id', $id);
        $this->db->where('c_type', 1);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function sub_cat_list($id = 0)
    {
        $this->db->select('*');
        $this->db->from('gtg_product_cat');
        $this->db->where('rel_id', $id);
        $this->db->where('c_type', 1);
        $query = $this->db->get();
        return $query->result_array();
    }
}
