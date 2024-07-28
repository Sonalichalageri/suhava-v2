<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('display_errors', 1);
class Registration extends CI_Controller {

	
	public function index()
	{
		
		$this->form_validation->set_rules("name","Name","required");
		$this->form_validation->set_rules("email","Email","trim|required|valid_email");
		$this->form_validation->set_rules("password","password","required");
		$this->form_validation->set_rules("confirm_password","confirm_password","required|matches[password]");		
		$this->form_validation->set_rules("dob","dob","required|callback_validate_age[dob]");

		if($this->form_validation->run()==false)
		{
			
			$this->load->view('registration');
		}
		else
		{

			$name = $_POST["name"];
			$email = $_POST["email"];
			$password = $_POST["password"];
			$confirm_password = $_POST["confirm_password"];
			$dob = $_POST["dob"];

			$profilepic = $_FILES["profilepic"];

			$config['upload_path']          = FCPATH.'uploads/';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 100;
			$config['max_width']            = 1024;
			$config['max_height']           = 768;

            $this->load->library('upload', $config);

			$file_name="";
			if(!$this->upload->do_upload('profilepic'))
			{
				$error = array('error' => $this->upload->display_errors());							
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());	
				$file_name = $data["upload_data"]["file_name"];
			}

			$insert_arr = array();
			$insert_arr =array(
				"name"=>$name,
				"email"=>$email,
				"password"=>md5($password),
				"profilepic"=>$file_name,
				"dob"=>$dob
			);
			
			$this->load->model("Registration_model");
			$result = $this->Registration_model->create($insert_arr);

			if($result)
			{				
				$this->session->set_flashdata("success_msg","Account created successfull.");								
				redirect(base_url()."index.php/registration?Sucess");	
			}
			else
			{
				$this->session->set_flashdata("error_msg","Something went wrong.");				
				redirect(base_url()."index.php/registration?Failed");	
			}

			


		}

		
		
	}

	public function validate_age($age)
	{
		$dob = new DateTime($age);
		$now = new DateTime();

		if($now->diff($dob)->y > 18)		
			return true;		
		else		
			return false;	
			
	}


	
}
