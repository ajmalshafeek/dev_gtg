<?php



defined('BASEPATH') or exit('No direct script access allowed');

class Pos_invoices_model extends CI_Model
{
    var $table = 'gtg_invoices';
    var $column_order = array(null, 'gtg_invoices.tid', 'gtg_customers.name', 'gtg_invoices.invoicedate', 'gtg_invoices.total', 'gtg_invoices.status', null);
    var $column_search = array('gtg_invoices.tid', 'gtg_customers.name', 'gtg_invoices.invoicedate', 'gtg_invoices.total', 'gtg_invoices.status');
    var $order = array('gtg_invoices.tid' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    public function lastinvoice()
    {
        $this->db->select('tid');
        $this->db->from($this->table);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $this->db->where('i_class', 1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->tid;
        } else {
            return 1000;
        }
    }


    public function invoice_details($id, $eid = '', $loc = null)
    {

        $this->db->select('gtg_invoices.*, SUM(gtg_invoices.shipping + gtg_invoices.ship_tax) AS shipping,gtg_customers.*,gtg_invoices.loc as loc,gtg_invoices.id AS iid,gtg_customers.id AS cid,gtg_terms.id AS termid,gtg_terms.title AS termtit,gtg_terms.terms AS terms');
        $this->db->from($this->table);
        $this->db->where('gtg_invoices.id', $id);
        if ($eid) {
            $this->db->where('gtg_invoices.eid', $eid);
        }
        if (@$this->aauth->get_user()->loc) {
            $this->db->where('gtg_invoices.loc', $this->aauth->get_user()->loc);
        } elseif (!BDATA and !$loc) {
            $this->db->where('gtg_invoices.loc', 0);
        }
        if ($loc) {
            $this->db->where('gtg_invoices.loc', $loc);
        }
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

    public function currencies()
    {

        $this->db->select('*');
        $this->db->from('gtg_currencies');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function currency_d($id)
    {
        $this->db->select('*');
        $this->db->from('gtg_currencies');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function warehouses()
    {
        $this->db->select('*');
        $this->db->from('gtg_warehouse');
        if ($this->aauth->get_user()->loc) {
            $this->db->where('loc', $this->aauth->get_user()->loc);
            if (BDATA)  $this->db->or_where('loc', 0);
        } elseif (!BDATA) {
            $this->db->where('loc', 0);
        }

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


    public function items_with_product($id)
    {

        $this->db->select('gtg_invoice_items.*,gtg_products.qty AS alert');
        $this->db->from('gtg_invoice_items');
        $this->db->where('tid', $id);
        $this->db->join('gtg_products', 'gtg_products.pid = gtg_invoice_items.pid', 'left');
        $query = $this->db->get();
        return $query->result_array();
    }


    public function invoice_delete($id, $eid = '')
    {

        $this->db->trans_start();

        $this->db->select('status');
        $this->db->from('gtg_invoices');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->row_array();

        if ($this->aauth->get_user()->loc) {
            if ($eid) {

                $res = $this->db->delete('gtg_invoices', array('id' => $id, 'eid' => $eid, 'loc' => $this->aauth->get_user()->loc));
            } else {
                $res = $this->db->delete('gtg_invoices', array('id' => $id, 'loc' => $this->aauth->get_user()->loc));
            }
        } else {
            if (BDATA) {
                if ($eid) {

                    $res = $this->db->delete('gtg_invoices', array('id' => $id, 'eid' => $eid));
                } else {
                    $res = $this->db->delete('gtg_invoices', array('id' => $id));
                }
            } else {


                if ($eid) {

                    $res = $this->db->delete('gtg_invoices', array('id' => $id, 'eid' => $eid, 'loc' => 0));
                } else {
                    $res = $this->db->delete('gtg_invoices', array('id' => $id, 'loc' => 0));
                }
            }
        }
        $affect = $this->db->affected_rows();
        if ($res) {
            if ($result['status'] != 'canceled') {
                $this->db->select('pid,qty');
                $this->db->from('gtg_invoice_items');
                $this->db->where('tid', $id);
                $query = $this->db->get();
                $prevresult = $query->result_array();
                foreach ($prevresult as $prd) {
                    $amt = $prd['qty'];
                    $this->db->set('qty', "qty+$amt", FALSE);
                    $this->db->where('pid', $prd['pid']);
                    $this->db->update('gtg_products');
                }
            }
            if ($affect) $this->db->delete('gtg_invoice_items', array('tid' => $id));
            $data = array('type' => 9, 'rid' => $id);
            $this->db->delete('gtg_metadata', $data);
            if ($this->db->trans_complete()) {
                return true;
            } else {
                return false;
            }
        }
    }


    private function _get_datatables_query($opt = '')
    {
        $this->db->select('gtg_invoices.id,gtg_invoices.tid,gtg_invoices.invoicedate,gtg_invoices.invoiceduedate,gtg_invoices.total,gtg_invoices.status,gtg_customers.name');
        $this->db->from($this->table);
        $this->db->where('gtg_invoices.i_class', 1);
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
        $this->db->where('gtg_invoices.i_class', 1);
        if ($this->aauth->get_user()->loc) {
            $this->db->where('gtg_invoices.loc', $this->aauth->get_user()->loc);
        } elseif (!BDATA) {
            $this->db->where('gtg_invoices.loc', 0);
        }
        return $query->result();
    }

    function count_filtered($opt = '')
    {
        $this->_get_datatables_query($opt);
        if ($opt) {
            $this->db->where('eid', $opt);
        }
        if ($this->aauth->get_user()->loc) {
            $this->db->where('gtg_invoices.loc', $this->aauth->get_user()->loc);
        } elseif (!BDATA) {
            $this->db->where('gtg_invoices.loc', 0);
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($opt = '')
    {
        $this->db->select('gtg_invoices.id');
        $this->db->from($this->table);
        $this->db->where('gtg_invoices.i_class', 1);
        if ($opt) {
            $this->db->where('gtg_invoices.eid', $opt);
        }
        if ($this->aauth->get_user()->loc) {
            $this->db->where('gtg_invoices.loc', $this->aauth->get_user()->loc);
        } elseif (!BDATA) {
            $this->db->where('gtg_invoices.loc', 0);
        }
        return $this->db->count_all_results();
    }


    public function billingterms()
    {
        $this->db->select('id,title');
        $this->db->from('gtg_terms');
        $this->db->where('type', 1);
        $this->db->or_where('type', 0);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function employee($id)
    {
        $this->db->select('gtg_employees.name,gtg_employees.sign,gtg_users.roleid');
        $this->db->from('gtg_employees');
        $this->db->where('gtg_employees.id', $id);
        $this->db->join('gtg_users', 'gtg_employees.id = gtg_users.id', 'left');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function meta_insert($id, $type, $meta_data)
    {

        $data = array('type' => $type, 'rid' => $id, 'col1' => $meta_data);
        if ($id) {
            return $this->db->insert('gtg_metadata', $data);
        } else {
            return 0;
        }
    }

    public function attach($id)
    {
        $this->db->select('gtg_metadata.*');
        $this->db->from('gtg_metadata');
        $this->db->where('gtg_metadata.type', 1);
        $this->db->where('gtg_metadata.rid', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function meta_delete($id, $type, $name)
    {
        if (@unlink(FCPATH . 'userfiles/attach/' . $name)) {
            return $this->db->delete('gtg_metadata', array('rid' => $id, 'type' => $type, 'col1' => $name));
        }
    }

    public function gateway_list($enable = '')
    {

        $this->db->from('gtg_gateways');
        if ($enable == 'Yes') {
            $this->db->where('enable', 'Yes');
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function drafts()
    {


        $this->db->select('gtg_draft.id,gtg_draft.tid,gtg_draft.invoicedate');
        $this->db->from('gtg_draft');
        $this->db->where('gtg_draft.loc', $this->aauth->get_user()->loc);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(12);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function draft_products($id)
    {

        $this->db->select('*');
        $this->db->from('gtg_draft_items');
        $this->db->where('tid', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function draft_details($id, $eid = '')
    {

        $this->db->select('gtg_draft.*,SUM(gtg_draft.shipping + gtg_draft.ship_tax) AS shipping,gtg_customers.*,gtg_customers.id AS cid,gtg_draft.id AS iid,gtg_terms.id AS termid,gtg_terms.title AS termtit,gtg_terms.terms AS terms');
        $this->db->from('gtg_draft');
        $this->db->where('gtg_draft.id', $id);
        if ($eid) {
            $this->db->where('gtg_draft.eid', $eid);
        }
        $this->db->join('gtg_customers', 'gtg_draft.csd = gtg_customers.id', 'left');
        $this->db->join('gtg_terms', 'gtg_terms.id = gtg_draft.term', 'left');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function accountslist()
    {
        $this->db->select('*');
        $this->db->from('gtg_accounts');

        if ($this->aauth->get_user()->loc) {
            $this->db->where('loc', $this->aauth->get_user()->loc);
            if (BDATA) $this->db->or_where('loc', 0);
        } else {
            if (!BDATA) $this->db->where('loc', 0);
        }

        $query = $this->db->get();
        return $query->result_array();
    }
}
