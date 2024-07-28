<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task_model extends CI_Model 
{

	function add($insert_array)
	{
		$this->db->insert("task",$insert_array);				
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	function task_list()
	{		
		$this->db->select("*");
		$this->db->from("task");		
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	function update($task_id, $update_array)
	{
		
		$this->db->where('task_id', $task_id);
        $this->db->update('task', $update_array);
		return true;

	}

	function task_details($task_id)
	{
		
		$this->db->select("*");
		$this->db->from("task");	
		$this->db->where("task_id",$task_id);			
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;

	}
	
	
	


	
}


