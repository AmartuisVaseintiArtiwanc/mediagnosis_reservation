<?php

class SSchedule_model extends CI_Model {

    var $column_order = array('sClinicID','clinicName','poliName',null); //set column field database for datatable orderable
    var $column_search = array('clinicName','poliName'); //set column field database for datatable searchable just firstname ,

    function getScheduleListData ($superUserID,$searchText,$orderByColumnIndex,$orderDir, $start,$limit, $clinic){
        $this->_dataSettingQuery($searchText,$orderByColumnIndex,$orderDir);
        // LIMIT
        if($limit!=null || $start!=null){
            $this->db->limit($limit, $start);
        }

        $this->db->where('a.createdBy',$superUserID);
        //ROLE
        $role = $this->session->userdata('role');
        if($role=="admin"){
            $this->db->where('a.clinicID',$clinic);
        }

        $query = $this->db->get();
        return $query->result_array();

    }

    function count_filtered($superUserID,$searchText,$clinic){
        $this->_dataSettingQuery($searchText,null,null);

        $this->db->where('a.createdBy',$superUserID);
        //ROLE
        $role = $this->session->userdata('role');
        if($role=="admin"){
            $this->db->where('a.clinicID',$clinic);
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($superUserID,$clinic){
        $this->db->from("tbl_cyberits_s_clinic a");

        $this->db->where('a.createdBy',$superUserID);
        //ROLE
        $role = $this->session->userdata('role');
        if($role=="admin"){
            $this->db->where('a.clinicID',$clinic);
        }
        return $this->db->count_all_results();
    }

    function _dataSettingQuery($searchText,$orderByColumnIndex,$orderDir){
        $this->db->select('sClinicID, clinicName, poliName, a.clinicID, a.poliID');
        $this->db->from('tbl_cyberits_s_clinic a');
        $this->db->join('tbl_cyberits_m_poli b','a.poliID = b.poliID');
        $this->db->join('tbl_cyberits_m_clinics c','a.clinicID = c.clinicID');

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

    function getHeaderData($clinicID, $poliID, $superUserID){
        $this->db->select('clinicName, poliName, a.clinicID, a.poliID');
        $this->db->from('tbl_cyberits_s_clinic a');
        $this->db->join('tbl_cyberits_m_poli b','a.poliID = b.poliID');
        $this->db->join('tbl_cyberits_m_clinics c','a.clinicID = c.clinicID');
        $this->db->where('a.clinicID',$clinicID);
        $this->db->where('a.poliID',$poliID);
        $this->db->where('a.createdBy',$superUserID);
        $query = $this->db->get();
        return $query->row();
    }

    function getSettingDetailSchedule($clinicID,$poliID){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_s_schedule a');
        $this->db->where('a.clinicID',$clinicID);
        $this->db->where('a.poliID',$poliID);
        $this->db->order_by('a.scheduleID','asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    function createSettingSchedule($data){
        $this->db->insert('tbl_cyberits_s_schedule',$data);
        $result=$this->db->affected_rows();
        return $result;
    }

    function updateSettingSchedule($scheduleID,$data){
        $this->db->where('scheduleID',$scheduleID);
        $this->db->update('tbl_cyberits_s_schedule',$data);
        $result=$this->db->affected_rows();
        return $result;
    }

    function deleteSettingSchedule($clinicID,$poliID){
        $this->db->where('poliID',$poliID);
        $this->db->where('clinicID',$clinicID);
        $this->db->delete('tbl_cyberits_s_schedule');
    }
}
?>