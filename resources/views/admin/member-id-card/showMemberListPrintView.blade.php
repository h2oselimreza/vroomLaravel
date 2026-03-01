<html>
    <head>
        <style type="text/css">
            @media print {
                div.newPageDivClass {
                    page-break-after: always;
                }
            }
            .div-print{
                width: 50%;
                margin: auto; 
            }
            body {
                -webkit-print-color-adjust: exact;
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
        <div style="width:100%">
            <div id="my-div" class="my-div">
                <style>
                    .basic-info td{
                        border-collapse: collapse;
                        padding: 0px;
                    }
                    .merit-position td{
                        text-align: center;
                        padding: 5px;
                    }
                    table{
                        border-collapse: collapse;
                    }
                    table, td, th {
                        border: 1px solid black;
                    }

                    table, td, th {
                        font-size: 11px;
                        padding-left: 3px;
                    }

                    .div-print{
                        width: 775px;
                        font-family:Calibri;
                        background-color: #FFF;
                        padding: 10px 0px 10px 5px;
                    }
                    .footerHead{
                        text-align: center;
                    }
                    .heading h2{ height: 10px;margin-top: 5px;}
                    .signature-footer{
                        width: 100%;
                        display: inline-block;
                        padding: 20px 0px 0px 0px;
                    }
                    .left_signature{float: left;
                                    margin-left: 30px;
                                    font-size: 12px;
                    }
                    .right_signature{float: right;
                                     margin-right: 30px;
                    }
                    p.signature-line {
                        border-style: solid;
                        border-width: 2px 0px 0px 0px;
                    }
                    .text-left{
                        text-align:left;
                        padding-left: 5px;
                    }
                    .society-logo{float: left}
                    .society-name{text-align: center}
                    .heading{margin:auto;width: 360px;}

                    .member-image{
                        width:65px;
                        height: 70px;
                        border: 1px solid #2C2D87;

                    }
                </style>
                <div id="" class="div-print" >

                    <div class="heading">
                        <div class="society-logo">

                        </div>
                        <div class="society-name">
                            <img src="{{ asset('assets/images/company/company_logo.png') }}" style="width:200px;">

                            <h4>Member ID Card Receiving Sheet </h4>
                        </div>
                    </div>


                    <br>
                    <table style="width:99%">

                        <tr style="background-color: #eee;">
                            <th class="td-center" style="width:5%" class="td-center">SL</th>
                            <th class="td-center" style="width:18%">Member Name<br> Member ID <br>Member Mobile</th>
                            <th class="td-center" style="width:19%" >Member Type</th>
                            <th class="td-center" style="width:13%">DOB <br>Blood Group</th>
                            <th class="td-center" style="width:20%">Emergency Contact</th>
                            <th class="td-center" style="width:10%">Photo</th>
                            <th class="td-center" style="width:15%">Signature</th>
                        </tr>
                        <?php
                        $count = 1;

                        $trColor = '#fff';
                        foreach ($members as $memberDetail) {
                            echo "<tr style='background-color:$trColor'>";
                            if ($trColor == '#fff') {
                                $trColor = '#eee';
                            } elseif ($trColor == '#eee') {
                                $trColor = '#fff';
                            }
                            echo "<td style='text-align:center'>" . $count . "</td>";
                            ?>

                            <td><?php echo $memberDetail->member_name . "<br>" . $memberDetail->member_id .($memberDetail->donar_member_id ? "<br>".$memberDetail->donar_member_id : ""). "<br>" . $memberDetail->primary_mobile; ?></td>
                            <td align="center">
                                {{ $memberDetail->member_type_name ?? '' }}
                            </td>

                            <td align="center">
                                {{ isset($memberDetail->dob) ? \Carbon\Carbon::parse($memberDetail->dob)->format('d-m-Y') : '' }}
                                <br>
                                {{ $memberDetail->blood_group ?? '' }}
                            </td>

                            <td>
                                {{ $memberDetail->emer_conatct_mobile ?? '' }}
                            </td>

                            <td align="center">
                                <img src="{{ asset('storage/member/' . ($memberDetail->member_image ?? 'no-image.png')) }}" 
                                    class="member-image">
                            </td>

                            <td></td>

                            <?php
                            echo "</tr>";
                            $count++;
                        }
                        ?>
                    </table>
                    <br>
                    <div class="signature-footer">
                        <div class="left_signature">
                            Generated By:
                            <b>
                                {{ auth()->user()->full_name ?? auth()->user()->name }} ({{ auth()->user()->username ?? auth()->user()->email }})
                            </b>
                            <br>
                            Generated Date & Time:
                            <b>
                                {{ now()->format('F j, Y, g:i a') }}
                            </b>
                        </div>
                         <div class="right_signature">
                            <p class="signature-line">Signature of Admin</p>
                        </div>

                    </div>
                    <div class="footerHead">
                        <br>Developed By <b>ArrowLink™ Soft</b>
                    </div>
                    <div class="newPageDivClass"></div>


                </div>	<!-- print div-->
            </div> <!-- my div -->
        </div>
        <br></br>
        <div>
            <input name="b_print" type="button" onClick="printdiv('my-div');" value=" Print ">
        </div>
        <br>
    </body>
</html>