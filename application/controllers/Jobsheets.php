<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Jobsheets extends CI_Controller
{

   public function __construct()
    {
        parent::__construct();
        $this->load->model('jobsheet_model', 'jobsheet');
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        if (!$this->aauth->premission(3)) {
            exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        }
        $this->li_a = 'crm';
    }

    public function index()
    {
       // $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Jobsheet';
       // $data['totalt'] = $tickets->ticket_count_all('');
       // print_r($data);
          $this->load->view('fixed/header', $head);
          $this->load->view('jobsheet/jobs');
          $this->load->view('fixed/footer');
    }

    public function create($data=null)
    {
       // $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Jobsheet - create task';
       // $data['totalt'] = $tickets->ticket_count_all('');
       // print_r($data);
          $this->load->view('fixed/header', $head);
          $this->load->view('jobsheet/create');
          $this->load->view('fixed/footer');
    }

    public function add_task()
    {

        $data=array();
        $title=$_POST['title'];
        $description=$_POST['description'];
        $timeFrame=$_POST['timeFrame'];
        $user=$this->aauth->get_user()->id;
        $uploaddoc=false;
        $created_at=date("Y-m-d")." ".date("h:i:s");
        $jobId=$this->jobsheet->addtask($title, $description, $timeFrame, $user, $created_at);
        $message="";
        if( $jobId>0) {
            $attach = $_FILES['userfile']['name'];
            if ($attach){
                    $config['upload_path'] = './userfiles/documents';
                    $config['allowed_types'] = 'docx|docs|txt|pdf|xls';
                    $config['encrypt_name'] = TRUE;
                    $config['max_size'] = 3000;
                    $config['file_name'] = time() . $attach;
                    $this->load->library('upload', $config);

                if (!$this->upload->do_upload('userfile')) {
                        $data['response'] = 0;
                        $data['responsetext'] = 'File Upload Error';

                } else {
                        $data['response'] = 1;
                        $data['responsetext'] = 'Document Uploaded Successfully. <a href="documents?id=' . $jobId . '"
                                            class="btn btn-indigo btn-md"><i
                                                        class="icon-folder"></i>
                                            </a>';
                        $filename = $this->upload->data()['file_name'];

                        $uploaddoc=$this->jobsheet->addtaskdocument($title, $filename, $jobId);

                        if(!$uploaddoc){
                        $message='upload document not working';
                        }
                    }
            }

            if($uploaddoc && ($jobId>0)){
                    $this->aauth->applog("[Jobsheets Added] $user TaskId " . $jobId, $this->aauth->get_user()->username);
                    $data['status'] = 'success';
                    $data['message'] = $this->lang->line('ADDED') . '' . '&nbsp;<a href="' . base_url('jobsheets/view') . '" class="btn btn-info btn-sm"><span class="icon-eye"></span> ' . $this->lang->line('View') . ' </a>';

            }else{
                    $this->jobsheet->delete($jobId);

                    $data['status'] = 'error';
                    $data['message']=$this->lang->line('ERROR').". ".$message;
            }
        }
        unset($_POST);
        $_SESSION['status']=$data['status'];
        $_SESSION['message']=$data['message'];
        $this->session->mark_as_flash('status');
        $this->session->mark_as_flash('message');
        redirect('jobsheets/create', 'refresh');
    }

    public function tasks_load_list()
    {
        $filt = $this->input->get('stat');
        $list = $this->jobsheet->jobsheet_datatables($filt);
        $data = array();
        $no = $this->input->post('start');

        foreach ($list as $jobsheet) {
            $row = array();
            $no++;
            $row[] = $no;
            $row[] = $jobsheet->jobName;
            $row[] = dateformat_time($jobsheet->created_at);
            $temp = '<span class="st-' . $jobsheet->status . '">';
            if($jobsheet->status==1){
                $temp.="Completed";
            }elseif($jobsheet->status==2){
                $temp.="Pending";
            }
            elseif($jobsheet->status==3){
                $temp.="unassigned";
            }


            $temp.='</span>';
            $row[]=$temp;

            $row[] = '<a href="' . base_url('jobsheets/thread/?id=' . $jobsheet->id) . '" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> ' . $this->lang->line('View') . '</a> <a class="btn btn-danger btn-xs delete-object" href="#" data-object-id="' . $jobsheet->id . '"> <i class="fa fa-trash "></i> </a>';


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->jobsheet->jobsheet_count_all($filt),
            "recordsFiltered" => $this->jobsheet->jobsheet_count_filtered($filt),
            "data" => $data,
        );
        echo json_encode($output);
    }
public function thread()
    {

        $this->load->helper(array('form'));
        $thread_id = $this->input->get('id');

        $data['response'] = 3;
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Add Jobsheet Update';

        $this->load->view('fixed/header', $head);

        if ($this->input->post('content')) {

            $message = $this->input->post('content');
            $attach = $_FILES['userfile']['name'];
            if ($attach) {
                $config['upload_path'] = './userfiles/support';
                $config['allowed_types'] = 'docx|docs|txt|pdf|xls|png|jpg|gif';
                $config['max_size'] = 3000;
                $config['file_name'] = time() . $attach;
                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('userfile')) {
                    $data['response'] = 0;
                    $data['responsetext'] = 'File Upload Error';
                } else {
                    $data['response'] = 1;
                    $data['responsetext'] = 'Reply Added Successfully.';
                    $filename = $this->upload->data()['file_name'];
                    $this->jobsheet->addreply($thread_id, $message, $filename);
                }
            } else {
                $this->jobsheet->addreply($thread_id, $message, '');
                $data['response'] = 1;
                $data['responsetext'] = 'Reply Added Successfully.';
            }

            $data['thread_info'] = $this->jobsheet->thread_jobsheet_info($thread_id);
            $data['thread_list'] = $this->jobsheet->thread_jobsheet_list($thread_id);

            $this->load->view('jobsheet/thread', $data);
        } else {

            $data['thread_info'] = $this->jobsheet->thread_jobsheet_info($thread_id);
            $data['thread_list'] = $this->jobsheet->thread_jobsheet_list($thread_id);


            $this->load->view('jobsheet/thread', $data);
        }
        $this->load->view('fixed/footer');
    }


    function addreply($thread_id, $message, $filename)
    {
        $data = array('jid' => $thread_id, 'message' => $message, 'cid' => 0, 'eid' => $this->aauth->get_user()->id, 'cdate' => date('Y-m-d H:i:s'), 'attach' => $filename);
        if ($this->jobsheet()->key2) {

            $customer = $this->thread_Jobsheet_info($thread_id);

           // $this->send_email($customer['email'], $customer['name'], '[Customer Ticket] #' . $thread_id, $message . $this->ticket()->other, $attachmenttrue = false, $attachment = '');
        }
        return $this->db->insert('gtg_jobsheets_th', $data);
    }


}