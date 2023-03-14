<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Payments extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('payments_model', 'payments');
        if (!is_login()) {
            redirect(base_url() . 'user/profile', 'refresh');
        }

        $this->load->model('User_model');
    }

    //invoices list
    public function index()
    {
        $head['title'] = "Payments";
        is_login();
        $userid = $this->session->userdata('user_details')[0]->users_id;
        $data['user_data'] = $this->User_model->get_users($userid);
        $head['user_data']=$data['user_data'];
        $this->load->view('includes/header',$head);
        $this->load->view('payments/payments');
        $this->load->view('includes/footer');
    }

    public function recharge()
    {
        $head['title'] = "Payments";
        $data['balance'] = $this->payments->balance($this->session->userdata('user_details')[0]->cid);
        $data['activity'] = $this->payments->activity($this->session->userdata('user_details')[0]->cid);
        $data['gateway'] = $this->payments->gateway_list('Yes');

        is_login();
        $userid = $this->session->userdata('user_details')[0]->users_id;
        $data['user_data'] = $this->User_model->get_users($userid);
        $head['user_data']=$data['user_data'];
        $this->load->view('includes/header',$head);
        $this->load->view('payments/recharge', $data);
        $this->load->view('includes/footer');
    }


    public function ajax_list()
    {
        $query = $this->db->query("SELECT currency FROM gtg_system WHERE id=1 LIMIT 1");
        $row = $query->row_array();

        $this->config->set_item('currency', $row["currency"]);


        $list = $this->payments->get_datatables();
        $data = array();

        $no = $this->input->post('start');
        $curr = $this->config->item('currency');

        foreach ($list as $invoices) {
            $no++;
            $row = array();
            $row[] = $invoices->date;
            $row[] =  amountExchange($invoices->credit, 0, $invoices->loc);
            $row[] =   amountExchange($invoices->debit, 0, $invoices->loc);
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->payments->count_all(),
            "recordsFiltered" => $this->payments->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}
