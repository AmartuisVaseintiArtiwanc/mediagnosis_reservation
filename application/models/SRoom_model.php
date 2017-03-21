<?php  
	class SRoom_model extends CI_Model{

		function getRoomID($topicID, $patientID, $expertID){
			$this->db->select('sRoomID');
	        $this->db->from('tbl_cyberits_s_room');
	        $this->db->where('topicID',$topicID);
	        $this->db->where('patientID',$patientID);
	        $this->db->where('doctorID',$expertID);
			$this->db->where("isActive",1);
	        $query = $this->db->get();
	        return $query->row();
		}

		function insertRoom($data){
	        $this->db->insert('tbl_cyberits_s_room', $data);
	        return $this->db->insert_id();
	    }

	    function getUserListByDoctorID($expertID){
	    	$this->db->select('mp.patientID, , mp.patientName, mt.topicID, mt.topicName, sr.RecentChat, mu.isOnline');
	        $this->db->from('tbl_cyberits_s_room sr');
	        $this->db->join('tbl_cyberits_m_patients mp', 'mp.patientID = sr.patientID');
			$this->db->join('tbl_cyberits_m_users mu', 'mu.userID = mp.userID');
	        $this->db->join('tbl_cyberits_m_topics mt', 'mt.topicID = sr.topicID');
	        $this->db->where('sr.doctorID',$expertID);
			$this->db->where("sr.isActive",1);
	        $query = $this->db->get();
	        return $query->result_array();
	    }

	    function updateRoom($data, $sroomID){
	    	$this->db->where('sRoomID',$sroomID);
	    	$this->db->update('tbl_cyberits_s_room', $data);
	        return $this->db->affected_rows();
	    }

	    function getUserListByPatientID($patientID){
	    	$this->db->select('md.doctorID, , md.doctorName,  md.phoneNumber, mt.topicID, mt.topicName, sr.RecentChat, mu.isOnline');
	        $this->db->from('tbl_cyberits_s_room sr');
	        $this->db->join('tbl_cyberits_m_doctors md', 'md.doctorID = sr.doctorID');
			$this->db->join('tbl_cyberits_m_users mu', 'mu.userID = md.userID');
	        $this->db->join('tbl_cyberits_m_topics mt', 'mt.topicID = sr.topicID');
	        $this->db->where('sr.patientID',$patientID);
			$this->db->where("sr.isActive",1);
	        $query = $this->db->get();
	        return $query->result_array();
	    }
		
		function getTokenBySRoomID($sRoomID){
			$this->db->select('md.token AS doctorToken, mp.token AS patientToken, sRoomID, md.userID AS doctorUserID, mp.userID AS patientUserID ,patientName, doctorName, topicID');
	        $this->db->from('tbl_cyberits_s_room sr');
	        $this->db->join('tbl_cyberits_m_doctors md', 'md.doctorID = sr.doctorID');
	        $this->db->join('tbl_cyberits_m_patients mp', 'mp.patientID = sr.patientID');
	        $this->db->where('sr.sRoomID',$sRoomID);
			$this->db->where("sr.isActive",1);
	        $query = $this->db->get();
	        return $query->row();
		}

	}
?>