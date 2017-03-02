<?php
	
	class Notification_Model extends CI_Model{
		
		function createNotification($data){
			$this->db->insert('tbl_cyberits_t_notification',$data);	
			$result=$this->db->affected_rows();
			return $result;
		}
	}
?>