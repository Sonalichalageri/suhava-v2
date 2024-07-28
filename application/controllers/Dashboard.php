<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('display_errors', 1);
class Dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
        
        if($this->session->userdata("user_id")=="")
		{
			redirect(base_url()."login/index?FailedLogin");						
		}
		
		$this->load->model('Donar_model');
    }
    
	public function index()
	{
	    $result= [];
	    $current_month_result= [];
	    
	   // $result = $this->Donar_model->get_dashboard_data();
	   // $current_month_result = $this->Donar_model->get_curr_month_dashboard_data();

	    $data["total_amount"] = 0;
	    $data["total_donor"] = 0; 
	    $data["curr_month_total_amount"] = 0;
	    $data["curr_month_total_donor"] = 0;
	    
	   // if(count($result) > 0)
	   // {
	   //     $DonationAmount = array_column($result , 'DonationAmount');
	   //     $data["total_amount"] = array_sum($DonationAmount); 
	   //     $data["total_donor"] = count($result); 
	   // }
	    
	   // if(count($current_month_result) > 0)
	   // {
	   //     $CurrentMonthDonationAmount = array_column($current_month_result , 'DonationAmount');
	   //     $data["curr_month_total_amount"] = array_sum($CurrentMonthDonationAmount); 
	   //     $data["curr_month_total_donor"] = count($current_month_result); 
	   // }
	    
	    $this->load->view('admin/header');
	    $this->load->view('admin/dashboard',$data);
	    $this->load->view('admin/footer');
	    
	}
	
}
