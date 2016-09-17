<!DOCTYPE html>
<!-- By Designscrazed.com , just a structure for easy usage. -->

<html lang='en'>
<head>
    <meta charset="UTF-8" />
    <title>
        Sample Page by Designscrazed.com
    </title>
    <!--Main CSS-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/custom/doctor.css">
    <!--Grid W3 System-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/custom/grid.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/font-awesome/css/font-awesome.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600' rel='stylesheet' type='text/css'>

    <!--Select2-->
    <script src="<?php echo base_url();?>assets/plugins/jQuery/jQuery-2.2.0.min.js"></script>
    <script src="<?php echo base_url();?>assets/plugins/autosize/autosize.min.js"></script>

</head>
<style>
    #identity-table{
        width: 100%;
        position: relative;
    }
    #identity-table tr td{
        padding: 10px;
        margin-top: 10px;
        font-size: 16px;
    }
    #identity-table tr td:first-child{
        width: 35%;
        min-width: 250px;
    }
    #button-save{
        margin-top: 32px;
        padding-left: 128px;
        padding-right: 128px;
        padding-top: 24px;
        padding-bottom: 24px;
    }
    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
    .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-selected { background: #F0F0F0; }
    .autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
    .autocomplete-group { padding: 2px 5px; }
    .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
</style>
<body>
<div class="headline">

    <h6></h6>

    <h1>
        Sample Page by Designscrazed.com
        <h6></h6>
    </h1>
    <h2>Author: DavieB</h2>

</div>

<!--  Start here -->

<body>
<div id="wrap">
    <div id="accordian">
        <div class="step" id="step1">
            <div class="number">
                <span>1</span>
            </div>
            <div class="title">
                <h1>IDENTITAS PASIEN</h1>
            </div>
            <div class="modify">
                <i class="fa fa-plus-circle"></i>
            </div>
        </div>
        <div class="content w3-row" id="email">
            <div class="w3-col m6">
                <div>
                    <input type="email" name="email" value="" id="email-address" placeholder="Email Address" data-trigger="change" data-validation-minlength="1" data-type="email" data-required="true" data-error-message="Enter a valid email address."/><label for="email">Email Address</label>
                </div>
            </div>
            <div class="w3-col m6">
                <table id="identity-table">
                    <tr>
                        <td>No. Kartu BPJS</td>
                        <td>001218236139</td>
                    </tr>
                    <tr>
                        <td>NO KTP</td>
                        <td>151820100183018</td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>SATRIA WS</td>
                    </tr>
                    <tr>
                        <td>Tempat, Tanngal Lahir</td>
                        <td>Jakarta, 1 January 1992</td>
                    </tr>
                    <tr>
                        <td>Perusahaan</td>
                        <td>Jl. Kebon Jeruk Raya No. 27, Kebon Jeruk, Kb. Jeruk, Jakarta Barat, Daerah Khusus Ibukota Jakarta 11530</td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td>088828292820820</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>satriaws@gmail.com</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- HASIL PEMERIKSAAN -->
        <div class="step" id="step2">
            <div class="number">
                <span>2</span>
            </div>
            <div class="title">
                <h1>HASIL PEMERIKSAAN</h1>
            </div>
            <div class="modify">
                <i class="fa fa-plus-circle"></i>
            </div>
        </div>
        <div class="content w3-row">
            <div class="w3-col m6 w3-padding-small">
                <div class="w3-bottombar w3-border-blue w3-margin-right">
                    <h4>SUBJEKTIF</h4>
                </div>

                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4>KELUHAN UTAMA</h4>
                    </div>

                    <form class="w3-container">
                        <p>
                            <textarea class="w3-input" id="main-condition-text"
                                data-ul="#main-condition-ul" data-li="#main-condition-value"></textarea>
                            <!--VALUE-->
                            <ul class="w3-ul w3-card-4 w3-hide" id="main-condition-ul">
                                <li class="w3-padding-8">
                                    <span class="w3-large" id="main-condition-value" data-value="" data-status=""></span>
                                      <span
                                          data-input-value="#main-condition-value"
                                            data-input-element="#main-condition-text"
                                            data-ul="#main-condition-ul"
                                            class="w3-closebtn w3-margin-right w3-medium">x</span>
                                </li>
                            </ul>
                        </p>
                    </form>
                </div>

                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4>MULAI SEJAK</h4>
                    </div>

                    <form class="w3-container">
                        <p>
                            <textarea class="w3-input"></textarea>
                        </p>
                    </form>
                </div>

                <!--ADDITIONAL CONDITION-->
                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4 class="w3-left">KELUHAN TAMBAHAN</h4>
                    </div>
                    <form class="w3-container">
                        <p>
                            <ul class="w3-ul w3-card-4" id="additional-condition-ul">
                                <li class="w3-padding-8">
                                    <span onclick="this.parentElement.style.display='none'"
                                          class="w3-closebtn w3-large w3-margin-right">x</span>
                                    <span class="w3-large"><textarea class="w3-input add-codition-li-text"></textarea></span><br>
                                </li>
                            </ul>
                        </p>
                        <button class="w3-btn w3-round-xxlarge w3-ripple w3-left w3-red w3-margin"
                                id="btn-add-additional-condition">+ TAMBAH BARU</button>
                    </form>
                </div>
            </div>
            <div class="w3-col m6 w3-padding-small">
                <div class="w3-bottombar w3-border-blue w3-margin-right">
                    <h4>OBJEKTIF</h4>
                </div>

                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4>PEMERIKSAAN FISIK</h4>
                    </div>

                    <form class="w3-container">
                        <p>
                            <label class="w3-label">Tekenan Darah</label>
                            <input class="w3-input" type="text">
                        </p>
                        <p>
                            <label class="w3-label">Denyut Nadi</label>
                            <input class="w3-input" type="text">
                        </p>
                        <p>
                            <label class="w3-label">Suhu Tubuh</label>
                            <input class="w3-input" type="text">
                        </p>
                        <p>
                            <label class="w3-label">Tekanan Pernapasan</label>
                            <input class="w3-input" type="text">
                        </p>
                        <p>
                            <label class="w3-label">Tinggi Badan</label>
                            <input class="w3-input" type="text">
                        </p>
                        <p>
                            <label class="w3-label">Berat Badan</label>
                            <input class="w3-input" type="text">
                        </p>
                    </form>
                </div>

                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4 class="w3-left">PEMERIKSAAN PENUNJANG</h4>
                    </div>
                    <form class="w3-container w3-margin-top">
                        <div class="w3-row-padding">
                            <div class="w3-half">
                                <input class="w3-input w3-border" type="text" placeholder="One">
                            </div>
                            <div class="w3-half">
                                <input class="w3-input w3-border" type="text" placeholder="Two">
                            </div>
                        </div>
                        <button class="w3-btn w3-round-xxlarge w3-ripple w3-left w3-red w3-margin"
                                id="btn-add-support-examination">+ TAMBAH BARU</button>
                        <br/>
                    </form>
                </div>
            </div>
        </div>

        <!-- DIAGNOSA / ANALISA -->
        <div class="step" id="step3">
            <div class="number">
                <span>3</span>
            </div>
            <div class="title">
                <h1>DIAGNOSA / ANALISA</h1>
            </div>
            <div class="modify">
                <i class="fa fa-plus-circle"></i>
            </div>
        </div>
        <div class="content w3-row">
            <div class="w3-col m6 w3-padding-small">
                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4 class="w3-left">DIAGNOSA KERJA</h4>
                    </div>

                    <form class="w3-container">
                        <p>
                            <textarea class="w3-input" id="working-diagnose-text"
                                data-ul="#working-diagnose-ul" data-li="#working-diagnose-value"></textarea>
                            <!--VALUE-->
                            <ul class="w3-ul w3-card-4 w3-hide" id="working-diagnose-ul">
                                <li class="w3-padding-8">
                                    <span class="w3-large" id="working-diagnose-value" data-value="" data-status=""></span>
                                          <span
                                              data-input-value="#working-diagnose-value"
                                              data-input-element="#working-diagnose-text"
                                              data-ul="#working-diagnose-ul"
                                              class="w3-closebtn w3-margin-right w3-medium">x</span>
                                </li>
                            </ul>
                        </p>
                        <br/>
                    </form>
                </div>
            </div>
            <!--SUPPORT DIAGNOSE-->
            <div class="w3-col m6 w3-padding-small">
                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4 class="w3-left">DIAGNOSA PENUNJANG</h4>
                    </div>
                    <p></p>
                    <form class="w3-container">
                        <ul class="w3-ul w3-card-4" id="support-diagnose-ul">
                            <li class="w3-padding-8">
                                    <span onclick="this.parentElement.style.display='none'"
                                          class="w3-closebtn w3-large w3-margin-right">x</span>
                                <span class="w3-large"><textarea class="w3-input support-diagnose-li-text"></textarea></span><br>
                            </li>
                        </ul>
                        <button class="w3-btn w3-round-xxlarge w3-ripple w3-left w3-red w3-margin"
                                id="btn-add-support-diagnose">+ TAMBAH BARU</button>
                        <br/>
                    </form>
                </div>
            </div>
        </div>

        <!-- PENATALAKSANAAN -->
        <div class="step" id="step4">
            <div class="number">
                <span>4</span>
            </div>
            <div class="title">
                <h1>PENATALAKSANAAN</h1>
            </div>
            <div class="modify">
                <i class="fa fa-plus-circle"></i>
            </div>
        </div>
        <div class="content w3-row">
            <!--MEDICATION-->
            <div class="w3-col m6 w3-padding-small">
                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4 class="w3-left">TERAPI</h4>
                    </div>
                    <p></p>
                    <form class="w3-container">
                        <ul class="w3-ul w3-card-4" id="medication-ul">
                            <li class="w3-padding-8">
                                    <span onclick="this.parentElement.style.display='none'"
                                          class="w3-closebtn w3-large w3-margin-right">x</span>
                                <span class="w3-large"><textarea class="w3-input medication-li-text"></textarea></span><br>
                            </li>
                        </ul>
                       <button class="w3-btn w3-round-xxlarge w3-left w3-red w3-margin" id="btn-add-medication">+ TAMBAH BARU</button>
                        <br/>
                    </form>
                </div>
            </div>

            <div class="w3-col m6 w3-padding-small">
                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4 class="w3-left">RUJUKAN</h4>
                    </div>

                    <form class="w3-container">
                        <p>
                            <textarea class="w3-input"></textarea>
                        </p>
                        <br/>
                    </form>
                </div>
            </div>

            <div class="w3-center">
                <button class="w3-btn w3-green" id="button-save">SIMPAN</button>
            </div>

            <div class="autocomplete-suggestions">
                <div class="autocomplete-group"><strong>NHL</strong></div>
                <div class="autocomplete-suggestion autocomplete-selected">...</div>
                <div class="autocomplete-suggestion">...</div>
                <div class="autocomplete-suggestion">...</div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // Example:
    $(document).ready(function(){
        //Auto fit Textarea
        autosize($('textarea'));

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
            var $close_btn = $("<span>", {class: "w3-closebtn w3-large w3-margin-right"}).text("x");
            var $container = $("<span>", {class: "w3-large"});
            var $textarea = $("<textarea>", {class: "w3-input add-codition-li-text"});
            var $br = $("<br>");

            $textarea.bind("keypress", {}, keypressInBoxList);
            $textarea.appendTo($container);
            $close_btn.appendTo($li);
            $container.appendTo($li);
            $br.appendTo($li);
            $li.appendTo($ul)
        });

        // ADD NEW SUPPORT TEST
        $("#btn-add-support-diagnose").click(function(e){
            e.preventDefault();

            var $ul = $("#support-diagnose-ul");
            var $li = $("<li>", {class: "w3-padding-8", "data-value": "0"});
            var $close_btn = $("<span>", {class: "w3-closebtn w3-large w3-margin-right"}).text("x");
            var $container = $("<span>", {class: "w3-large"});
            var $textarea = $("<textarea>", {class: "w3-input support-diagnose-li-text"});
            var $br = $("<br>");

            $textarea.bind("keypress", {}, keypressInBoxList);
            $textarea.appendTo($container);
            $close_btn.appendTo($li);
            $container.appendTo($li);
            $br.appendTo($li);
            $li.appendTo($ul)
        });

        // ADD NEW MEDICATION
        $("#btn-add-medication").click(function(e){
            e.preventDefault();

            var $ul = $("#medication-ul");
            var $li = $("<li>", {class: "w3-padding-8", "data-value": "0"});
            var $close_btn = $("<span>", {class: "w3-closebtn w3-large w3-margin-right"}).text("x");
            var $container = $("<span>", {class: "w3-large"});
            var $textarea = $("<textarea>", {class: "w3-input medication-li-text"});
            var $br = $("<br>");

            $textarea.bind("keypress", {}, keypressInBoxList);
            $textarea.appendTo($container);
            $close_btn.appendTo($li);
            $container.appendTo($li);
            $br.appendTo($li);
            $li.appendTo($ul)
        });


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

        $("#btn-add-support-examination").click(function(e){
            e.preventDefault();
        });
    });
</script>
</body>
</html>
