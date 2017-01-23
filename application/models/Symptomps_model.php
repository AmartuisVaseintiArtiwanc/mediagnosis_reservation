<?php 
	class Symptomps_model extends CI_Model{
		
		function getSymptompList(){
			$this->db->select('*');
            $this->db->from('tbl_cyberits_m_symptomps s');
            $this->db->where('s.isActive', 1);
            $query = $this->db->get();
            return $query->result_array();
		}

        var $column_order = array('symptompID','symptompName',null); //set column field database for datatable orderable
        var $column_search = array('symptompName'); //set column field database for datatable searchable just firstname ,

        function getSymptompListData ($searchText,$orderByColumnIndex,$orderDir, $start,$limit){
            $this->_dataSymptompQuery($searchText,$orderByColumnIndex,$orderDir);
            // LIMIT
            if($limit!=null || $start!=null){
                $this->db->limit($limit, $start);
            }
            $query = $this->db->get();
            return $query->result_array();

        }

        function count_filtered($searchText){
            $this->_dataSymptompQuery($searchText,null,null);
            $query = $this->db->get();
            return $query->num_rows();
        }

        public function count_all(){
            $this->db->from("tbl_cyberits_m_symptomps");
            return $this->db->count_all_results();
        }

        function _dataSymptompQuery($searchText,$orderByColumnIndex,$orderDir){
            $this->db->select('*');
            $this->db->from('tbl_cyberits_m_symptomps a');

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

        function getSymptompListBySearch($start,$limit, $search_name){
            $user = $this->session->userdata('id_user');
            $this->db->select('*');
            $this->db->from('tbl_cyberits_m_symptomps a');
            $this->db->like('a.symptompName', $search_name);

            $this->db->limit($limit, $start);

            $this->db->order_by('a.symptompName','asc');

            $query = $this->db->get();
            return $query->result_array();
        }

        function countSymptompList($sWhere){

            $query = $this->db->query("
            SELECT symptompID
            FROM tbl_cyberits_m_symptomps
			$sWhere
        ");

            return $query->num_rows();
        }

        function countSymptompListBySearch($search_name){
            $this->db->select('*');
            $this->db->from('tbl_cyberits_m_symptomps a');
            $this->db->like('a.symptompName', $search_name);
            return $this->db->count_all_results();
        }

        function getSymptompByName($name, $isEdit, $old_data){
            $this->db->select('*');
            $this->db->from('tbl_cyberits_m_symptomps a');
            $this->db->where('symptompName',$name);
            if($isEdit){
                $this->db->where('symptompName != ', $old_data);
            }
            $query = $this->db->get();
            return $query->row();
        }

        function getSymptompByID($id){
            $this->db->select('*');
            $this->db->from('tbl_cyberits_m_symptomps a');
            $this->db->where('symptompID',$id);
            $query = $this->db->get();
            return $query->row();
        }

        function createSymptomp($data){
            $this->db->insert('tbl_cyberits_m_symptomps',$data);
            $result=$this->db->affected_rows();
            return $result;
        }

        function updateSymptomp($data,$id){
            $this->db->where('symptompID',$id);
            $this->db->update('tbl_cyberits_m_symptomps',$data);
            $result=$this->db->affected_rows();
            return $result;
        }

        function deleteSymptomp($id){
            $this->db->where('symptompID',$id);
            $this->db->delete('tbl_cyberits_m_symptomps');
        }
	}
 ?>