<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Donate extends CI_Controller {
    
    public function __construct()
	{	
		parent::__construct();
		$this->load->helper('email');
		$this->load->helper('url');
	    $this->load->helper('security');
		$this->load->model("Donar_model");
	}
	
    public function has_match($pancard_no){
        if (preg_match("/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/", $pancard_no )) {
            return true;
        }
        else {
            $this->form_validation->set_message('has_match', 'Invalid PAN number is entered.');
            return false;
        }
    }
    
    // callback function
    public function customAlpha($str) 
    {
        if ( !preg_match('/^[a-z .,\-]+$/i',$str) )
        {
            $this->form_validation->set_message('customAlpha', 'Only alphabets is allowed.');
            return false;
        }else{
            return true;
        }
    }
    
    // callback function
    public function customAlphaNumeric($str) 
    {
        if ( !preg_match('/^[][a-zA-Z0-9@# ,().]+$/',$str) )
        {
            $this->form_validation->set_message('customAlphaNumeric', 'Only alphabets and Numbers is allowed.');
            return false;
        }else{
            return true;
        }
    }

	public function index()
	{   
	    
	    $this->form_validation->set_rules("donor_name","Name","required|min_length[3]|max_length[30]|xss_clean|callback_customAlpha[donor_name]");
		$this->form_validation->set_rules("mobile","Mobile No","required|numeric|exact_length[10]|xss_clean");
		$this->form_validation->set_rules("email_id","Email Id","trim|required|valid_email|xss_clean");
		$this->form_validation->set_rules("donation_amount","Donation amount","required|xss_clean|numeric");
		$this->form_validation->set_rules("pancard_no","Pancard No","required|callback_has_match[pancard_no]|xss_clean");
		$this->form_validation->set_rules("address","Address","required|xss_clean|callback_customAlphaNumeric[address]");
		 
	    if($this->form_validation->run()==false)
		{
		    
			 $this->load->view('site/site-header');
		     $this->load->view('site/donate');
		     $this->load->view('site/site-footer');
		}
		else
		{
		    
			$donor_name = $_POST["donor_name"];
			$mobile = $_POST["mobile"];
			$email_id = $_POST["email_id"];
			$donation_amount = $_POST["donation_amount"];
			$pancard_no = $_POST["pancard_no"];
			$address = $_POST["address"];

			$insert_array = array();

			$insert_array = array(
				"DonorName"=>$donor_name,
				"ContactNumber"=>$mobile,
				"Email"=>$email_id,
				"Address"=>$address,
				"Pan"=>$pancard_no,
				"DonationAmount"=>$donation_amount,
				"DonationDate"=>date('Y-m-d'),
				"PaymentMethod"=>"",
				"TxnId"=>"",
			);

			
			$result = $this->Donar_model->add_donation($insert_array);
			
			$donor_id = array('DonorID' =>$result);
            $donor_json = json_encode($donor_id);
            $donor_base64_encode = base64_encode($donor_json);

			if($result)
			{
				$this->session->set_flashdata("success_msg","Details successfully added.");				
				redirect(base_url()."donate/step2/$donor_base64_encode?success");			
			}
			else
			{
				$this->session->set_flashdata("error_msg","Something went wrong.");				
				redirect(base_url()."donate/index?success");			
			}

		}
	    
	}
	
	public function step2()
	{
	   
	    $data["url"] = $url = $this->uri->segment(3);
	    $donor_base64_decode = base64_decode($url,true);
        $donor_json_decode = json_decode($donor_base64_decode,true);
        $donor_id = $donor_json_decode["DonorID"];

	    $this->form_validation->set_rules("transaction_no","Transaction No","required|xss_clean|callback_customAlphaNumeric[transaction_no]");
	   
        if($this->form_validation->run()==false)
		{
			
			$this->load->view('site/site-header',$data);
    		$this->load->view('site/donate_step2',$data);
    		$this->load->view('site/site-footer',$data);
		}
		else
		{
		    $transaction_no = $_POST["transaction_no"];
			$uploadimage = $_FILES["uploadimage"];


		    $config['upload_path']          = FCPATH.'assets/uploads';
			$config['allowed_types']        = 'jpg|jpeg|png';
			$config['max_size']             = 3000;
			$config['max_width']            = 1024;
			$config['max_height']           = 768;
			
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
			
			$update_arr = array();
			$update_arr =array(
				"TxnId"=>$transaction_no,
				"transaction_img_name"=>$file_name,
			);
			
			
			$result = $this->Donar_model->update_transaction($donor_id, $update_arr);		
			if($result)
			{
				$this->session->set_flashdata("success_msg","Updated successfully.");				
				redirect(base_url()."donate/step3/$url?success");			
			}
			else
			{
				$this->session->set_flashdata("error_msg","Something went wrong.");				
				redirect(base_url()."donate/step2/$url?failed");			
			}
			
		}
		
	}
	
	public function step3()
	{
	   
	    $this->load->view('site/site-header');
		$this->load->view('site/donate_step3');
		$this->load->view('site/site-footer');
	    
	}



}
