<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	
	public function index()
	{
	    
	    $this->load->view('site/site-header');
		$this->load->view('site/index');
		$this->load->view('site/site-footer');
		
	}
	
}
