<style>
    .loading-modal{
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
    }

</style>
<!--Modal ADD-->
<div class="modal fade" id="diagnose-result-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Hasil Diagnosa</b></h4>
            </div><!--modal header-->

            <div class="modal-body">
                <table class="table table-hover table-bordered table-striped" id="dataTables-diagnose-result">
                    <thead>
                    <tr>
                        <th style = "text-align:left;">Penyakit</th>
                    </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>
                                <div class="progress-group">
                                    <span class="progress-text">Add Products to Cart</span>
                                    <span class="progress-number"><b>160%</b></span>

                                    <div class="progress sm">
                                        <div class="progress-bar progress-bar-green" style="width: 80%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="box loading-modal">
                    <h3 class="text-center">Diagnosa ...</h3>
                    <br/>
                    <div class="overlay loading-screen-queue">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div><!--modal body-->
            <div class="modal-footer">
            </div><!--modal footer-->
        </div><!--modal content-->
    </div><!--modal dialog-->
</div>