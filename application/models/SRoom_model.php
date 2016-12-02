<?php  
	class SRoom_model extends CI_Model{

		function getRoomID($topicID, $patientID, $expertID){
			$this->db->select('sRoomID');
	        $this->db->from('tbl_cyberits_s_room');
	        $this->db->where('topicID',$topicID);
	        $this->db->where('patientID',$patientID);
	        $this->db->where('doctorID',$expertID);
	        $query = $this->db->get();
	        return $query->row();
		}

		function insertRoom($data){
	        $this->db->insert('tbl_cyberits_s_room', $data);
	        return $this->db->insert_id();
	    }

	    function getUserListByDoctorID($expertID){
	    	$this->db->select('mp.patientID, , mp.patientName, mt.topicID, mt.topicName, sr.RecentChat');
	        $this->db->from('tbl_cyberits_s_room sr');
	        $this->db->join('tbl_cyberits_m_patients mp', 'mp.patientID = sr.patientID');
	        $this->db->join('tbl_cyberits_m_topics mt', 'mt.topicID = sr.topicID');
	        $this->db->where('sr.doctorID',$expertID);
	        $query = $this->db->get();
	        return $query->result_array();
	    }

	    function updateRoom($data, $sroomID){
	    	$this->db->where('sRoomID',$sroomID);
	    	$this->db->update('tbl_cyberits_s_room', $data);
	        return $this->db->affected_rows();
	    }

	    function getUserListByPatientID($patientID){
	    	$this->db->select('md.doctorID, , md.doctorName, mt.topicID, mt.topicName, sr.RecentChat');
	        $this->db->from('tbl_cyberits_s_room sr');
	        $this->db->join('tbl_cyberits_m_doctors md', 'md.doctorID = sr.doctorID');
	        $this->db->join('tbl_cyberits_m_topics mt', 'mt.topicID = sr.topicID');
	        $this->db->where('sr.patientID',$patientID);
	        $query = $this->db->get();
	        return $query->result_array();
	    }

	}
?>