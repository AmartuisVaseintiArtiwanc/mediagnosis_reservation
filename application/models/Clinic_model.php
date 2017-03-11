<?php

class Clinic_Model extends CI_Model {

    var $column_order = array('clinicID','clinicName',null); //set column field database for datatable orderable
    var $column_search = array('clinicName'); //set column field database for datatable searchable just firstname ,
    // Rating Clinic
    var $column_rating_order = array('a.clinicID','a.clinicName','a.isActive','c.userName','a.rating','a.lastUpdatedRating'); //set column field database for datatable orderable
    var $column_rating_search = array('clinicName'); //set column field database for datatable searchable just firstname ,


    function getClinicList($start,$limit) //$num=10, $start=0
	{		
		$this->db->select('*'); 
		$this->db->from('tbl_cyberits_m_clinics a');
		
		if($limit!=null || $start!=null){
			$this->db->limit($limit, $start);
		}
		$this->db->order_by('a.clinicName','asc');
		
		$query = $this->db->get();
		return $query->result_array();
	}

    function getClinicListData ($superUserID,$searchText,$orderByColumnIndex,$orderDir, $start,$limit){
        $this->_dataClinicQuery($searchText,$orderByColumnIndex,$orderDir);
        $this->db->where('a.createdBy',$superUserID);
        // LIMIT
        if($limit!=null || $start!=null){
            $this->db->limit($limit, $start);
        }
        $query = $this->db->get();
        return $query->result_array();

    }

    function count_filtered($superUserID,$searchText){
        $this->_dataClinicQuery($searchText,null,null);
        $this->db->where('a.createdBy',$superUserID);

        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($superUserID){
        $this->db->from("tbl_cyberits_m_clinics a");
        $this->db->where('a.createdBy',$superUserID);

        return $this->db->count_all_results();
    }

    function _dataClinicQuery($searchText,$orderByColumnIndex,$orderDir){
        $this->db->select('a.clinicID, a.clinicName, b.userID, b.userName, b.email,
        a.isActive, a.created, a.lastUpdated, a.createdBy, a.lastUpdatedBy');
        $this->db->from('tbl_cyberits_m_clinics a');
        $this->db->join('tbl_cyberits_m_users b',"a.userID = b.userID");

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

        //Order by
        if($orderByColumnIndex != null && $orderDir != null ) {
            $this->db->order_by($this->column_order[$orderByColumnIndex], $orderDir);
        }
    }

    function getClinicByName($name, $isEdit, $old_data, $superUserID=""){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_clinics a');
        $this->db->where('clinicName',$name);

        $this->db->where('a.createdBy',$superUserID);

        if($isEdit){
            $this->db->where('clinicName != ', $old_data);
        }
        $query = $this->db->get();
        return $query->row();
    }

    function checkDupicateClinicName($name, $isEdit, $clinicID, $superUserID=""){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_clinics a');
        $this->db->where('a.clinicName',$name);

        $this->db->where('a.createdBy',$superUserID);

        if($isEdit){
            $this->db->where('a.clinicID != ', $clinicID);
        }
        $query = $this->db->get();
        return $query->row();
    }

    function getClinicByID($id,$superUserID=""){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_clinics a');
        $this->db->where('clinicID',$id);
        $this->db->where('a.isActive',1);

        // Check Role
        $role = $this->session->userdata('role');
        if($role != "mediagnosis_admin"){
            $superUserID = $this->session->userdata('superUserID');
        }

        $this->db->where('a.createdBy',$superUserID);

        $query = $this->db->get();
        return $query->row();
    }

    function getClinicByUserID($id){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_clinics a');
        $this->db->where('userID',$id);
        $this->db->where('a.createdBy',$this->session->userdata('superUserID'));
        $this->db->where('a.isActive',1);
        $query = $this->db->get();
        return $query->row();
    }

    function getClinicByPlaceID($id){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_clinics a');
        $this->db->where('placeID',$id);
        $query = $this->db->get();
        return $query->row();
    }

    function getClinicByUserID_Mobile($id){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_clinics a');
        $this->db->where('a.clinicID',$id);
        $query = $this->db->get();
        return $query->row();
    }

    function getRatingClinicListData ($searchText,$orderByColumnIndex,$orderDir, $start,$limit){
        $this->_dataRatingClinicQuery($searchText,$orderByColumnIndex,$orderDir);

        // LIMIT
        if($limit!=null || $start!=null){
            $this->db->limit($limit, $start);
        }
        $query = $this->db->get();
        return $query->result_array();

    }

    function count_rating_clinic_filtered($searchText){
        $this->_dataRatingClinicQuery($searchText,null,null);

        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_rating_clinic_all(){
        $this->db->from("tbl_cyberits_m_clinics a");
        return $this->db->count_all_results();
    }

    function _dataRatingClinicQuery($searchText,$orderByColumnIndex,$orderDir){
        $this->db->select('a.clinicID, a.clinicName, a.rating, a.lastUpdatedRating,
        b.userID, b.userName, b.email, b.superUserID, c.userName as superAdmin,
        a.isActive, a.created, a.lastUpdated, a.createdBy, a.lastUpdatedBy');
        $this->db->from('tbl_cyberits_m_clinics a');
        $this->db->join('tbl_cyberits_m_users b',"a.userID = b.userID");
        $this->db->join('tbl_cyberits_m_users C',"b.superUserID = c.userID");

        $this->db->where('b.userRole',"admin");

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

        //Order by
        if($orderByColumnIndex != null && $orderDir != null ) {
            $this->db->order_by($this->column_rating_order[$orderByColumnIndex], $orderDir);
        }else{
            $this->db->order_by("b.superUserID", "ASC");
        }
    }

    function createClinic($data){
        $this->db->insert('tbl_cyberits_m_clinics',$data);	
		$result=$this->db->affected_rows();
		return $result;
    }
    
   	function updateClinic($data,$id){
		$this->db->where('clinicID',$id);
		$this->db->update('tbl_cyberits_m_clinics',$data);
		$result=$this->db->affected_rows();
		return $result;
	}
    
    function deleteClinic($id){
    	$this->db->where('clinicID',$id);
        $this->db->delete('tbl_cyberits_m_clinics');
	}
}