
    var count_index = 0;
    var base_url = $("#base_url").val();
    var detailItemSymptomp = [];
    var symptompLookupData = [];
	var $symptomp_data_temp = [];

    $(document).ready(function() {
        $.fn.editable.defaults.mode = 'inline';
        // Select symptomp to Detail List
        // Add button on list view on Lookup
        $('#dataTables-symptomp tbody').on('click', 'button.add-symptomp-btn', function () {
            var $tr =  $(this).closest("tr");
            var index = $tr.index();
            var id = $tr.attr("data-id");
            var text = $tr.find('td').eq(1).text();
			if(checkDuplicateSymptomp(index)){
				addSymptomp(index);
				$('#lookup-symptomp-modal').modal("hide");
			}                       
        });
        // Double Click on list view on Lookup
        $('#dataTables-symptomp tbody').on('dblclick', 'tr', function () {
            var index = $(this).index();
            var id = $(this).attr("data-id");
            var text = $(this).find('td').eq(1).text();
            if(checkDuplicateSymptomp(index)){
				addSymptomp(index);
				$('#lookup-symptomp-modal').modal("hide");
			}            
        });

        function addSymptomp(index){
            createItemDetail(index);    
        }
		
		function checkDuplicateSymptomp(index){
			var flag = $data_symptomp_current.filter(function ($data_symptomp_current) { 
				return $data_symptomp_current.symptompID == symptompLookupData[index][1]
			});
			
			var flag2 = $symptomp_data_temp.filter(function ($symptomp_data_temp) { 
				return $symptomp_data_temp.symptompID == symptompLookupData[index][1]
			});
			//alert(JSON.stringify($data_symptomp_current));
			if(flag == "" && flag2==""){
				return true;
			}else{
				alert("Gejala ini sudah terdapat dalam setting !");
				return false;
			}
		}

        function createItemDetail(index){
            if (typeof symptompLookupData[index] !== 'undefined' && symptompLookupData[index] !== null) {
                var tr = $("<tr>", {id: "item-" + count_index, class: "item-detail tr-new","data-weight":"1","data-value": symptompLookupData[index][1]});
                // Symptomp Name
                var td1 = $("<td>", {class: "symptomp-item", "data-value": "0"}).text(symptompLookupData[index][2]);

                // Weight,
                var td2 = $("<td>", {class: "weight-item", "data-value": "0"});
				var a2 = $("<a>", {
                    class: "weight-item-input",
                    "data-value": "1",
                    "data-type": "text",
                    "data-pk": count_index
                });
                a2.text("1");
                a2.appendTo(td2);
               
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
					for (var i = 0; i < $symptomp_data_temp.length; i++) {
						var cur = $symptomp_data_temp[i];
						if (cur.symptompID == tr_element.attr("data-value")) {
							$symptomp_data_temp.splice(i, 1);
							break;
						}
					}
                    tr_element.remove();
                });

                //Set data to table
                td1.appendTo(tr);
                td2.appendTo(tr);             
                td7.appendTo(tr);
                $('#detail-content').append(tr);
                count_index++;
				
				//Add Data temp
				var data_new = {
					symptompID : symptompLookupData[index][1]
				};	
				$symptomp_data_temp.push(data_new);				
            }

            // Set Editable Weight
            $('.weight-item-input').editable({
                title : '1',
                display: function(value) {
                    // set Total-discount
                    var $tr = $(this).closest("tr");
                    $tr.attr("data-weight",value);
                    $(this).text(value);
                },
                validate: function(value) {
                    var regex = /^\d+(?:\.+\d*)?$/;
                    if(! regex.test(value)) {
                        return 'numbers only!';
                    }
                }
            });
        }

    });

    // change format currency
    Number.prototype.format = function(n, x, s, c) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
            num = this.toFixed(Math.max(0, ~~n));

        return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
    };

    function validateSymptompInput(){
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
        detailItemSymptomp=[];
        $( "tr.item-detail" ).each(function( index, element ) {
            // element == this            
			$(this).css("border","none");
			var symptomp = $(this).attr("data-value");
			var weight = $(this).attr("data-weight");

			var detailData = {
				symptompID : symptomp,
				weight : weight			
			};

			detailItemSymptomp.push(detailData);
			check_detail = 0;
			//err+=0;          
        });
        if(err != 0){
            detailItemSymptomp=[];
            alertify.alert("Gejala Penyakit tidak boleh kosong !");
            return false;
        }else{
            //alert(err+" sukses "+check_detail+JSON.stringify(detailItemSymptomp));
            return true;
        }

    }

