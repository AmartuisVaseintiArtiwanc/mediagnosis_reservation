<?php  
	class Topic_Model extends CI_model{

		function getTopicList(){
			$this->db->select('*'); 
			$this->db->from('tbl_cyberits_m_topics');
			$query = $this->db->get();
			return $query->result_array();
		}
	}
?>