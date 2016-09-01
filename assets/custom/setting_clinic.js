
    var count_index = 0;
    var base_url = $("#base_url").val();
    var detailItemPoli = [];
    var poliLookupData = [];
	var $poli_data_temp = [];

    $(document).ready(function() {
        // Select poli to Detail List
        // Add button on list view on Lookup
        $('#dataTables-poli tbody').on('click', 'button.add-poli-btn', function () {
            var $tr =  $(this).closest("tr");
            var index = $tr.index();
            var id = $tr.attr("data-id");
            var text = $tr.find('td').eq(1).text();
			if(checkDuplicatePoli(index)){
				addPoli(index);
				$('#lookup-poli-modal').modal("hide");
			}                       
        });
        // Double Click on list view on Lookup
        $('#dataTables-poli tbody').on('dblclick', 'tr', function () {
            var index = $(this).index();
            var id = $(this).attr("data-id");
            var text = $(this).find('td').eq(1).text();
            if(checkDuplicatePoli(index)){
				addPoli(index);
				$('#lookup-poli-modal').modal("hide");
			}            
        });

        function addPoli(index){
            createItemDetail(index);    
        }
		
		function checkDuplicatePoli(index){
			var flag = $data_poli_current.filter(function ($data_poli_current) { 
				return $data_poli_current.poliID == poliLookupData[index][1]
			});
			
			var flag2 = $poli_data_temp.filter(function ($poli_data_temp) { 
				return $poli_data_temp.poliID == poliLookupData[index][1]
			});
			//alert(JSON.stringify($data_poli_current));
			if(flag == "" && flag2==""){
				return true;
			}else{
				alert("Poli already exist !");
				return false;
			}
		}

        function createItemDetail(index){
            if (typeof poliLookupData[index] !== 'undefined' && poliLookupData[index] !== null) {
                var tr = $("<tr>", {id: "item-" + count_index, class: "item-detail", "data-value": poliLookupData[index][1]});
                // Poli Name
                var td1 = $("<td>", {class: "poli-item", "data-value": "0"}).text(poliLookupData[index][2]);

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
					for (var i = 0; i < $poli_data_temp.length; i++) {
						var cur = $poli_data_temp[i];
						if (cur.poliID == tr_element.attr("data-value")) {
							$poli_data_temp.splice(i, 1);
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
					poliID : poliLookupData[index][1]
				};	
				$poli_data_temp.push(data_new);				
            }
        }

    });

    function validatePoliInput(){
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
        detailItemPoli=[];
        $( "tr.item-detail" ).each(function( index, element ) {
            // element == this            
			$(this).css("border","none");
			var poli = $(this).attr("data-value");

			var detailData = {
				poliID : poli
			};

			detailItemPoli.push(detailData);
			check_detail = 0;
			//err+=0;          
        });
        if(err != 0){
            detailItemPoli=[];
            alertify.alert("Detail Poli must be filled");
            return false;
        }else{
            //alert(err+" sukses "+check_detail+JSON.stringify(detailItemPoli));
            return true;
        }

    }

