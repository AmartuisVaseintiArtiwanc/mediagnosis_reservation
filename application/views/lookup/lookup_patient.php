<!--Modal ADD-->
<div class="modal fade" id="lookup-patient-modal" tabindex="-1" data-isopen="1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content box">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Data Pasien</b></h4>
            </div><!--modal header-->

            <div class="modal-body">
                <table class="table table-hover table-bordered table-striped" id="table-lookup-patient">
                    <thead>
                    <tr>
                        <th style = "text-align:left;">Nama</th>
                        <th style = "text-align:left;">KTP</th>
                        <th style = "text-align:left;">BPJS</th>
						<th style = "text-align:left;">MRIS</th>
                        <th style = "text-align:left;">Jenis Kelamin</th>
                        <th style = "text-align:left;">Alamat</th>
                        <th style = "text-align:center;">Option</th>
                    </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
            </div><!--modal body-->

            <div class="overlay">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
            <div class="modal-footer">
            </div><!--modal footer-->

        </div><!--modal content-->
    </div><!--modal dialog-->
</div>

<script>

    $(document).ready( function() {
    });

</script>