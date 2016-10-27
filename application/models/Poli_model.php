<?php

class Poli_model extends CI_Model {

    var $column_order = array('poliID','poliName',null); //set column field database for datatable orderable
    var $column_search = array('poliName'); //set column field database for datatable searchable just firstname ,

    function getPoliListData ($searchText,$orderByColumnIndex,$orderDir, $start,$limit){
        $this->_dataPoliQuery($searchText,$orderByColumnIndex,$orderDir);
        //$this->db->where('a.createdBy',$this->session->userdata('superUserID'));

        // LIMIT
        if($limit!=null || $start!=null){
            $this->db->limit($limit, $start);
        }
        $query = $this->db->get();
        return $query->result_array();

    }

    function count_filtered($searchText){
        $this->_dataPoliQuery($searchText,null,null);
        //$this->db->where('a.createdBy',$this->session->userdata('superUserID'));

        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all(){
        $this->db->from("tbl_cyberits_m_poli a");
        //$this->db->where('a.createdBy',$this->session->userdata('superUserID'));

        return $this->db->count_all_results();
    }

    function _dataPoliQuery($searchText,$orderByColumnIndex,$orderDir){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_poli a');
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

    function getPoliByName($name, $isEdit, $old_data){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_poli a');
        $this->db->where('poliName',$name);
        //$this->db->where('a.createdBy',$this->session->userdata('superUserID'));
        if($isEdit){
            $this->db->where('poliName != ', $old_data);
        }
        $query = $this->db->get();
        return $query->row();
    }

    function getPoliByID($id){
        $this->db->select('*');
        $this->db->from('tbl_cyberits_m_poli a');
        $this->db->where('poliID',$id);
        //$this->db->where('a.createdBy',$this->session->userdata('superUserID'));
        $query = $this->db->get();
        return $query->row();
    }
    
    function createPoli($data){
        $this->db->insert('tbl_cyberits_m_poli',$data);	
		$result=$this->db->affected_rows();
		return $result;
    }
    
   	function updatePoli($data,$id){
		$this->db->where('poliID',$id);
		$this->db->update('tbl_cyberits_m_poli',$data);
		$result=$this->db->affected_rows();
		return $result;
	}
    
    function deletePoli($id){
    	$this->db->where('poliID',$id);
        $this->db->delete('tbl_cyberits_m_poli');
	}
}