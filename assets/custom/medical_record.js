
$(document).ready(function(){
    var $base_url =  $("#base-url").val();
    autosize($('textarea'));

    // CLOSE BUTTON ON INPUT TEXT AREA
    $(document).on('click', 'ul.w3-ul-list li span.w3-closebtn-list', function () {
        var $parent = $(this).parent();
        $parent.remove();
    });
    setAutocomplete();

    // KEY PRESS ON SINGLE TEXTAREA
    /*
    $("#main-condition-text").bind("keypress", {}, keypressInBox);
    $("#working-diagnose-text").bind("keypress", {}, keypressInBox);
    function keypressInBox(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) { //Enter keycode
            e.preventDefault();
            var $parent = $(this).parent();
            var $value = $(this).val();
            var $ul = $(this).attr("data-ul");
            var $li = $(this).attr("data-li");

            $(this).addClass("w3-hide");
            $($ul).removeClass("w3-hide");
            $($li).html($value);
        }
    };
    // KET PRESS ON INPUT TEXTAREA LIST
    $("textarea.add-codition-li-text").bind("keypress", {}, keypressInBoxList);
    $("textarea.support-diagnose-li-text").bind("keypress", {}, keypressInBoxList);
    $("textarea.medication-li-text").bind("keypress", {}, keypressInBoxList);
    function keypressInBoxList(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) { //Enter keycode
            e.preventDefault();
            var $parent = $(this).parent();
            var $value = $(this).val();

            $($parent).html($value);
        }
    };
    */

    // CLOSE BUTTON ON INPUT TEXT AREA
    $(".w3-closebtn").click(function(){
        var $element = $(this).attr("data-input-element");
        var $value = $(this).attr("data-input-value");
        var $ul = $(this).attr("data-ul");

        $($ul).addClass("w3-hide");
        $($element).removeClass("w3-hide");
        $($value).attr("data-value",0);
    });

    //ADD NEW ADDITIONAL CONDITION
    $("#btn-add-additional-condition").click(function(e){
        e.preventDefault();

        var $ul = $("#additional-condition-ul");
        var $li = $("<li>", {class: "w3-padding-8", "data-value": "0"});
        var $close_btn = $("<span>", {class: "w3-closebtn w3-closebtn-list w3-large w3-margin-right"}).text("x");
        var $container = $("<div>", {class: "w3-medium w3-padding-medium"});
        var $textarea = $("<textarea>", {class: "w3-input add-codition-li-text"});
        var $br = $("<br>");

        $textarea.appendTo($container);
        $close_btn.appendTo($li);
        $br.appendTo($li);
        $container.appendTo($li);
        $li.appendTo($ul)

        //Autocomplete
        var options2 = {
            url: function(phrase) {
                return $base_url+"/MedicalRecord/getAdditionalConditionList";
            },

            getValue: function(element) {
                return element.additionalConditionText;
            },

            ajaxSettings: {
                dataType: "json",
                method: "POST",
                data: {
                    dataType: "json"
                }
            },

            preparePostData: function(data) {
                data.phrase = $textarea.val();
                return data;
            },
            requestDelay: 400
        };
        $textarea.easyAutocomplete(options2);
    });

    // ADD NEW SUPPORT DIAGNOSE
    $("#btn-add-support-diagnose").click(function(e){
        e.preventDefault();

        var $ul = $("#support-diagnose-ul");
        var $li = $("<li>", {class: "w3-padding-8", "data-value": "0"});
        var $close_btn = $("<span>", {class: "w3-closebtn w3-closebtn-list w3-large w3-margin-right"}).text("x");
        var $container = $("<div>", {class: "w3-medium w3-padding-medium"});
        var $textarea = $("<textarea>", {class: "w3-input support-diagnose-li-text"});
        var $br = $("<br>");

        $textarea.appendTo($container);
        $close_btn.appendTo($li);
        $br.appendTo($li);
        $container.appendTo($li);
        $li.appendTo($ul)

        var options2 = {
            url: function(phrase) {
                return $base_url+"/MedicalRecord/getDiseaseList";
            },

            getValue: function(element) {
                return element.diseaseName;
            },

            ajaxSettings: {
                dataType: "json",
                method: "POST",
                data: {
                    dataType: "json"
                }
            },

            preparePostData: function(data) {
                data.phrase =$textarea.val();
                return data;
            },
            requestDelay: 400
        };
        $textarea.easyAutocomplete(options2);
    });

    // ADD NEW MEDICATION
    $("#btn-add-medication").click(function(e){
        e.preventDefault();

        var $ul = $("#medication-ul");
        var $li = $("<li>", {class: "w3-padding-8", "data-value": "0"});
        var $close_btn = $("<span>", {class: "w3-closebtn w3-closebtn-list w3-large w3-margin-right"}).text("x");
        var $container = $("<div>", {class: "w3-medium w3-padding-medium"});
        var $textarea = $("<textarea>", {class: "w3-input medication-li-text"});
        var $br = $("<br>");

        $textarea.appendTo($container);
        $close_btn.appendTo($li);
        $br.appendTo($li);
        $container.appendTo($li);
        $li.appendTo($ul)

        // Medication
        var options5 = {
            url: function(phrase) {
                return $base_url+"/MedicalRecord/getMedicationList";
            },

            getValue: function(element) {
                return element.medicationText;
            },

            ajaxSettings: {
                dataType: "json",
                method: "POST",
                data: {
                    dataType: "json"
                }
            },

            preparePostData: function(data) {
                data.phrase = $textarea.val();
                return data;
            },
            requestDelay: 400
        };
        $textarea.easyAutocomplete(options5);
    });

    $("#btn-add-support-examination").click(function(e){
        e.preventDefault();

        var $ul = $("#support-examination-ul");
        var $li = $("<li>", {class: "w3-padding-small", "data-value": "0"});
        var $close_btn = $("<span>", {class: "w3-closebtn w3-closebtn-list w3-large w3-margin-right"}).text("x");
        var $container = $("<div>", {class: "w3-row"});
        var $col1= $("<div>", {class: "w3-col m6 w3-padding-small"});
        var $col2= $("<div>", {class: "w3-col m6 w3-padding-small"});
        var $textarea_col = $("<textarea>", {class: "w3-input w3-border support-examination-column"});
        var $textarea_val = $("<textarea>", {class: "w3-input w3-border support-examination-value"});
        var $br = $("<br>");
        var $clear = $("<span>", {class: "w3-clear"});

        $textarea_col.appendTo($col1);
        $textarea_val.appendTo($col2);
        $col1.appendTo($container);
        $col2.appendTo($container);
        $close_btn.appendTo($li);
        $br.appendTo($li);
        $clear.appendTo($li);
        $container.appendTo($li);
        $li.appendTo($ul);

        // Support Examination
        var options6 = {
            url: function(phrase) {
                return $base_url+"/MedicalRecord/getSupportExaminationColumnList";
            },

            getValue: function(element) {
                return element.supportExaminationColumnName;
            },

            ajaxSettings: {
                dataType: "json",
                method: "POST",
                data: {
                    dataType: "json"
                }
            },

            preparePostData: function(data) {
                data.phrase = $textarea_col.val();
                return data;
            },
            requestDelay: 400
        };
        $textarea_col.easyAutocomplete(options6);
    });

    function setAutocomplete(){
        // AUTOCOMPLETE
        // MAIN CONDITION
        var options = {
            url: function(phrase) {
                return $base_url+"/MedicalRecord/getMainConditionList";
            },

            getValue: function(element) {
                return element.mainConditionText;
            },

            ajaxSettings: {
                dataType: "json",
                method: "POST",
                data: {
                    dataType: "json"
                }
            },

            preparePostData: function(data) {
                data.phrase = $("#main-condition-text").val();
                return data;
            },
            requestDelay: 400
        };
        $("#main-condition-text").easyAutocomplete(options);

        //Additional
        var options2 = {
            url: function(phrase) {
                return $base_url+"/MedicalRecord/getAdditionalConditionList";
            },

            getValue: function(element) {
                return element.additionalConditionText;
            },

            ajaxSettings: {
                dataType: "json",
                method: "POST",
                data: {
                    dataType: "json"
                }
            },

            preparePostData: function(data) {
                data.phrase = $(".add-codition-li-text").val();
                return data;
            },
            requestDelay: 400
        };
        $(".add-codition-li-text").easyAutocomplete(options2);

        // Working Diagnosis
        var options3 = {
            url: function(phrase) {
                return $base_url+"/MedicalRecord/getDiseaseList";
            },

            getValue: function(element) {
                return element.diseaseName;
            },

            ajaxSettings: {
                dataType: "json",
                method: "POST",
                data: {
                    dataType: "json"
                }
            },

            preparePostData: function(data) {
                data.phrase = $("#working-diagnose-text").val();
                return data;
            },
            requestDelay: 400
        };
        $("#working-diagnose-text").easyAutocomplete(options3);

        // Support Diagnosis
        var options4 = {
            url: function(phrase) {
                return $base_url+"/MedicalRecord/getDiseaseList";
            },

            getValue: function(element) {
                return element.diseaseName;
            },

            ajaxSettings: {
                dataType: "json",
                method: "POST",
                data: {
                    dataType: "json"
                }
            },

            preparePostData: function(data) {
                data.phrase = $(".support-diagnose-li-text").val();
                return data;
            },
            requestDelay: 400
        };
        $(".support-diagnose-li-text").easyAutocomplete(options4);

        // Medication
        var options5 = {
            url: function(phrase) {
                return $base_url+"/MedicalRecord/getMedicationList";
            },

            getValue: function(element) {
                return element.medicationText;
            },

            ajaxSettings: {
                dataType: "json",
                method: "POST",
                data: {
                    dataType: "json"
                }
            },

            preparePostData: function(data) {
                data.phrase = $(".medication-li-text").val();
                return data;
            },
            requestDelay: 400
        };
        $(".medication-li-text").easyAutocomplete(options5);

        // Support Examination
        var options6 = {
            url: function(phrase) {
                return $base_url+"/MedicalRecord/getSupportExaminationColumnList";
            },

            getValue: function(element) {
                return element.supportExaminationColumnName;
            },

            ajaxSettings: {
                dataType: "json",
                method: "POST",
                data: {
                    dataType: "json"
                }
            },

            preparePostData: function(data) {
                data.phrase = $(".support-examination-column").val();
                return data;
            },
            requestDelay: 400
        };
        $(".support-examination-column").easyAutocomplete(options6);
    }
    
    function getData(){
        var $detail_reservation = $("#detail-reservation").val();
        var $patient = $("#patient-id").val();

        var $medical_record_data = new Array();
        var $main_condition = $("#main-condition-text").val();
        var $working_diagnose = $("#working-diagnose-text").val();
        var $condition_date = $("#condition-date-text").val();
        var $rujukan = $("#rujukan-text").val();

        var $visit_type = $('input[name=visit-type-input]:checked', '#visit-form').val();
        var $treatment = $('input[name=treatment-input]:checked', '#treatment-form').val();
        var $status_diagnose = $('#status-diagnose-input').val();

        //Physical Examination
        var $conscious = $("#conscious-input").val();
        var $blood_low = $("#blood-preasure-low-input").val();
        var $blood_high = $("#blood-preasure-high-input").val();
        var $pulse = $("#pulse-input").val();
        var $temperature = $("#temperature-input").val();
        var $respiration = $("#respiration-input").val();
        var $height = $("#height-input").val();
        var $weight = $("#weight-input").val();

        var $additionalConditionList=[];
        var $supportDiagnoseList=[];
        var $supportExaminationList=[];
        var $medicationList=[];


        //KELUHAN TAMBAHAN
        $('#additional-condition-ul li').each(function(){
            var $data= $(this).find("textarea.add-codition-li-text").val();
            var detailData = {
                value : $data
            };
            $additionalConditionList.push(detailData);
        });

        //DIAGNOSA PENUNJANG
        $('#support-diagnose-ul li').each(function(){
            var $data= $(this).find("textarea.support-diagnose-li-text").val();
            var detailData = {
                value : $data
            };
            $supportDiagnoseList.push(detailData);
        });

        //SUPPORT EXAMINATION
        $('#support-examination-ul li').each(function(){
            var $data1= $(this).find("textarea.support-examination-column").val();
            var $data2= $(this).find("textarea.support-examination-value").val();
            var detailData = {
                column : $data1,
                value : $data2
            };
            $supportExaminationList.push(detailData);
        });

        //TERAPI
        $('#medication-ul li').each(function(){
            var $data= $(this).find("textarea.medication-li-text").val();
            var detailData = {
                value : $data
            };
            $medicationList.push(detailData);
        });

        var md_data = new Object();
        md_data.detail_reservation = $detail_reservation;
        md_data.patient = $patient;
        md_data.main_condition = $main_condition;
        md_data.additional_condition = $additionalConditionList;
        md_data.condition_date = $condition_date;

        md_data.visit_type = $visit_type;
        md_data.treatment = $treatment;
        md_data.status_diagnose = $status_diagnose;

        md_data.conscious = $conscious;
        md_data.blood_low = $blood_low;
        md_data.blood_high = $blood_high;
        md_data.pulse = $pulse;
        md_data.respiration = $respiration;
        md_data.temperature = $temperature;
        md_data.height = $height;
        md_data.weight = $weight;

        md_data.support_examination = $supportExaminationList;
        md_data.working_diagnose = $working_diagnose;
        md_data.support_diagnose = $supportDiagnoseList;
        md_data.medication = $medicationList;

        md_data.rujukan = $rujukan;

        $medical_record_data.push(md_data);

        return $medical_record_data;
        //alert(JSON.stringify($medical_record_data));

    }

    function validateInput(){
        var err = 0;

        // MAIN CONDITION / KELUHAN UTAMA
        if (!$("#main-condition-text").validateRequired({errMsg:"Harap diisi"})) {
            err++;
        }

        // CONDITION DATE / MULAI SEJAK
        if(!$("#condition-date-text").validateRequired({errMsg:"Harap diisi"})){
            err++;
        }

        // WORKING DIAGNOSE / DIAGNOSA KERJA
        if(!$("#working-diagnose-text").validateRequired({errMsg:"Harap diisi"})){
            err++;
        }

        // PHYSICAL EXAMINATION
        if(!$("#blood-preasure-low-input").validateRequired({errMsg:"Harap diisi"}) ||
            !$("#blood-preasure-high-input").validateRequired({errMsg:"Harap diisi"}) ){
            err++;
        }
        if(!$("#pulse-input").validateRequired({errMsg:"Harap diisi"})){
            err++;
        }
        if(!$("#temperature-input").validateRequired({errMsg:"Harap diisi"})){
            err++;
        }
        if(!$("#respiration-input").validateRequired({errMsg:"Harap diisi"})){
            err++;
        }
        if(!$("#height-input").validateRequired({errMsg:"Harap diisi"})){
            err++;
        }
        if(!$("#weight-input").validateRequired({errMsg:"Harap diisi"})){
            err++;
        }

        if ($('#additional-condition-ul>li').length == 0){
           $("#add-condition-err-msg").html("");
        }
        if ($('#support-diagnose-ul>li').length == 0){
            $("#support-diagnose-err-msg").html("");
        }
        if ($('#support-examination-ul>li').length == 0){
            $("#support-examination-err-msg").html("");
        }
        if ($('#medication-ul>li').length == 0){
            $("#medication-err-msg").html("");
        }

        //KELUHAN TAMBAHAN
        $('#additional-condition-ul>li').each(function(){
            var $element= $(this).find("textarea.add-codition-li-text");
            if(!$element.validateRequired({errMsg:"Harap diisi"})){
                err++;
            }
        });

        //DIAGNOSA PENUNJANG
        $('#support-diagnose-ul>li').each(function(){
            var $element= $(this).find("textarea.support-diagnose-li-text");
            if(!$element.validateRequired({errMsg:"Harap diisi"})){
                err++;
            }
        });

        //PEMERIKSAAN PENUNJANG
        $('#support-examination-ul>li').each(function(){
            var $element1= $(this).find("textarea.support-examination-column");
            var $element2= $(this).find("textarea.support-examination-value");

            if(!$element1.validateRequired({errMsg:"Harap diisi"})){
                err++;
            }
            if(!$element2.validateRequired({errMsg:"Harap diisi"})){
                err++;
            }
        });

        //TERAPI
        $('#medication-ul>li').each(function(){
            var $element= $(this).find("textarea.medication-li-text");
            if(!$element.validateRequired({errMsg:"Harap diisi"})){
                err++;
            }
        });

        if (err != 0) {
            swal(
                "Terdapat data yang masih kosong, Silahkan periksa kembali !",
                '',
                'error'
            )
            return false;
        } else {
            return true;
        }
    }

    $("#btn-save-medical-record").click(function(e){
        if(validateInput()){
            var $medical_record_data = getData();
            var data_post = {
                data :$medical_record_data
            }

            swal({
                title: 'Apakah Anda yakin untuk menyimpan data ini?',
                text: "Data yang di simpan tidak bisa diganti lagi",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan Data!'
            }).then(function() {
                $.ajax({
                    url: $base_url+"/MedicalRecord/saveMedicalRecordData",
                    data: data_post,
                    type: "POST",
                    dataType: 'json',
                    beforeSend:function(){
                        $("#load_screen").show();
                    },
                    success:function(data){
                        if(data.status != 'error'){
                            swal({
                                title: data.msg,
                                text: 'This Page will be redirect after a few second !',
                                type: 'success',
                                allowOutsideClick: false,
                                showConfirmButton:false
                            })
                            window.setTimeout(function () {
                                location.href =  $base_url+"/ReservationDoctor";
                            }, 2000);

                        }else{
                            swal(
                                data.msg,
                                '',
                                'error'
                            )
                        }
                        alert($data);
                    },
                    error: function(xhr, status, error) {
                        //var err = eval("(" + xhr.responseText + ")");
                        $("#load_screen").hide();
                        swal(
                            "Server Error ! Please Try Again",
                            '',
                            'error'
                        )
                    }
                });
            })
            //alert(JSON.stringify(data_post));
        }
    });

    $("#btn-cancel-medical-record").click(function(e){
        var $detail_reservation = $("#detail-reservation").val();
        swal({
            title: 'Apakah Anda yakin untuk keluar dari rekam medis ini ?',
            text: "Data yang di simpan tidak bisa diganti lagi !",
            type: 'error',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Keluar'
        }).then(function(){
            $.ajax({
                url: $base_url+"/MedicalRecord/rejectReservationMedicalRecord",
                data: {detailReservation : $detail_reservation},
                type: "POST",
                dataType: 'json',
                beforeSend:function(){
                    $("#load_screen").show();
                },
                success:function(data){
                    if(data.status != 'error'){
                        swal({
                            title: data.msg,
                            text: 'This Page will be redirect after a few second !',
                            type: 'success',
                            allowOutsideClick: false,
                            showConfirmButton:false
                        })
                        window.setTimeout(function () {
                            location.href =  $base_url+"/ReservationDoctor";
                        }, 2000);

                    }else{
                        swal(
                            data.msg,
                            '',
                            'error'
                        )
                    }
                    alert($data);
                },
                error: function(xhr, status, error) {
                    //var err = eval("(" + xhr.responseText + ")");
                    $("#load_screen").hide();
                    swal(
                        "Server Error ! Please Try Again",
                        '',
                        'error'
                    )
                }
            });
        });
    });

});/**
 * Created by Vicky on 9/17/2016.
 */
