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

    public function create()
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
        $created_at="2023-01-27 11:49:02";
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
                    $data['message'] = $this->lang->line('ADDED') . ' Task' . '&nbsp;<a href="' . base_url('jobsheets/view') . '" class="btn btn-info btn-sm"><span class="icon-eye"></span>' . $this->lang->line('View') . '</a>';

            }else{
                    $this->jobsheet->delete($jobId);

                    $data['status'] = 'error';
                    $data['message']=$this->lang->line('ERROR').". ".$message;
            }
        }

        $head['title'] = 'Jobsheet - create task';
        $data['message']=$message;
        $this->load->view('fixed/header', $head);
        $this->load->view('jobsheet/create',$data);
        $this->load->view('fixed/footer');
    }
}