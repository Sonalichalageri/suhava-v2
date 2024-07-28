<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Donar_model extends CI_Model {
    
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
    
    public function add_donation($insert_donor_data) {
        
        $this->db->insert("GausalaDonor",$insert_donor_data);				
		$insert_id = $this->db->insert_id();
		return $insert_id;
    }
    
    public function update_transaction($donor_id, $update_array) 
    {
       
        $this->db->where('DonorID', $donor_id);
        $this->db->update('GausalaDonor', $update_array);
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
    
    public function verified_list() {
        
        $this->db->where('DonorStatus', 'approved');
        $query = $this->db->get('GausalaDonor');
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
    
}
