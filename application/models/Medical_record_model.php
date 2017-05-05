<?php

class Medical_record_model extends CI_Model {

    function getMedicalRecordListByPatient($patientID){
        $this->db->select('mr.medicalRecordID,doc.doctorID, doc.doctorName, mr.patientID,
         mr.detailReservationID, cl.clinicName, pl.poliName, dis.diseaseName, hr.created');
        $this->db->from('tbl_cyberits_t_medical_record mr');
        $this->db->join('tbl_cyberits_t_detail_medical_record  dmr', 'dmr.medicalRecordID = mr.medicalRecordID');
        $this->db->join('tbl_cyberits_m_diseases  dis', 'dmr.workingDiagnose = dis.diseaseID');
        $this->db->join('tbl_cyberits_t_detail_reservation  dr', 'mr.detailReservationID = dr.detailReservationID');
        $this->db->join('tbl_cyberits_t_header_reservation  hr', 'dr.reservationID = hr.reservationID');
        $this->db->join('tbl_cyberits_m_clinics  cl', 'cl.clinicID = hr.clinicID');
        $this->db->join('tbl_cyberits_m_poli pl', 'pl.poliID = hr.poliID');
        $this->db->join('tbl_cyberits_m_doctors  doc', 'dr.doctorID = doc.doctorID');
        $this->db->where('mr.patientID', $patientID);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getMedicalRecordListByPatientByDate($patientID, $date){
        $this->db->select('mr.medicalRecordID,doc.doctorID, doc.doctorName, mr.patientID,
         mr.detailReservationID, cl.clinicName, pl.poliName, dis.diseaseName, hr.created');
        $this->db->from('tbl_cyberits_t_medical_record mr');
        $this->db->join('tbl_cyberits_t_detail_medical_record  dmr', 'dmr.medicalRecordID = mr.medicalRecordID');
        $this->db->join('tbl_cyberits_m_diseases  dis', 'dmr.workingDiagnose = dis.diseaseID');
        $this->db->join('tbl_cyberits_t_detail_reservation  dr', 'mr.detailReservationID = dr.detailReservationID');
        $this->db->join('tbl_cyberits_t_header_reservation  hr', 'dr.reservationID = hr.reservationID');
        $this->db->join('tbl_cyberits_m_clinics  cl', 'cl.clinicID = hr.clinicID');
        $this->db->join('tbl_cyberits_m_poli pl', 'pl.poliID = hr.poliID');
        $this->db->join('tbl_cyberits_m_doctors  doc', 'dr.doctorID = doc.doctorID');
        $this->db->where('mr.patientID', $patientID);
        $this->db->like('hr.created', $date);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getMedicalRecordListByPatientByPeriode($patientID, $startDate, $endDate){
        $this->db->select('mr.medicalRecordID,doc.doctorID, doc.doctorName, mr.patientID,
         mr.detailReservationID, cl.clinicName, pl.poliName, dis.diseaseName, hr.created');
        $this->db->from('tbl_cyberits_t_medical_record mr');
        $this->db->join('tbl_cyberits_t_detail_medical_record  dmr', 'dmr.medicalRecordID = mr.medicalRecordID');
        $this->db->join('tbl_cyberits_m_diseases  dis', 'dmr.workingDiagnose = dis.diseaseID');
        $this->db->join('tbl_cyberits_t_detail_reservation  dr', 'mr.detailReservationID = dr.detailReservationID');
        $this->db->join('tbl_cyberits_t_header_reservation  hr', 'dr.reservationID = hr.reservationID');
        $this->db->join('tbl_cyberits_m_clinics  cl', 'cl.clinicID = hr.clinicID');
        $this->db->join('tbl_cyberits_m_poli pl', 'pl.poliID = hr.poliID');
        $this->db->join('tbl_cyberits_m_doctors  doc', 'dr.doctorID = doc.doctorID');
        $this->db->where('mr.patientID', $patientID);

        $this->db->where('hr.created >=',$startDate);
        $this->db->where('hr.created <=',$endDate);

        $query = $this->db->get();
        return $query->result_array();
    }

    function getMedicalRecordByID($medicalRecordID){
        $this->db->select('mr.medicalRecordID, mr.tPatientProfileID, mr.patientID, mr.detailReservationID, dr.reservationID, cl.clinicID,
          pl.poliID, doc.doctorID, cl.clinicName, pl.poliName, doc.doctorName,
          b.patientName, b.ktpID, b.bpjsID, b.address, b.gender, b.participantStatus, b.participantType,
          dr.created');
        $this->db->from('tbl_cyberits_t_medical_record mr');
        $this->db->join('tbl_cyberits_t_patient_profile  b', 'mr.tPatientProfileID = b.tPatientProfileID');
        $this->db->join('tbl_cyberits_t_detail_reservation  dr', 'mr.detailReservationID = dr.detailReservationID');
        $this->db->join('tbl_cyberits_t_header_reservation  hr', 'dr.reservationID = hr.reservationID');
        $this->db->join('tbl_cyberits_m_clinics  cl', 'cl.clinicID = hr.clinicID');
        $this->db->join('tbl_cyberits_m_poli pl', 'pl.poliID = hr.poliID');
        $this->db->join('tbl_cyberits_m_doctors  doc', 'dr.doctorID = doc.doctorID');
        $this->db->where('mr.medicalRecordID', $medicalRecordID);
        $query = $this->db->get();
        return $query->row();
    }

    function getMedicalRecordListBySuperUser($superUserID){
        #Create where clause
        $this->db->select('cl.clinicID');
        $this->db->from('tbl_cyberits_m_clinics cl');
        $this->db->join('tbl_cyberits_m_users usr', 'cl.userID = usr.userID');
        $this->db->where("usr.superUserID",$superUserID);
        $where_clause = $this->db->get_compiled_select();

        #Create main query
		$this->db->distinct();
        $this->db->select('d.patientName, a.patientID, DATE_FORMAT(d.dob,"%d %M %Y") as dob, d.gender, d.address,d.mrisNumber, c.clinicName');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->join('tbl_cyberits_t_header_reservation b', 'a.reservationID = b.reservationID');
        $this->db->join('tbl_cyberits_m_patients d', 'a.patientID = d.patientID');
		$this->db->join('tbl_cyberits_m_clinics c', 'c.clinicID = b.clinicID');
        $this->db->where('a.status',"done");
        $this->db->where("b.clinicID IN ($where_clause)", NULL, FALSE);
        $this->db->order_by('d.patientName','asc');
        $this->db->group_by('a.patientID');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getMedicalRecordListBySuperUserAndPatient($superUserID,$patientID){
        #Create where clause
        $this->db->select('cl.clinicID');
        $this->db->from('tbl_cyberits_m_clinics cl');
        $this->db->join('tbl_cyberits_m_users usr', 'cl.userID = usr.userID');
        $this->db->where("usr.superUserID",$superUserID);
        $where_clause = $this->db->get_compiled_select();

        #Create main query
        $this->db->select('a.detailReservationID, a.reservationID, mr.medicalRecordID, a.patientID, a.reservationType,
        DATE_FORMAT(a.created,"%d %M %Y") as created, d.clinicName, e.doctorName');
        $this->db->from('tbl_cyberits_t_detail_reservation a');
        $this->db->join('tbl_cyberits_t_medical_record mr','mr.detailReservationID = a.detailReservationID');
        $this->db->join('tbl_cyberits_t_header_reservation b', 'a.reservationID = b.reservationID');
        $this->db->join('tbl_cyberits_m_clinics d', 'b.clinicID = d.clinicID');
        $this->db->join('tbl_cyberits_m_doctors e', 'a.doctorID = e.doctorID');
        $this->db->where('a.status',"done");
        $this->db->where('a.patientID',$patientID);
        $this->db->where("b.clinicID IN ($where_clause)", NULL, FALSE);
        $this->db->order_by('a.created','desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    function checkMedication($search_name){
        $this->db->select('medicationID');
        $this->db->from('tbl_cyberits_m_medication a');
        $this->db->where('a.medicationText', $search_name);

        $query = $this->db->get();
        return $query->row();
    }

    function createMedicalRecordHeader($data){
        $this->db->insert('tbl_cyberits_t_medical_record',$data);
        $result=$this->db->insert_id();
        return $result;
    }

}