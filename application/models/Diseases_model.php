<?php 
	class Diseases_model extends CI_Model{
		
		function getDiseaseList(){
			$this->db->select('*');
            $this->db->from('tbl_cyberits_m_diseases d');
            $this->db->where('d.isActive', 1);
            $query = $this->db->get();
            return $query->result_array();
		}

		function getDiseaseDetail($id){
			$this->db->select('*');
            $this->db->from('tbl_cyberits_m_diseases d');           
            $this->db->where('d.isActive', 1);
            $this->db->where('d.diseaseID', $id);
            $query = $this->db->get();
            return $query->result_array();	
		}

        function getDiseaseByIdWithoutIsacitve($id){
            $this->db->select('*');
            $this->db->from('tbl_cyberits_m_diseases d');
            $this->db->where('d.diseaseID', $id);
            $query = $this->db->get();
            return $query->row();
        }

		function getDiseaseSymptomp($id){
			$this->db->select('symptompName'); 
			$this->db->from('tbl_cyberits_s_diseasesymptomps ds');
			$this->db->join('tbl_cyberits_m_symptomps s', 'ds.symptompID=s.symptompID');
			$this->db->where('ds.isActive', 1);
			$this->db->where('ds.diseaseID',$id);
			$query = $this->db->get();
            return $query->result_array();
		}

        function getDiseaseAutocomplete($search){
            $this->db->select('diseaseName');
            $this->db->from('tbl_cyberits_m_diseases d');
            $this->db->like('d.diseaseName', $search);

            $this->db->limit(10);
            $query = $this->db->get();
            return $query->result_array();
        }

        function checkDisease($search_name){
            $this->db->select('diseaseID as id');
            $this->db->from('tbl_cyberits_m_diseases a');
            $this->db->where('a.diseaseName', $search_name);

            $query = $this->db->get();
            return $query->row();
        }

        function createDisease($data){
            $this->db->insert('tbl_cyberits_m_diseases',$data);
            $result=$this->db->insert_id();
            return $result;
        }

		function getDiagnosisResult($arraySymptomp){
			$sql_string="SELECT d.diseaseID, d.diseaseName, d.TotalWeigth, SUM(z.weight) as Weight, CAST(SUM(z.weight) AS DECIMAL)/CAST(d.TotalWeigth AS DECIMAL)*100 AS Percentage FROM 
						(SELECT a.diseaseID, a.diseaseName , SUM(b.weight) as TotalWeigth
						FROM tbl_cyberits_m_diseases a 
						JOIN tbl_cyberits_s_diseasesymptomps b ON b.diseaseID=a.diseaseID
						GROUP BY diseaseID) as d 
						JOIN tbl_cyberits_s_diseasesymptomps z ON z.diseaseID=d.diseaseID ";
			$sql_where="WHERE ";
			for ($i=0; $i < sizeof($arraySymptomp)-1; $i++) { 
				if($i==0){
					$sql_where = $sql_where."z.symptompID=".$arraySymptomp[$i]." ";
				}
				else{
					$sql_where = $sql_where."OR z.symptompID=".$arraySymptomp[$i]." ";	
				}
			}													
			$sql_group="GROUP BY d.diseaseID, d.diseaseName, d.TotalWeigth ";
			$query_string = $sql_string.$sql_where.$sql_group;
			
			return $this->db->query($query_string)->result_array();
			}		
	}
 ?>