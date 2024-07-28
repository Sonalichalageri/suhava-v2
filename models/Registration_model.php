<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration_model extends CI_Model 
{

	function create($insert_array)
	{
		$this->db->insert("user",$insert_array);				
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	function login($email, $password)
	{		
		$this->db->select("*");
		$this->db->from("user");
		$this->db->where("email",$email);		
		$this->db->where("password",$password);		
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;

	}
	


	
}


