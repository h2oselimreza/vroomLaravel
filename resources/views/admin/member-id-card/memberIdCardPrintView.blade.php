<html>
    <head>
        <script src="{{ asset('assets/select_bo/js/jquery-1.11.1.min.js') }}" type="text/javascript"></script>
        <style type="text/css">
            @media print {
                div.newPageDivClass {
                    page-break-after: always;
                }
            }

            @page {
                margin: 0;
            }
            body {
                -webkit-print-color-adjust: exact;
            }
            .div-print{
                /*width: 50%;*/
                margin: auto; 
                padding: 5px 10px 0px 0px;
            }
            td{
                word-wrap:break-word
            }

        </style>  
        <script type="text/javascript">
            function printdiv(printpage)
            {
                var headstr = "<html><head><title></title>";
                var style = "<link rel='stylesheet' href='' type='text/css' />";
                var headstr1 = "</head><body>";
                var footstr = "</body>";
                var newstr = document.all.item(printpage).innerHTML;
                var oldstr = document.body.innerHTML;
                document.body.innerHTML = headstr + style + headstr1 + newstr + footstr;
                window.print();
                document.body.innerHTML = oldstr;
                return false;
            }
        </script>
    </head>
    <body style="background-color:#eee">
        <div>
            <input name="b_print" type="button" id="print-btn" onClick="printdiv('my-div');" value=" Print ">
        </div>
        <br>
        <div style="width:100%">
            <div id="my-div" class="my-div">
                <style>

                    table{
                        border-collapse: collapse;
                    }
                    table, td, th {
                        /*border: 1px solid black;*/
                    }

                    .text-left{
                        text-align:left;
                        padding-left: 5px;
                    }

                    .div-print{
                        width: 86mm; 
                        height: 54mm;
                        font-family:Calibri; 
                        /*                        background-color: white;*/
                    }

                    .card-layout-iamge{
                        background-image: url('{{ asset('assets/images/company/member_id_card.png') }}');
                        background-size: 86mm 54mm;
                        background-repeat: no-repeat;
                        height: 100%;
                        width: 100%;
                        border-radius: 10px;
                        /*border: 1px solid black;*/
                    }

                    .card-layout-iamge-back-part{
                        background-image: url('{{ asset('assets/images/company/member-id-card-back.jpg') }}');
                        background-size: 86mm 54mm;
                        background-repeat: no-repeat;
                        height: 100%;
                        width: 100%;
                        border-radius: 15px;
                        /*border: 1px solid black;*/
                    }
                    .card-layout{
                        height: 100%;
                        width: 100%;
                        /*border: 1px solid black;*/
                    }
                    .society-name{
                        font-weight: bold;
                        font-size: 18px;
                        /*                        /font-family: 'Impact, Charcoal, sans-serif';*/
                        color:#2C2D87;
                    }
                    .society-address{
                        font-size: 14px;
                        color:#057E39;
                        font-weight: bold;
                    }
                    .horizontal-line{
                        border: 0.5px solid #ddd;
                        border-style: dotted;
                    }
                    .idcard-heading{
                        background-color: #2C2D87;
                        color: white;
                        text-align: center;
                        font-weight: bold;
                        font-size: 14px;
                        padding: 1px;
                    }

                    .idcard-return{
                        background-color: #2C2D87;
                        color: white;
                        text-align: center;
                        font-weight: bold;
                        font-size: 13px;
                        padding: 0px;
                    }

                    .main-info th,td{
                        font-size: 11px;
                        font-weight:bold;
                        color:#093e24;
                    }
                    .main-info td{
                        padding-left: 5px;
                        padding-right: 10px;
                    }

                    .main-info{
                        margin-top: 66px;
                    }


                    .student-image{
                        width:80px;
                        border: 2px solid #53974c;

                    }
                    .headmaster-signature{
                        height: 31px;

                    }
                    .headmaster-sign-label{
                        font-size: 13px;
                        float: right;
                    }
                    .student-id{
                        font-size: 10px;
                    }
                    .right-table td{
                        padding-bottom: 0px;
                    }

                    .right-table{
                        width:22%;
                        float:right;
                        margin-top:40px;
                        margin-left: 20px;
                    }

                    .main-info-back{
                        margin-top: 1px;
                    }

                    .main-info-back td,th{
                        padding-left: 2px;
                        font-size: 10px;
                        color:#53974c;
                        /*padding-right: 0px;*/
                    }

                    .society-name-back{
                        font-weight: bold;
                        font-size: 15px;
                        color:#2C2D87;
                    }
                    .society-address-back{
                        font-size: 11px;
                        /*                        color:#057E39;*/
                        font-weight: bold;
                        color:#057E39;
                    }
                    .developed-by{
                        font-size:10px;
                        color:#be763f;

                    }

                </style>

                <?php
                $memberArr = array();
                $flag = 0;
                
                foreach ($members as $memberDetail) {
                    $flag++;
                    $qrCodeStr = $memberDetail->member_name . "\n" .
                            $memberDetail->member_type_name . "\n" .
                            $memberDetail->primary_mobile . "\n" .
                            $memberDetail->blood_group;

                        // Paths
                        // $barcodeFile = public_path('assets/images/idCardQrBarcode/' . $memberDetail->member_id . 'b.png');
                        // $qrcodeFile  = public_path('assets/images/idCardQrBarcode/' . $memberDetail->member_id . 'q.png');

                        // // Generate barcode and QR code
                        // barcodeGenerate($barcodeFile, $memberDetail->member_id);
                        // qrCodeGenerate($qrcodeFile, $qrCodeStr);

                        // // Get base64 encoded contents
                        // $barcodeFileContents = base64_encode(file_get_contents($barcodeFile));
                        // $qrcodeFileContents = base64_encode(file_get_contents($qrcodeFile));
                    if ($flag == 1) {
                        $style = "padding-top:0px;";
                    } else {
                        $style = "padding-top:8px;";
                    }
                    ?>
                    <!--<div class='newPageDivClass'></div>-->
                    <!-- ---------- front ----------- -->
                    <div class="div-print div-print-image" style="<?php echo $style ?>">
                        <div class="card-layout-iamge">

                            <div class="main-content">
                            <table class="right-table" style="width:22%;float:left">
                                    <tr>
                                        <td style="" align="right">
                                            <img class="student-image" src="{{ asset('storage/member/' . ($memberDetail->member_image ?? 'no-image.png')) }}" style="">
                                        </td>
                                    </tr>
                                   
                                
                                </table>
                                <table class="main-info" style="width:67%;float:right">
                                    <tr>
                                    
                                        <td style="width:68%; font-size: 14px;font-weight: bold;color:green" align="left" colspan="2"><?php echo $memberDetail->member_name ?></td>
       
                                    </tr>
                                    <tr>

                                        <td align="left" style="font-size: 13px;color:blue"><?php
                                            echo $memberDetail->member_id;
                                            if($memberDetail->donar_member_id) {
                                                echo "&nbsp; . &nbsp;" .$memberDetail->donar_member_id;
                                            }
                                            ?>

                                        </td>
                                    </tr> 
                                    <tr>
                                        <td align="left" style="color:red">Blood Group <?php echo $memberDetail->blood_group ?></td>
                                        
                                    </tr>
                                </table>

                                
                            </div>

                        </div>
                    </div>

                    <!-- back part -->
                    <div class='newPageDivClass'></div>
                    <div id="" class="div-print">
                        <div class="card-layout-iamge-back-part">
                            <div class="main-content" >
                                <div style="padding: 163px 0px 0px 145px; font-size: 10px"><?php echo 'issueDate'?></div>
                            </div>
                        </div>
                    </div>
                    <div class='newPageDivClass'></div>

                    <?php
                    $memberArr[] = $memberDetail->member_id;
                }
                $memberStr = implode(',', $memberArr);
                ?>



            </div> 
        </div> 
    </body>
</html>

