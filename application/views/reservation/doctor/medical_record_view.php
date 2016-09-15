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
                            <input class="w3-input" type="text">
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

                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4 class="w3-left">KELUHAN TAMBAHAN</h4>
                        <h4></h4><button class="w3-btn w3-round-xxlarge w3-right w3-red">+ ADD</button></h4>
                    </div>
                    <form class="w3-container">
                        <p>
                        <ul class="w3-ul w3-card-4">
                            <li class="w3-padding-8">
                                <span onclick="this.parentElement.style.display='none'"
                                      class="w3-closebtn w3-margin-right">x</span>
                                <span class="w3-xlarge">Mike</span><br>
                            </li>
                            <li class="w3-padding-8">
                                <span onclick="this.parentElement.style.display='none'"
                                      class="w3-closebtn w3-margin-right">x</span>
                                <span class="w3-xlarge">Mike</span><br>
                            </li>
                            <li class="w3-padding-8">
                                <span onclick="this.parentElement.style.display='none'"
                                      class="w3-closebtn w3-margin-right">x</span>
                                <span class="w3-xlarge">Mike</span><br>
                            </li>
                        </ul>
                        </p>
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

                    <form class="w3-container">
                        <h4></h4><button class="w3-btn w3-round-xxlarge w3-left w3-red w3-margin">+ TAMBAH BARU</button></h4>
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
        <div class="content">
            <div class="w3-col m6 w3-padding-small">
                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4 class="w3-left">DIAGNOSA KERJA</h4>
                    </div>

                    <form class="w3-container">
                        <p>
                            <input class="w3-input" type="text">
                        </p>
                        <br/>
                    </form>
                </div>
            </div>
            <div class="w3-col m6 w3-padding-small">
                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4 class="w3-left">DIAGNOSA PENUNJANG</h4>
                    </div>

                    <form class="w3-container">

                        <h4></h4><button class="w3-btn w3-round-xxlarge w3-left w3-red w3-margin">+ TAMBAH BARU</button></h4>
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
        <div class="content">
            <div class="w3-col m6 w3-padding-small">
                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4 class="w3-left">TERAPI</h4>
                    </div>

                    <form class="w3-container">

                        <h4></h4><button class="w3-btn w3-round-xxlarge w3-left w3-red w3-margin">+ TAMBAH BARU</button></h4>
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
                            <input class="w3-input" type="text">
                        </p>
                        <br/>
                    </form>
                </div>
            </div>
        </div>


        <button class="w3-btn-block w3-teal">SIMPAN</button>
    </div>
</div>

<script type="text/javascript">

</script>

</body>
</html>
