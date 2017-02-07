<?php

class Patient_model extends CI_Model {

    var $column_order = array('patientID','patientName', 'ktpID', 'bpjsID','isActive', 'mrisNumber','created',null); //set column field database for datatable orderable
    var $column_search = array('patientName', 'ktpID', 'bpjsID'); //set column field database for datatable searchable just firstname ,

    function getPatientByID($id){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_patients a');
        $this->db->where('patientID',$id);
        $query = $this->db->get();
        return $query->row();
    }

    function getPatientByUserID($id){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_patients a');
        $this->db->where('userID',$id);
        $query = $this->db->get();
        return $query->row();
    }

    function insertTransProfilePatient($data){
        $this->db->insert('tbl_cyberits_t_patient_profile', $data);
        return $this->db->insert_id();
    }

    function checkTransProfilePatient($patientID){
        $this->db->select('a.ktpID,a.bpjsID,a.phoneNumber,a.address,a.dob,b.tPatientProfileID');
        $this->db->from('tbl_cyberits_m_patients a');
        $this->db->join('tbl_cyberits_t_patient_profile  b', 'a.patientID = b.patientID');
        $this->db->where('b.patientID',$patientID);
        $this->db->where('a.ktpID','b.ktpID');
        $this->db->where('a.bpjsID','b.bpjsID');
        $this->db->where('a.phoneNumber','b.phoneNumber');
        $this->db->where('a.address','b.address');
        $this->db->where('a.dob','b.dob');
        $query = $this->db->get();
        return $query->row();
    }

    function getPatientList(){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_patients');
        $this->db->where('isActive',1);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getPatientIDByUserID($userID){
        $this->db->select('patientID');
        $this->db->from('tbl_cyberits_m_patients');
        $this->db->where('isActive',1);
        $this->db->where("userID", $userID);
        $query = $this->db->get();
        return $query->row();
    }

    function updatePatient($id,$data){
        $this->db->where('userID',$id);
        $this->db->update('tbl_cyberits_m_patients',$data);
        $result=$this->db->affected_rows();
        return $result;
    }

    public function checkTemporaryPatient($idNumber){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_patients');
        $this->db->where('isActive', 1);
        $this->db->where('isTemp', 1);
        $this->db->where('ktpID', $idNumber);
        $query = $this->db->get();

        if($query->num_rows()>0){
            return 1; // allready exist
        }else{
            return 0; //blom ada
        }
    }

    public function checkBPJSIDExists($bpjsID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_patients');
        $this->db->where('isActive', 1);
        $this->db->where('isTemp', 0);
        $this->db->where('bpjsID', $bpjsID);
        $query = $this->db->get();

        if($query->num_rows()>0){
            return 1; // allready exist
        }else{
            return 0; //blom ada
        }
    }

    public function checkBPJSIDExistsBYPatientID($bpjsID, $patientID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_patients');
        $this->db->where('isActive', 1);
        $this->db->where('isTemp', 0);
        $this->db->where('bpjsID', $bpjsID);
        $this->db->where('patientID !=', $patientID);
        $query = $this->db->get();

        if($query->num_rows()>0){
            return 1; // allready exist
        }else{
            return 0; //blom ada
        }
    }

    public function checkIDNumberExists($idNumber){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_patients');
        $this->db->where('isActive', 1);
        $this->db->where('isTemp', 0);
        $this->db->where('ktpID', $idNumber);
        $query = $this->db->get();

        if($query->num_rows()>0){
            return 1; // allready exist
        }else{
            return 0; //blom ada
        }
    }

    public function checkIDNumberExistsByPatientID($idNumber, $patientID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_patients');
        $this->db->where('isActive', 1);
        $this->db->where('isTemp', 0);
        $this->db->where('ktpID', $idNumber);
        $this->db->where('patientID !=', $patientID);
        $query = $this->db->get();

        if($query->num_rows()>0){
            return 1; // allready exist
        }else{
            return 0; //blom ada
        }
    }

    public function getTemporaryPatientID($idNumber){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_patients');
        $this->db->where('isActive', 1);
        $this->db->where('isTemp', 1);
        $this->db->where('ktpID', $idNumber);
        $query = $this->db->get();

        return $query->row();
    }

    public function insertPatient($data){
        $this->db->insert('tbl_cyberits_m_patients', $data);
        return $this->db->insert_id();

    }

    function updatePatientByPatientID($patientID,$data){
        $this->db->where('patientID',$patientID);
        $this->db->update('tbl_cyberits_m_patients',$data);
        $result=$this->db->affected_rows();
        return $result;
    }

    function getPatientListData ($searchText,$orderByColumnIndex,$orderDir, $start,$limit, $clinicID){
        $this->_dataPatientQuery($searchText,$orderByColumnIndex,$orderDir);
        $this->db->where('a.clinicID', $clinicID);
        // LIMIT
        if($limit!=null || $start!=null){
            $this->db->limit($limit, $start);
        }
        $query = $this->db->get();
        return $query->result_array();

    }

    function _dataPatientQuery($searchText,$orderByColumnIndex,$orderDir){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_patients a');

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

    function count_filtered($searchText, $clinicID){
        $this->_dataPatientQuery($searchText,null,null);
        $this->db->where('a.clinicID', $clinicID);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($clinicID){
        $this->db->from("tbl_cyberits_m_patients a");
        $this->db->where('a.clinicID', $clinicID);
        return $this->db->count_all_results();
    }
	
	public function check_mris_number($mrisNum){
		$this->db->select("*");
		$this->db->from("tbl_cyberits_m_patients a");
		$this->db->where('a.mrisNumber', $mrisNum);
		$query = $this->db->get();
		
		if($query->num_rows()>0){
            return 1; // allready exist
        }else{
            return 0; //blom ada
        }
	}
	
	public function get_last_mris_number(){
		$this->db->select("MAX(mrisNumber) as lastMrisNumber");
		$this->db->from("tbl_cyberits_m_patients a");
		//$this->db->where('a.isTemp', 0);
		$query = $this->db->get();
		
		return $query->row();
	}
    
}