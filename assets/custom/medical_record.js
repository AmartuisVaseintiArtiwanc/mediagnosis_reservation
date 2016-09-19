
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


});/**
 * Created by Vicky on 9/17/2016.
 */
