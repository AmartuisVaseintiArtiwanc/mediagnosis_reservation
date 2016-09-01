
    var count_index = 0;
    var base_url = $("#base_url").val();
    var detailItemPenjualan = [];
    var barangLookupData = [];

    $(document).ready(function() {
        // Select Barang to Penjualan List
        // Add button on list view on Lookup
        $('#dataTables-barang tbody').on('click', 'button.add-barang-btn', function () {
            var $tr =  $(this).closest("tr");
            var index = $tr.index();
            var id = $tr.attr("data-id");
            var text = $tr.find('td').eq(1).text();
            addBarangPenjualan(index);
            $('#lookup-barang-modal').modal("hide");
        });
        // Double Click on list view on Lookup
        $('#dataTables-barang tbody').on('dblclick', 'tr', function () {
            var index = $(this).index();
            var id = $(this).attr("data-id");
            var text = $(this).find('td').eq(1).text();
            addBarangPenjualan(index);
            $('#lookup-barang-modal').modal("hide");
        });

        function addBarangPenjualan(index){
            createItemDetail(index);
            countHargaTotalHandler();
        }

        function createItemDetail(index){
            if (typeof barangLookupData[index] !== 'undefined' && barangLookupData[index] !== null) {
                var tr = $("<tr>", {id: "item-" + count_index, class: "item-detail", "data-value": barangLookupData[index][1]});
                // Kode Barang
                var td1 = $("<td>", {class: "kode-item", "data-value": "0"}).text(barangLookupData[index][2]);

                // Nama barang,
                var td2 = $("<td>", {class: "name-item", "data-value": "0"}).text(barangLookupData[index][3]);

                var td3 = $("<td>", {
                    class: "info-item",
                    "data-value": "0"
                }).text(barangLookupData[index][6] + " - " + barangLookupData[index][7]+" "+barangLookupData[index][10]);

                // Harga jual
                var harga_jual = barangLookupData[index][16];
                var td4 = $("<td>", {class: "harga-item td-right", "data-value": harga_jual}).text(barangLookupData[index][9]);

                // Qty Barang
                var td5 = $("<td>", {class: "qty-item td-right"});
                var a5 = $("<a>", {
                    class: "qty-item-input",
                    "data-value": "0",
                    "data-type": "text",
                    "data-satuan":barangLookupData[index][13],
                    "data-pk": count_index
                });
                a5.appendTo(td5);

                //Harga total
                var td6 = $("<td>", {class: "harga-total-item td-right", "data-value": "0"});

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
                    var element = $(this).closest("tr");
                    var total = parseInt(element.find(".harga-total-item").attr("data-value"));
                    var result = 0 - total;
                    countResult(result);
                    element.remove();
                });

                //Set data to table
                td1.appendTo(tr);
                td2.appendTo(tr);
                td3.appendTo(tr);
                td4.appendTo(tr);
                td5.appendTo(tr);
                td6.appendTo(tr);
                td7.appendTo(tr);
                $('#detail-content').append(tr);
                count_index++;
            }

            // Set Editable Component
            $('.qty-item-input').editable({
                //step: 'any', // <-- added this line
                title : '0',
                display: function(value) {
                    var satuan = $(this).attr("data-satuan");
                    $(this).attr("data-value",value);
                    $(this).text(value+" "+satuan);
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

    function countHargaTotalHandler(){
        // Change event to Count each item Total = Price * Qty
        $(".qty-item-input").bind("DOMSubtreeModified", function() {
            var $row =  $(this).closest("tr");
            var qty = $row.find(".qty-item-input").attr("data-value");
            var price = $row.find(".harga-item").attr("data-value");

            var total = parseFloat(qty)*parseFloat(price);
            var old_total = parseInt($row.find(".harga-total-item").attr("data-value"));
            var result = total-old_total;
            countResult(result);

            $row.find(".harga-total-item").attr("data-value",total);
            $row.find(".harga-total-item").text(total.format(0, 3, '.', ','));
        });
    }

    // Function Count FINAL TOTAL
    function countResult(value){
        var old_result = parseInt($("#total-result").attr("data-value"));
        var result = old_result+value;
        $("#total-result").attr("data-value",result);
        $("#total-result").text(result.format(0, 3, '.', ','));
    }

    function validatePenjualanInput(){
        var err=0;
        var kode_bon = $("#kode_bon").val();
        var nama_customer = $("#nama_customer").val();
        var tgl_penjualan = $("#tgl_penjualan").val();

        var status = $('#stat option:selected').val();
        var tgl_jth_tempo = $("#tgl_jth_tmp").val();
        var harga_hutang = $("#harga_htg").val();

        var error_list_msg = new Array();

        if(!$('#kode_bon').validateRequired()){
            err++;
        }
        if(!$('#tgl_penjualan').validateRequired()){
            err++;
        }

        if(status == 3){
            if(!$('#harga_htg').validateRequired()){
                err++;
            }
            if(!$('#tgl_jth_tmp').validateRequired()){
                err++;
            }
        }

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

    function validatePenjualanEdit(){
        var err=0;
        var kode_bon = $("#kode_bon").val();
        var nama_customer = $("#nama_customer").val();
        var tgl_penjualan = $("#tgl_penjualan").val();

        var status = $('#stat option:selected').val();
        var tgl_jth_tempo = $("#tgl_jth_tmp").val();
        var harga_hutang = $("#harga_htg").val();

        var error_list_msg = new Array();

        if(!$('#kode_bon').validateRequired()){
            err++;
        }
        if(!$('#tgl_penjualan').validateRequired()){
            err++;
        }

        if(status == 3){
            if(!$('#harga_htg').validateRequired()){
                err++;
            }
            if(!$('#tgl_jth_tmp').validateRequired()){
                err++;
            }
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
        detailItemPenjualan=[];
        $( "tr.item-detail" ).each(function( index, element ) {
            // element == this
            var harga_total = $(this).children("td.harga-total-item").attr("data-value");

            if ( harga_total==0 || harga_total==null ) {
                $(this).css("border","3px solid #C0392B");
                err++;
            }else{
                $(this).css("border","none");
                var barang_id = $(this).attr("data-value");
                var harga_curr = $(this).children("td.harga-item").attr("data-value");
                var qty = $(this).children("td.qty-item").children().attr("data-value");

                var detailData = {
                    id : barang_id,
                    qty : qty,
                    capital_price : harga_curr,
                    price : harga_curr
                };

                detailItemPenjualan.push(detailData);
                check_detail = 0;
                err+=0;
            }
        });
        if(err != 0 || check_detail != 0){
            detailItemPenjualan=[];
            alertify.alert("Detail Penjualan must be filled");
            return false;
        }else{
            //alert(err+" sukses "+check_detail+JSON.stringify(detailItemPenjualan));
            return true;
        }

    }

