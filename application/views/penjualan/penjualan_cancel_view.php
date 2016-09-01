<?php $this->load->helper('HTML');
echo link_tag('css/form-transaction.css');
echo link_tag('css/chosen.css');
echo link_tag('css/datepicker.css');
echo link_tag('css/select2.css');
?>
<script src="<?php echo base_url(); ?>js/jquery.maskMoney.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/validate/insert-barang-validate.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/select2.full.min.js"></script>
<script src="<?php echo base_url(); ?>js/bootstrap-editable.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/penjualan.js" type="text/javascript"></script>

<script type="text/javascript">
    $(function(){
        var $selected_row;
        $(window).load(function() {
            var option = <?=$data_penjualan_header->Status?>;
            $(".hutang").hide();
            if(option!=1){
                $(".hutang").show();
                $("#harga_htg").val('<?=$data_penjualan_header->Harga_Hutang?>');
                $("#tgl_jth_tmp").val('<?=$data_penjualan_header->Tgl_Jatuh_Tempo?>');
            }
            $('#stat option[value='+option+']').attr('selected','selected');
        });

        //set jquery datepicker
        $('#tgl_penjualan').datepicker({
            dateFormat: "yy-mm-dd"
        });
        $('#tgl_jth_tmp').datepicker({
            dateFormat: "yy-mm-dd"
        });

        $('#tgl_penjualan').datepicker()
            .on('changeDate', function(ev){
                $('#tgl_penjualan').datepicker('hide');
            });

        $('#tgl_jth_tmp').datepicker()
            .on('changeDate', function(ev){
                $('#tgl_jth_tmp').datepicker('hide');
            });

        //show hide status hutang
        $( ".status" ).change(function() {
            var value = $( "#stat" ).val();
            if(value=='1'){
                $(".hutang").hide();
            }else{
                $(".hutang").show();
            }
        });

        $.fn.editable.defaults.mode = 'inline';
        $('#discount').editable({
            type: 'number',
            title : 'Enter New Value',
            display: function(value) {
                // set Total-discount
                var old_discount =  parseInt($(this).attr("data-value"));
                var discount  = parseInt(value);
                var result = 0-(discount-old_discount);
                //alert(result);
                countResult(result);

                // set Discount value
                $(this).attr("data-value",value);
                var k = discount.format(0, 3, '.', ',');
                $(this).text(k);
            }
        });
    });
</script>
<style>
    .modal
    {
        overflow: hidden;
    }
    .modal-dialog{
        margin:auto;
        left: 0;
    }
    #container-result{
        margin-bottom: 40px;
    }
    #total-result{
        color: #5978ff;
        font-weight: 800;
    }
    #dicount{
        color:#FE1A00;
    }
    #total-result, #dicount{
        font-size: 22px;
    }
    .frmSearch {
        border: 1px solid #428BCC;
        background-color:#C8EEFD;
        margin: 2px 0px;}
    .suggesstion-box{
        position:relative;
    }
    .barang-list{
        float:left;
        list-style:none;
        margin:0;padding:0;
        width:100%;
        position: absolute;
        border: 1px solid black;
    }
    .barang-list li{
        padding: 8px;
        background:#FAFAFA;
        border-bottom:#F0F0F0 1px solid;
    }
    .barang-list li:hover{
        background:#C8EEFD;
        cursor: pointer
    }
    .search-box{
        padding: 5px;
        border: #428BCC 1px solid;
        width:100%;
    }
    .label{
        font-size: 12px;
    }
    .tr-cancel td{
        color:#d2322d;
        font-weight: bold;
    }
</style>

<div class="content-container" >

    <!--Error Container-->
    <div class="error-container hidden" id="error-msg">
        <div class="btn-closed">
            <button type="button" class="btn btn-default btn-lg">
                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
            </button>
        </div>

        <div class="error-box">
            <!--Error Messages here-->
        </div>
    </div>

    <div class="form-add-new">
        <!-- general form elements -->
        <div class="box box-primary">

            <!-- form start -->

            <h3 class="">
                Penjualan Edit
            </h3>
            <div class="well well-sm">
                <button type="button" class="btn btn-primary" id="hd-btn-save">
                    <span class="glyphicon glyphicon-floppy-save"></span>&nbsp Save
                </button>
                <a href="<?=site_url('Penjualan/index')?>">
                    <button type="button" class="btn btn-default">
                        <span class="glyphicon glyphicon-circle-arrow-left"></span>&nbsp Back to List
                    </button>
                </a>
            </div>

            <?php echo form_open('',"class='form-horizontal'"); ?>

            <div class="side-one"><!-- side 1 -->
                <div class="form-group" id="lbl-kode">
                    <label class="col-sm-3 control-label heading-label">Kode BON</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="kode_bon" name="kode_bon"
                               placeholder="Kode BON" maxlength="15" value="<?=$data_penjualan_header->Kode_Bon?>">
                    </div><span id='err_kode' class="err_span"></span><span id='pesan_kode'></span>
                </div>

                <div class="form-group" id="lbl-customer">
                    <label class="col-sm-3 control-label heading-label">Nama Pembeli</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="nama_customer" name="nama_customer"
                               placeholder="Nama Pembeli" maxlength="150" value="<?=$data_penjualan_header->Nama_Pembeli?>">
                    </div><span id='err_nama' class="err_span"></span>
                </div>

                <div class="form-group" id="lbl-tgl-beli">
                    <label class="col-sm-3 control-label heading-label">Tanggal Penjualan</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="tgl_penjualan" name="tgl_penjualan"
                               placeholder="MM/dd/YYYY" maxlength="150" value="<?=$data_penjualan_header->Tgl_Penjualan?>">
                    </div><span id='err_nama' class="err_span"></span>
                </div>

            </div><!-- side 1 -->

            <div class="side-two"><!-- side 2 -->
                <div class="form-group">
                    <label class="col-sm-3 control-label heading-label">Status</label>
                    <div class="col-sm-9">
                        <select class="form-control status" id="stat" name="stat">
                            <option value="1">CASH</option>
                            <option value="2">LUNAS</option>
                            <option value="3">HUTANG</option>
                        </select>
                    </div>
                </div>

                <div class="form-group hutang">
                    <label class="col-sm-3 control-label heading-label">Harga Hutang</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="harga_htg" name="harga_htg"
                               placeholder="xxx" maxlength="150">
                    </div>
                </div>

                <div class="form-group hutang" id="lbl-tgl-beli">
                    <label class="col-sm-3 control-label heading-label">Tanggal Jatuh Tempo</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="tgl_jth_tmp" name="tgl_jth_tmp"
                               placeholder="MM/dd/YYYY" maxlength="150">
                    </div><span id='err_nama' class="err_span"></span>
                </div>

            </div>
            <?php echo form_close(); ?>

            <div class="clear"></div>

            <table class="table table-bordered table-striped" id="tbl-detail">
                <thead>
                <tr>
                    <th style = "text-align:left;">Kode Barang</th>
                    <th style = "text-align:left;">Nama Barang</th>
                    <th style = "text-align:right;">Harga Jual Current </th>
                    <th style = "text-align:right;">Qty</th>
                    <th style = "text-align:right;">Harga Jual</th>
                    <th style = "text-align:right;">Harga Total</th>
                    <th style = "text-align:center;">Option</th>
                </tr>
                </thead>

                <tbody id="detail-content">
                <?php foreach($data_penjualan_detail as $row){?>
                    <tr data-id="<?=$row['Penjualan_Detail_ID']?>"
                        <?php if($row['status']==2){?>class="tr-cancel"<?php } ?>
                        >
                        <td class="kode-barang"><?=$row['Kode_Barang']?></td>
                        <td class="nama-barang"><?=$row['Barang_Name']?></td>
                        <td class="td-right"><?=$row['Harga_Jual_Normal']?></td>
                        <td class="td-right"><?=$row['Qty']?></td>
                        <td class="td-right"><?=$row['Harga_Jual']?></td>
                        <td class="td-right"><?=$row['Harga_Total']?></td>
                        <td class="td-center">
                            <button type="button" class="btn btn-danger btn-sm btn-cancel">
                                <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-default btn-sm btn-remove-cancel">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                            </button>
                        </td>
                        <input type="hidden" class="alasan-cancel-hidden"
                               <?php if($row['status']==2){?>value="<?=$row['alasan']?>"<?php } ?>
                            >

                    </tr>
                <?php } ?>
                </tbody>

            </table>

            <div id="container-result">
                <div class="row">
                    <div class="col-lg-5 float-right td-right">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default btn-lg" aria-label="Bold">
                                    <span class="glyphicon glyphicon-gift"></span> Discount
                                </button>
                            </div>
                            <span class="form-control"><a href="#" id="discount" data-value="<?=$data_penjualan_header->Discount?>"><?=$data_penjualan_header->Discount?></a></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5 float-right td-right">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default btn-lg" aria-label="Bold">
                                    <span class="glyphicon glyphicon-shopping-cart"></span> Total
                                    &nbsp&nbsp&nbsp&nbsp&nbsp
                                </button>
                            </div>
                            <span class="form-control" id="total-result" data-value="<?=$data_penjualan_header->Harga_Total?>"><?=$data_penjualan_header->Harga_Total?></span>
                        </div>
                    </div>
                </div>
            </div><!-- div Container-result -->
        </div>
    </div><!-- div form-add-new -->


</div><!-- div container -->

<!--Modal-->
<div class="modal fade" id="alasan-modal" tabindex="-1" role="dialog" aria-labelledby="alasan-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="alasan-modal-label">Alasan Pembatalan</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Barang:</label>
                        <input type="text" class="form-control" id="barang-name-modal" disabled="disabled">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Qty:</label>
                        <span class="label label-danger err-alasan"></span>
                        <div class="row">
                            <div class="col-xs-4">
                                <input type="number" class="form-control" id="qty-modal" max="2">
                            </div>
                            <div class="col-xs-4">
                                <input type="text" class="form-control" id="qty-awal-modal" disabled="disabled">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Alasan:</label>
                        <span class="label label-danger err-alasan"></span>
                        <textarea class="form-control" id="alasan-text"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-confirm-alasan" data-id="">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        var data_cancel_penjualan = [];

        //Cancel
        $(".btn-cancel" ).click(function(){
            var $row =  $(this).closest("tr");
            $selected_row = $row;
            var kodeBarang = $row.find(".kode-barang").text();
            var namaBarang = $row.find(".nama-barang").text();
            var alasanCancel = $row.find(".alasan-cancel-hidden").val();
            var name = kodeBarang+" - "+namaBarang;
            var id = $row.data('id');

            // Set Modal Value
            $("#btn-confirm-alasan").data('id',id);
            $('.err-alasan').text('');
            $('#alasan-text').val(alasanCancel);
            $('#barang-name-modal').val(name);
            $('#alasan-modal').modal('show');
        });

        //Confirm Alasan
        $("#btn-confirm-alasan").click(function(){
            var alasan = $('#alasan-text').val();
            var id = $(this).data('id');
            //alert(id);
            if(alasan == "" || alasan==null){
                $('.err-alasan').text('This field cannot be empty!');
            }else{
                $('.err-alasan').text('');
                $selected_row.addClass('tr-cancel');
                $selected_row.find(".alasan-cancel-hidden").val(alasan);
                $('#alasan-modal').modal('hide');
            }
        });

        //Remove Cancel
        $(".btn-remove-cancel").click(function(){
            var $row =  $(this).closest("tr");
            $row.removeClass('tr-cancel')
            $row.find(".alasan-cancel-hidden").val('');
            $('#alasan-text').val('');
        });


        $('#hd-btn-save').click(function(){
            data_cancel_penjualan = [];
            $( "tr.tr-cancel" ).each(function( index, element ) {
                var id_detail = $(this).data("id");
                var excuse = $(this).children("input.alasan-cancel-hidden").val();
                var detailData = {
                    id : id_detail,
                    alasan : excuse
                };
                data_cancel_penjualan.push(detailData);
            });
            if(data_cancel_penjualan.length!=0){
                //alert(JSON.stringify(data_cancel_penjualan));
                var data_post = {
                    data :data_penjualan
                }
                //alert(JSON.stringify(data_penjualan));
                // ajax mulai disini
                $.ajax({
                    url: base_url+"index.php/penjualan/cancelPenjualanDetailItem",
                    data: data_post,
                    type: "POST",
                    dataType: 'json',
                    success: function(msg){
                        if(msg==0){
                            alert(msg);
                        }else{
                            alert(msg);
                        }
                    },
                    error:function(msg){
                        alert("error");
                    }
                });
            }else{
                alertify.alert('Tidak ada penjualan yang dibatalkan!');
            }

        });
    });
</script>