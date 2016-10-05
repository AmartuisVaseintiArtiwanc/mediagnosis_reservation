<?php

class SClinic_Model extends CI_Model {

    function getSettingDetailClinic($clinicID){
		$this->db->select('*');
        $this->db->from('tbl_cyberits_s_clinic a');
		$this->db->join('tbl_cyberits_m_poli b', 'a.poliID = b.poliID');
        $this->db->where('a.clinicID',$clinicID);
        $this->db->where('a.createdBy',$this->session->userdata('superUserID'));
		$this->db->order_by('b.poliName','asc');
        $query = $this->db->get();
		return $query->result_array();
	}

    function getClinicListByID($clinicID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_s_clinic a');
        $this->db->join('tbl_cyberits_m_poli b', 'a.poliID = b.poliID');
        $this->db->where('a.clinicID',$clinicID);
        $this->db->where('a.isActive',1);
        $this->db->order_by('b.poliName','asc');
        $query = $this->db->get();
        return $query->result_array();
    }
	
    function createSettingClinic($data){
        $this->db->insert('tbl_cyberits_s_clinic',$data);	
		$result=$this->db->insert_id();
		return $result;
    }
    
   	function updateSettingClinic($poliID,$clinicID,$data){
		$this->db->where('poliID',$poliID);
		$this->db->where('clinicID',$clinicID);
		$this->db->update('tbl_cyberits_s_clinic',$data);
		$result=$this->db->affected_rows();
		return $result;
	}
    
    function deleteSettingClinic($clinicID,$poliID){
        $this->db->where('poliID',$poliID);
        $this->db->where('clinicID',$clinicID);
        $this->db->delete('tbl_cyberits_s_clinic');
	}
}