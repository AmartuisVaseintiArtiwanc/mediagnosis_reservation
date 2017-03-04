<?php  
	class Topic_Model extends CI_model{

		function getTopicList(){
			$this->db->select('mt.topicID, mt.topicName, COUNT(st.doctorID) AS doctorCount'); 
			$this->db->from('tbl_cyberits_m_topics mt');
			$this->db->join('tbl_cyberits_s_topic st', 'mt.topicID = st.topicID', 'left');
			$this->db->where('mt.isActive', 1);
			$this->db->group_by('mt.topicID');
			$this->db->order_by('mt.topicID');
			
			$query = $this->db->get();
			return $query->result_array();
		}

		function getExpertList($topicID){
			$this->db->select('*'); 
			$this->db->from('tbl_cyberits_s_topic st');
			$this->db->join('tbl_cyberits_m_doctors md', 'md.doctorID = st.doctorID');
			$this->db->join('tbl_cyberits_m_users mu', 'mu.userID = md.userID');
			$this->db->where('st.isActive', 1);
			$this->db->where('md.isActive', 1);
			$this->db->where('mu.isActive', 1);
			$this->db->where('st.topicID', $topicID);
			
			$query = $this->db->get();
			return $query->result_array();	
		}
		
		function getSpecificExpertOnlineStatus($userID){
			$this->db->select('mu.isOnline'); 
			$this->db->from('tbl_cyberits_s_topic st');
			$this->db->join('tbl_cyberits_m_doctors md', 'md.doctorID = st.doctorID');
			$this->db->join('tbl_cyberits_m_users mu', 'mu.userID = md.userID');
			$this->db->where('st.isActive', 1);
			$this->db->where('md.isActive', 1);
			$this->db->where('mu.isActive', 1);
			$this->db->where('mu.userID', $userID);
			
			$query = $this->db->get();
			return $query->row();
		}
	}
?>