<?php



defined('BASEPATH') or exit('No direct script access allowed');

class Jobsheet_model extends CI_Model
{


    //documents list

    var $doccolumn_order = array(null, 'jobName', 'created_at', null);
    var $doccolumn_search = array('jobName', 'created_at');


    public function jobs()
    {
        $query = $this->db->get('gtg_job');
        return $query->result_array();
    }

    function addtaskdocument($title, $filename, $complaintid)
    {
        $cid=0;
        $this->aauth->applog("[Jobsheets Doc Added]  DocId $title ComplaintId " . $complaintid, $this->aauth->get_user()->username);
        $data = array('title' => $title, 'filename' => $filename, 'cdate' => date('Y-m-d'), 'cid' => $this->aauth->get_user()->id, 'fid' => $cid, 'rid' => 0,'complaintid'=>$complaintid);
        return $this->db->insert('gtg_documents', $data);
    }

    function deletetaskdocument($id, $complaintid)
    {   $cid=0;
        $this->db->select('filename');
        $this->db->from('gtg_documents');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        $this->db->trans_start();
        if ($this->db->delete('gtg_documents', array('id' => $id, 'fid' => $cid, 'rid' => 0))) {
            if (@unlink(FCPATH . 'userfiles/documents/' . $result['filename'])) {
                $this->aauth->applog("[Jobsheets Doc Deleted]  DocId $id CID " . $cid, $this->aauth->get_user()->username);
                $this->db->trans_complete();
                return true;
            } else {
                $this->db->trans_rollback();
                return false;
            }
        } else {
            return false;
        }
    }

    public function addtask($title, $description, $timeFrame, $user, $created_at)
    {
            $data = array(
                'jobName' => $title,
                'jobDescription' => $description,
                'manDays' => $timeFrame,
                'userId' => $user,
                'updated_at' => $created_at,
                'created_at' => $created_at
            );

            $cid=0;
            if ($this->db->insert('gtg_job', $data)) {
                $cid = $this->db->insert_id();

            }
            return $cid;

    }

    public function delete($id)
        {
            $this->db->delete('gtg_customers', array('id' => $id));
            return true;
        }

    }



/*

    public function thread_list($id)
    {
        $this->db->select('*');
        $this->db->from('job');
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
*/

