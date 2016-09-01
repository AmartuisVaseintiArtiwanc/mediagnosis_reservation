
    var count_index = 0;
    var base_url = $("#base_url").val();
    var detailItemDoctor = [];
    var doctorLookupData = [];
	var $doctor_data_temp = [];

    $(document).ready(function() {
        // Select doctor to Detail List
        // Add button on list view on Lookup
        $('#dataTables-doctor tbody').on('click', 'button.add-doctor-btn', function () {
            var $tr =  $(this).closest("tr");
            var index = $tr.index();
            var id = $tr.attr("data-id");
            var text = $tr.find('td').eq(1).text();
			if(checkDuplicateDoctor(index)){
				addDoctor(index);
				$('#lookup-doctor-modal').modal("hide");
			}                       
        });
        // Double Click on list view on Lookup
        $('#dataTables-doctor tbody').on('dblclick', 'tr', function () {
            var index = $(this).index();
            var id = $(this).attr("data-id");
            var text = $(this).find('td').eq(1).text();
            if(checkDuplicateDoctor(index)){
				addDoctor(index);
				$('#lookup-doctor-modal').modal("hide");
			}            
        });

        function addDoctor(index){
            createItemDetail(index);    
        }
		
		function checkDuplicateDoctor(index){
			var flag = $data_doctor_current.filter(function ($data_doctor_current) { 
				return $data_doctor_current.doctorID == doctorLookupData[index][1]
			});
			
			var flag2 = $doctor_data_temp.filter(function ($doctor_data_temp) { 
				return $doctor_data_temp.doctorID == doctorLookupData[index][1]
			});
			//alert(JSON.stringify($data_doctor_current));
			if(flag == "" && flag2==""){
				return true;
			}else{
				alert("Doctor already exist !");
				return false;
			}
		}

        function createItemDetail(index){
            if (typeof doctorLookupData[index] !== 'undefined' && doctorLookupData[index] !== null) {
                var tr = $("<tr>", {id: "item-" + count_index, class: "item-detail", "data-value": doctorLookupData[index][1]});
                // Doctor Name
                var td1 = $("<td>", {class: "doctor-item", "data-value": "0"}).text(doctorLookupData[index][2]);

                //Option
                var td7 = $("<td>", {class: "option td-center"});
                var button_del = $("<button>", {
                    id: "del",
                    class: "btn btn-danger",
                    type: "button",
                    "data-index-id": count_index
                });
                var span_del = $("<span>", {class: "glyphicon glyphicon-trash"});
                span_del.appendTo(button_del);
                button_del.appendTo(td7);

                //Delete Action
                //DELETE BUTTON CLICK
                button_del.click(function (event) {
                    var tr_element = $(this).closest("tr");
					for (var i = 0; i < $doctor_data_temp.length; i++) {
						var cur = $doctor_data_temp[i];
						if (cur.doctorID == tr_element.attr("data-value")) {
							$doctor_data_temp.splice(i, 1);
							break;
						}
					}
					
                    tr_element.remove();
                });

                //Set data to table
                td1.appendTo(tr);
                td7.appendTo(tr);
                $('#detail-content').append(tr);
                count_index++;
				
				//Add Data temp
				var data_new = {
					doctorID : doctorLookupData[index][1]
				};	
				$doctor_data_temp.push(data_new);				
            }
        }

    });

    function validateDoctorInput(){
        var err=0;       
        // Validate detail item
        if(!validateDetailItem()){
            err++;
        }

        if (err != 0) {
            return false;
        } else {
            return true;
        }
    }

    function validateDetailItem(){
        var err = 0; //err data detail
        var check_detail = 1; // err if detail is empty
        detailItemDoctor=[];
        $( "tr.item-detail" ).each(function( index, element ) {
            // element == this            
			$(this).css("border","none");
			var doctor = $(this).attr("data-value");

			var detailData = {
				doctorID : doctor
			};

			detailItemDoctor.push(detailData);
			check_detail = 0;
			//err+=0;          
        });
        if(err != 0){
            detailItemDoctor=[];
            alertify.alert("Detail Doctor must be filled");
            return false;
        }else{
            //alert(err+" sukses "+check_detail+JSON.stringify(detailItemDoctor));
            return true;
        }

    }

