<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_pending_donors() {
        $this->db->where('DonorStatus', 'pending-verification');
        $query = $this->db->get('GausalaDonor');
        return $query->result();
    }
    public function verify_donor($donor_id) {
        $this->db->set('DonorStatus', 'approved');
        $this->db->where('DonorID', $donor_id);
        return $this->db->update('GausalaDonor');
    }
    
    public function add_carousel($insert_data) {
        
        $this->db->insert("site_carousel",$insert_data);				
		$insert_id = $this->db->insert_id();
		return $insert_id;
    }
    
    public function update_carousel($carousel_id, $update_array) 
    {
       
        $this->db->where('sc_id', $carousel_id);
        $this->db->update('site_carousel', $update_array);
		return true;
    }
    
    public function get_dashboard_data() 
    {
        
        $this->db->select("*");
		$this->db->from("GausalaDonor");		
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
    }
    
    public function get_curr_month_dashboard_data() 
    {

        $this->db->select("*");
		$this->db->from("GausalaDonor");
		$this->db->where('MONTH(DonationDate)', date('m')); //For current month
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
		
    }
    public function get_email($donar_id){
        $this->db->select('email,DonorName');
        $this->db->from('GausalaDonor');
        $this->db->where('DonorID',$donar_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    public function carousel_list() {
        
        // $this->db->select('*');
        // $this->db->from('site_carousel');
        // $this->db->where('sc_status',0);
        // $query = $this->db->get();
        // $result = $query->result_array();
        // return $result;
        
        $this->db->where('sc_status', 0);
        $query = $this->db->get('site_carousel');
        return $query->result();
    }
    
     public function get_donor($donor_id) {
        
        $this->db->select('*');
        $this->db->from('GausalaDonor');
        $this->db->where('DonorID',$donor_id);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
        
    }
    
    
    public function get_carousel($carousel_id){
        
        $this->db->select('*');
        $this->db->from('site_carousel');
        $this->db->where('sc_id',$carousel_id);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }
    
    public function add_logo($insert_data) {
        
        $this->db->insert("logo",$insert_data);				
		$insert_id = $this->db->insert_id();
		return $insert_id;
    }
    
    public function logo_list() {
        
        $this->db->where('logo_status', 0);
        $query = $this->db->get('logo');
        return $query->result();
    }
    
    public function get_logo($logo_id) {
        
        $this->db->select('*');
        $this->db->from('logo');
        $this->db->where('logo_id',$logo_id);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
        
    }
    
    public function update_logo($logo_id, $update_array) 
    {
       
        $this->db->where('logo_id', $logo_id);
        $this->db->update('logo', $update_array);
		return true;
    }
    
    
}
