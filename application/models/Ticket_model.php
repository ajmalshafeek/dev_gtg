<?php



defined('BASEPATH') or exit('No direct script access allowed');

class Ticket_model extends CI_Model
{


    //documents list

    var $doccolumn_order = array(null, 'subject', 'created', null);
    var $doccolumn_search = array('subject', 'created');


    public function thread_list($id)
    {
        $this->db->select('gtg_tickets_th.*,gtg_customers.name AS custo,gtg_employees.name AS emp');
        $this->db->from('gtg_tickets_th');
        $this->db->join('gtg_customers', 'gtg_tickets_th.cid=gtg_customers.id', 'left');
        $this->db->join('gtg_employees', 'gtg_tickets_th.eid=gtg_employees.id', 'left');
        $this->db->where('gtg_tickets_th.tid', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    private function send_email($mailto, $mailtotitle, $subject, $message, $attachmenttrue = false, $attachment = '')
    {
        $this->load->library('ultimatemailer');
        $this->db->select('host,port,auth,auth_type,username,password,sender');
        $this->db->from('gtg_smtp');
        $query = $this->db->get();
        $smtpresult = $query->row_array();
        $host = $smtpresult['host'];
        $port = $smtpresult['port'];
        $auth = $smtpresult['auth'];
        $auth_type = $smtpresult['auth_type'];
        $username = $smtpresult['username'];;
        $password = $smtpresult['password'];
        $mailfrom = $smtpresult['sender'];
        $mailfromtilte = $this->config->item('ctitle');

        $this->ultimatemailer->bin_send($host, $port, $auth, $auth_type, $username, $password, $mailfrom, $mailfromtilte, $mailto, $mailtotitle, $subject, $message, $attachmenttrue, $attachment);
    }

    public function thread_info($id)
    {
        $this->db->select('gtg_tickets.*, gtg_customers.name,gtg_customers.email');
        $this->db->from('gtg_tickets');
        $this->db->join('gtg_customers', 'gtg_tickets.cid=gtg_customers.id', 'left');
        $this->db->where('gtg_tickets.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function ticket()
    {
        $this->db->select('*');
        $this->db->from('univarsal_api');
        $this->db->where('id', 3);
        $query = $this->db->get();
        return $query->row();
    }

    function addreply($thread_id, $message, $filename)
    {
        $data = array('tid' => $thread_id, 'message' => $message, 'cid' => 0, 'eid' => $this->aauth->get_user()->id, 'cdate' => date('Y-m-d H:i:s'), 'attach' => $filename);
        if ($this->ticket()->key2) {

            $customer = $this->thread_info($thread_id);

            $this->send_email($customer['email'], $customer['name'], '[Customer Ticket] #' . $thread_id, $message . $this->ticket()->other, $attachmenttrue = false, $attachment = '');
        }
        return $this->db->insert('gtg_tickets_th', $data);
    }

    function deleteticket($id)
    {
        $this->db->delete('gtg_tickets', array('id' => $id));

        $this->db->select('attach');
        $this->db->from('gtg_tickets_th');
        $this->db->where('tid', $id);
        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $row) {
            if ($row['attach'] != '') {

                unlink(FCPATH . 'userfiles/support/' . $row['attach']);
            }
        }
        $this->db->delete('gtg_tickets_th', array('tid' => $id));
        return true;
    }

    public function ticket_stats()
    {

        $query = $this->db->query("SELECT
				COUNT(IF( status = 'Waiting', id, NULL)) AS Waiting,
				COUNT(IF( status = 'Processing', id, NULL)) AS Processing,
				COUNT(IF( status = 'Solved', id, NULL)) AS Solved
				FROM gtg_tickets ");
        echo json_encode($query->result_array());
    }


    function ticket_datatables($filt)
    {
        $this->ticket_datatables_query($filt);
        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $query = $this->db->get();
        return $query->result();
    }

    private function ticket_datatables_query($filt)
    {

        $this->db->from('gtg_tickets');
        if ($filt == 'unsolved') {
            $this->db->where('status!=', 'Solved');
        }

        $i = 0;

        foreach ($this->doccolumn_search as $item) // loop column
        {
            $search = $this->input->post('search');
            $value = $search['value'];
            if ($value) {

                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $value);
                } else {
                    $this->db->or_like($item, $value);
                }

                if (count($this->doccolumn_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $search = $this->input->post('order');
        if ($search) {
            $this->db->order_by($this->doccolumn_order[$search['0']['column']], $search['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function ticket_count_filtered($filt)
    {
        $this->ticket_datatables_query($filt);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function ticket_count_all($filt)
    {
        $this->ticket_datatables_query($filt);
        $query = $this->db->get();
        return $query->num_rows();
    }
}
