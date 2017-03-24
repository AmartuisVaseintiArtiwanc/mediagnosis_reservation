<?php  
	class SRoom_model extends CI_Model{
		
		var $column_order = array('sr.sRoomID','patientName','doctorName', 'topicName', 'recentChat','sr.sRoomID', 'sr.isActive','sr.lastUpdated'); //set column field database for datatable orderable
		var $column_search = array('patientName', 'doctorName', 'topicName', 'isActive');

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
	    	$this->db->select('md.doctorID, md.sip, md.userID, md.doctorName, md.phoneNumber, mt.topicID, mt.topicName, sr.RecentChat, mu.isOnline, mu.userImage');
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
		
		function getReportedChatListData ($searchText,$orderByColumnIndex,$orderDir, $start,$limit){
			$this->_dataReportedChatQuery($searchText,$orderByColumnIndex,$orderDir);

			// LIMIT
			if($limit!=null || $start!=null){
				$this->db->limit($limit, $start);
			}
			$query = $this->db->get();
			return $query->result_array();

		}
		
		function countReportedChatFiltered($searchText){
			$this->_dataReportedChatQuery($searchText,null,null);
			$query = $this->db->get();
			return $query->num_rows();
		}

		public function countReportedChatAll(){
			$this->db->from('tbl_cyberits_s_room sr');
			$this->db->join('tbl_cyberits_m_patients mp', 'mp.patientID = sr.patientID');
			$this->db->join('tbl_cyberits_m_doctors md', 'md.doctorID = sr.doctorID');
			$this->db->join('tbl_cyberits_m_topics mt', 'mt.topicID = sr.topicID');
			$this->db->where('sr.isReported',1);
			return $this->db->count_all_results();
		}
		
		function _dataReportedChatQuery($searchText,$orderByColumnIndex,$orderDir){
			$this->db->select('*');
			$this->db->from('tbl_cyberits_s_room sr');
			$this->db->join('tbl_cyberits_m_patients mp', 'mp.patientID = sr.patientID');
			$this->db->join('tbl_cyberits_m_doctors md', 'md.doctorID = sr.doctorID');
			$this->db->join('tbl_cyberits_m_topics mt', 'mt.topicID = sr.topicID');

			//WHERE
			$i = 0;
			if($searchText != null && $searchText != ""){
				//Search By Each Column that define in $column_search
				foreach ($this->column_search as $item){
					// first loop
					if($i===0){
						$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
						$this->db->like($item, $searchText);
					}
					else {
						$this->db->or_like($item, $searchText);
					}

					if(count($this->column_search) - 1 == $i) //last loop
						$this->db->group_end(); //close bracket

					$i++;
				}
			}
			$this->db->where('sr.isReported',1);

			//Order by
			if($orderByColumnIndex != null && $orderDir != null ) {
				$this->db->order_by($this->column_order[$orderByColumnIndex], $orderDir);
			}
		}

	}
?>