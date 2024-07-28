<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Index extends CI_Controller {
    
    public function __construct()
	{	
		parent::__construct();
		
	}
	
   

	public function index()
	{   
	   
	    $this->load->model("Site_model");
	    
	    $data["result"] = $result = $this->Site_model->carousel_list();
	    $data["logo_list"] = $logo_list = $this->Site_model->logo_list();
	    
	   // print_r($data);
	   // die("debug 21");
	    
	    
	    $this->load->view('suhava_site/index',$data);
	    
	}
	

}
