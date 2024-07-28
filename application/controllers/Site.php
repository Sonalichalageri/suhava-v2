<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('display_errors', 1);
class Site extends CI_Controller {
    
     public function __construct() {
        parent::__construct();
        
        if($this->session->userdata("user_id")=="")
		{
			redirect(base_url()."login/index?FailedLogin");						
		}
		
        $this->load->library('email'); // Load the email library
        $this->load->model("Site_model");
        $this->load->helper('url','form');
	    $this->load->helper('security');
    }
    
	public function index()
	{
	   
	    $data["result"] = $result = $this->Site_model->carousel_list();
	    
	    $this->load->view('admin/header');
	    $this->load->view('suhava_site/list_carousel',$data);
	    $this->load->view('admin/footer');
	}
	
	public function add()
	{
	    
	    $this->form_validation->set_rules("image_name","Name","required");
	    $this->form_validation->set_rules("image_description","Description","required");
	    $this->form_validation->set_rules("status","Status","required");
	   
	    if($this->form_validation->run()==false)
		{
		    
			$this->load->view('admin/header');
	        $this->load->view('suhava_site/add_carousel');
	        $this->load->view('admin/footer');
		}
		else
		{
		    
			$image_name = $_POST["image_name"];
			$image_description = $_POST["image_description"];
			$status = $_POST["status"];
			$uploadimage = $_FILES["uploadimage"];

		    $config['upload_path']          = FCPATH.'assets/uploads';
			$config['allowed_types']        = 'jpg|jpeg|png';
			$config['max_size']             = 3000;
// 			$config['max_width']            = 1024;
// 			$config['max_height']           = 768;
			
            $this->load->library('upload', $config);

			$file_name="";
			if(!$this->upload->do_upload('uploadimage'))
			{
				$error = array('error' => $this->upload->display_errors());							
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());	
				$file_name = $data["upload_data"]["file_name"];
			}
			
		
			$insert_array = array();
			$insert_array = array(
				"sc_name"=>$image_name,
				"sc_description"=>$image_description,
				"sc_status"=>$status,
				"file_name"=>$file_name,
				"sc_created_date"=>date('Y-m-d'),
				"sc_updated_date"=>date('Y-m-d'),
				"file_name"=>$file_name,
			);

			
			$result = $this->Site_model->add_carousel($insert_array);
			if($result)
			{
				$this->session->set_flashdata("success_msg","Site carousel successfully added.");				
				// redirect(base_url()."site/add/$donor_base64_encode?success");			
				redirect(base_url()."site/index?success");			
			}
			else
			{
				$this->session->set_flashdata("error_msg","Something went wrong.");				
				redirect(base_url()."site/add?error");			
			}

		}
	    
	}
	
	public function edit()
	{
	    
	    $data["url"] = $url = $this->uri->segment(3);
	    
	    $donor_base64_decode = base64_decode($url,true);
        $donor_json_decode = json_decode($donor_base64_decode,true);
        $site_carousel_id = $donor_json_decode["sc_id"];
        
	    $this->form_validation->set_rules("image_name","Name","required");
	    $this->form_validation->set_rules("image_description","Description","required");
	    $this->form_validation->set_rules("status","Status","required");
	    
	    $data["result"] = $result = $this->Site_model->get_carousel($site_carousel_id);
	    
	    if($this->form_validation->run()==false)
		{
		    
			$this->load->view('admin/header');
	        $this->load->view('suhava_site/edit_carousel',$data);
	        $this->load->view('admin/footer');
		}
		else
		{ 
		    
			$image_name = $_POST["image_name"];
			$image_description = $_POST["image_description"];
			$status = $_POST["status"];
            $file_name="";
            
            if(isset($_FILES["uploadimage"]) && $_FILES["uploadimage"]!=""){
                
                $config['upload_path']          = FCPATH.'assets/uploads';
    			$config['allowed_types']        = 'jpg|jpeg|png';
    			$config['max_size']             = 3000;
    			
                $this->load->library('upload', $config);
    			
    			if(!$this->upload->do_upload('uploadimage'))
    			{
    				$error = array('error' => $this->upload->display_errors());							
    			}
    			else
    			{
    				$data = array('upload_data' => $this->upload->data());	
    				$file_name = $data["upload_data"]["file_name"];
    			}
            }
		    
			$update_array = array();
			$update_array = array(
				"sc_name"=>$image_name,
				"sc_description"=>$image_description,
				"sc_status"=>$status,
				"file_name"=>$file_name,
				"sc_updated_date"=>date('Y-m-d'),
			);
			
			if($file_name!=""){
			    $update_array["file_name"]=$file_name;
			}

			
			$result = $this->Site_model->update_carousel($site_carousel_id, $update_array);
			
			if($result)
			{
				$this->session->set_flashdata("success_msg","Site carousel successfully updated.");				
				redirect(base_url()."site/index?success");			
			}
			else
			{
				$this->session->set_flashdata("error_msg","Something went wrong.");				
				redirect(base_url()."site/edit/$url?error");			
			}

		}
	    
	}
	
	public function add_logo()
	{
	    
	    $this->form_validation->set_rules("logo_name","Logo Name","required");
	    $this->form_validation->set_rules("logo_description","Logo Description","required");
	    $this->form_validation->set_rules("logo_status","Logo Status","required");
	   
	    if($this->form_validation->run()==false)
		{
		    
			$this->load->view('admin/header');
	        $this->load->view('suhava_site/add_logo');
	        $this->load->view('admin/footer');
		}
		else
		{
		    
			$logo_name = $_POST["logo_name"];
			$logo_description = $_POST["logo_description"];
			$logo_status = $_POST["logo_status"];
			$uploadimage = $_FILES["logo_image_upload"];

		    $config['upload_path']          = FCPATH.'assets/uploads/logo';
			$config['allowed_types']        = 'jpg|jpeg|png';
			$config['max_size']             = 3000;
			
            $this->load->library('upload', $config);

			$file_name="";
			if(!$this->upload->do_upload('logo_image_upload'))
			{
				$error = array('error' => $this->upload->display_errors());							
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());	
				$file_name = $data["upload_data"]["file_name"];
			}
		
			$insert_array = array();
			$insert_array = array(
				"logo_title"=>$logo_name,
				"logo_description"=>$logo_description,
				"logo_status"=>$logo_status,
				"logo_img_name"=>$file_name
			);

			
			$result = $this->Site_model->add_logo($insert_array);
			
			if($result)
			{
				$this->session->set_flashdata("success_msg","Logo added successfully.");				
				redirect(base_url()."site/logo_list?success");			
			}
			else
			{
				$this->session->set_flashdata("error_msg","Something went wrong.");				
				redirect(base_url()."site/add_logo?error");			
			}

		}
	    
	}
	
	public function logo_list()
	{
	   
	    $data["result"] = $result = $this->Site_model->logo_list();
	    
	    $this->load->view('admin/header');
	    $this->load->view('suhava_site/logo_list',$data);
	    $this->load->view('admin/footer');
	}
	
	public function edit_logo()
	{
	    
	    $data["url"] = $url = $this->uri->segment(3);
	    
	    $donor_base64_decode = base64_decode($url,true);
        $donor_json_decode = json_decode($donor_base64_decode,true);
        $logo_id = $donor_json_decode["logo_id"];
        
	    $this->form_validation->set_rules("logo_name","Logo Name","required");
	    $this->form_validation->set_rules("logo_description","Logo Description","required");
	    $this->form_validation->set_rules("logo_status","Logo Status","required");
	    
	    $data["result"] = $result = $this->Site_model->get_logo($logo_id);
	    
	    if($this->form_validation->run()==false)
		{
		    
		    
			$this->load->view('admin/header');
	        $this->load->view('suhava_site/edit_logo',$data);
	        $this->load->view('admin/footer');
		}
		else
		{ 
		    
		    $logo_name = $_POST["logo_name"];
			$logo_description = $_POST["logo_description"];
			$logo_status = $_POST["logo_status"];
            $file_name="";
            
            if(isset($_FILES["logo_img_name"]) && $_FILES["logo_img_name"]!=""){
                
                $config['upload_path']          = FCPATH.'assets/uploads/logo';
    			$config['allowed_types']        = 'jpg|jpeg|png';
    			$config['max_size']             = 3000;
    			
                $this->load->library('upload', $config);
    			
    			if(!$this->upload->do_upload('logo_img_name'))
    			{
    				$error = array('error' => $this->upload->display_errors());							
    			}
    			else
    			{
    				$data = array('upload_data' => $this->upload->data());	
    				$file_name = $data["upload_data"]["file_name"];
    			}
            }
		    
			$update_array = array();
			$update_array = array(
			    "logo_title"=>$logo_name,
				"logo_description"=>$logo_description,
				"logo_status"=>$logo_status,
			);
			
			if($file_name!=""){
			    $update_array["logo_img_name"]=$file_name;
			}

			
			$result = $this->Site_model->update_logo($logo_id, $update_array);
			
			if($result)
			{
				$this->session->set_flashdata("success_msg","Site carousel successfully updated.");				
				redirect(base_url()."site/index?success");			
			}
			else
			{
				$this->session->set_flashdata("error_msg","Something went wrong.");				
				redirect(base_url()."site/edit/$url?error");			
			}

		}
	    
	}
	//added by arvind
	public function sendEmail(){
	    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
	    $name = $this->input->post('name');
        $email = $this->input->post('email');
	    $email_body = $this->load->view('site/email_template', array('name' => $name), TRUE);
        $this->email->from('prajapatiaws@gmail.com', 'Suhava');
        $this->email->to($email);
        $this->email->subject('Verification Successful');
        $this->email->message($email_body);

        // Send email
        if ($this->email->send()) {
            echo 'Email sent successfully';

            // Update database to mark the bill as sent
            $data = array('bill_sent' => '1');
            #$this->order_bills->update($order_id, $data);
        } else {
            echo 'Failed to send email';
            echo $this->email->print_debugger();
        }
    }
	
	

}
