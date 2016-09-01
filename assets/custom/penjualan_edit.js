function validatePenjualanEdit(){
    var kode_bon = $("#kode_bon").val();
    var nama_customer = $("#nama_customer").val();
    var tgl_penjualan = $("#tgl_penjualan").val();

    var status = $('#stat option:selected').val();
    var tgl_jth_tempo = $("#tgl_jth_tmp").val();

    var error_list_msg = new Array();

    if(kode_bon == null || kode_bon==""){
        error_list_msg.push("Kode bon harus di isi");
    }
    if(nama_customer == null || nama_customer==""){
        error_list_msg.push("Nama Customer harus di isi");
    }
    if(tgl_penjualan == null || tgl_penjualan==""){
        error_list_msg.push("Tanggal Penjualan harus di isi");
    }
    if(status == 2){
        if(tgl_jth_tempo == null || tgl_jth_tempo==""){
            error_list_msg.push("Tanggal Jatuh Tempo harus di isi");
        }
    }

    if(error_list_msg.length != 0 ){
        //$('#error-msg').removeClass("hidden");
        for(var i in error_list_msg ){
            //var div_msg = $("<div>", {class: "alert alert-danger"}).text(error_list_msg[i]);
            msg+=error_list_msg[i]+"<br/><br/>";
            //div_con.append(div_msg);
        }
        alertify.alert(msg);
        return false;
    }else{
        return true;
    }

}
