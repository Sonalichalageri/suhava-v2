<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('display_errors', 1);
class Task extends CI_Controller {
	
	public function __construct()
	{	
		parent::__construct();				
		if($this->session->userdata("user_id")=="")
		{
			redirect(base_url()."index.php/login?FailedLogin");						
		}

		$this->load->model("Task_model");
	}

	public function task_list()
	{	
		
		$result = $this->Task_model->task_list();
		$data["result"]=$result;
		
		

		$this->load->view('task_details',$data);
	}

	public function add()
	{	
		$this->form_validation->set_rules("task_name","Task Name","required");
		$this->form_validation->set_rules("description","Description","required");
		$this->form_validation->set_rules("start_date","Start Date","required");
		$this->form_validation->set_rules("end_date","End Date","required");
		$this->form_validation->set_rules("task_status","task Status","required");
		
		if($this->form_validation->run()==false)
		{

			$this->load->view("add_task");
		}
		else
		{
			$name = $_POST["task_name"];
			$description = $_POST["description"];
			$start_date = $_POST["start_date"];
			$end_date = $_POST["end_date"];
			$task_status = $_POST["task_status"];

			$insert_array = array();

			$insert_array = array(
				"name"=>$name,
				"description"=>$description,
				"start_date"=>$start_date,
				"end_date"=>$end_date,
				"task_status"=>$task_status,
			);

			
			$result = $this->Task_model->add($insert_array);

			if($result)
			{
				$this->session->set_flashdata("success_msg","Successfully Inserted.");				
				redirect(base_url()."index.php/task/task_list?SuccessfullyInserted");			
			}
			else
			{
				$this->session->set_flashdata("error_msg","Something went wrong.");				
				redirect(base_url()."index.php/login/add?InsertFailed");
			}

		}

	}

	
	public function edit_task()
	{	
		$data["edit_url"] = $task_url = $this->uri->segment(3);
		$task_url_json = base64_decode($task_url,true);
		$task_details = json_decode($task_url_json,true);	
		
		$this->form_validation->set_rules("task_name","Task Name","required");
		$this->form_validation->set_rules("description","Description","required");
		$this->form_validation->set_rules("start_date","Start Date","required");
		$this->form_validation->set_rules("end_date","End Date","required");
		$this->form_validation->set_rules("task_status","task Status","required");

		$result = $this->Task_model->task_details($task_details["task_id"]);
		$data["result"]=$result;
		
		if($this->form_validation->run()==false)
		{

			$this->load->view("edit_task",$data);
		}
		else
		{
			$name = $_POST["task_name"];
			$description = $_POST["description"];
			$start_date = $_POST["start_date"];
			$end_date = $_POST["end_date"];
			$task_status = $_POST["task_status"];

			$update_array = array();

			$update_array = array(
				"name"=>$name,
				"description"=>$description,
				"start_date"=>$start_date,
				"end_date"=>$end_date,
				"task_status"=>$task_status,
			);

			$result = $this->Task_model->update($task_details["task_id"], $update_array);		

			if($result)
			{
				$this->session->set_flashdata("success_msg","Successfully updated record.");				
				redirect(base_url()."index.php/task/task_list?SuccessfullyUpdate");			
			}
			else
			{
				$this->session->set_flashdata("error_msg","Something went wrong.");				
				redirect(base_url()."index.php/login/edit?UpdateFailed");
			}

		}

	}

}
