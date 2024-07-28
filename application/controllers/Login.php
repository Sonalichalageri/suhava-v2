<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('display_errors', 1);
class Login extends CI_Controller {

     public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('session');
    }

	public function index()
	{
	    $this->load->view('admin/login');
	}
    public function auth() {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('login');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $user = $this->User_model->login($username, $password);

            if ($user) {
                $this->session->set_userdata('user_id', $user->id);
                // redirect('dashboard');
                redirect(base_url()."site/index");						
            } else {
                $this->session->set_flashdata('error', 'Invalid username or password');
                // redirect('login');
                redirect(base_url()."login/");						
            }
        }
    }

	public function signout()
	{
		
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('name');
		$this->session->unset_userdata('email');	
		$this->session->sess_destroy();

		$this->session->set_flashdata("success_msg","Successfully Logout.");				

		redirect(base_url()."index.php/login?successfulllogout");
	}
	
	public function logout() {
        $this->session->unset_userdata('user_id');
        $this->session->sess_destroy();

        redirect('login');
    }
}
