<!DOCTYPE html>
<html>
    <head>
        <title>Report</title>
       <link rel="icon" href="{{ asset('assets/images/company/favicon1.png') }}" type="image/x-icon"/>
        
        <style type="text/css">
            @media print {
                div.newPageDivClass {
                    page-break-after: always;
                }
            }
            .content-div{
                width: 50%;
                margin: auto; 
                padding: 10px 20px 10px 20px;
                /*                border: 1px solid black;*/
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
    <body style="background-color: #eee;">
        <input name="b_print" type="button" onClick="printdiv('my-div');" value=" Print ">

        <br>

        <div class="full-page" >
            <div id="my-div" class="my-div">
                <style>
                    .content-div{width: 735px;font-family:Calibri;background-color: #FFF;}
                    .heading-right{padding-right: 40px;}
                    .heading-right-head{font-weight: bold;font-size: 25px;}
                    .heading-right-body{font-size: 14px;line-height: 14px;}
                    .heading-border-bottom{margin: 10px 10px;border: 1px solid #ababab;}
                    .body-heading1{font-weight: bold;font-size: 19px;}
                    .body-heading2{font-size: 16px;}
                    .r-p-5{padding-right: 5px}
                    .cust-workshop-detail {border-collapse: collapse;outline: 1px solid black;}
                    .cust-workshop-detail td{border: 1px solid black;font-size: 13px;padding-left: 5px;}
                    .quo-status{font-size: 14px}
                    .description{ margin-top: 20px;border-collapse: collapse;outline: 2px solid black;}
                    .description td{font-size: 13px;padding-left: 5px;padding-right: 5px;}
                    .description th{background-color: #eee;font-size: 13px;padding: 5px;text-align: center;}
                    .left-border{border-left: 1px solid black;} 
                    .right-border{ border-right: 1px solid black;} 
                    .top-border{border-top: 1px solid black; } 
                    .bottom-border{border-bottom: 1px solid black;} 
                    .vehicle-reg{padding:5px}
                    .product-label{padding-top:5px}
                    .double-underline{text-decoration: underline;text-decoration-style:double;padding-bottom: 5px;}
                    .m-t-20{margin-top: 20px;}
                    .note{text-align:justify;font-size: 13px;}
                    .left_signature{float: left;margin-left: 30px;}
                    .right_signature{float: right;margin-right: 30px;}
                    .generated-footer{font-size: 12px;width: 100%;display: inline-block;padding: 0px 0px 0px 0px;}
                    .footer{text-align: center;font-family:Calibri;font-size:12px; margin-top: -5px;}

                </style>

                <div id="" class="content-div">
                    @include('client.report.corporate-expense-report.reportHeaderView')
                    <div class="heading-border-bottom"></div>
                    <table border="0" cellpadding="0" cellspacing="0" align="center" width="726">
                        
                        <tr>
                            <td align="center" class="body-heading2">Product Wise Stock In Report</td>
                        </tr>
                        <tr>
                            <td align="center" class="body-heading2">From <?php echo get_date_format1($fromDate) ?> To <?php echo get_date_format1($toDate) ?></td>
                        </tr>
                    </table>

                    <table class="description" cellpadding="0" cellspacing="0" width="726">
                        <tr>
                            <th class="bottom-border right-border" width="176"><b>Category</b></th>
                            <th class="bottom-border right-border" width="200"><b>Product</b></th>
                            <th class="bottom-border right-border" width="200"><b>Variant</b></th>
                            <th class="bottom-border right-border" width="50"><b>Quantity</b></th>
                            <th class="bottom-border right-border" width="100"><b>Unit Name</b></th>
                        </tr>
                        <?php
                        foreach ($stockInDetails as $stockInDetail) {
                            $quantity = $stockInDetail->credit_quantity - $stockInDetail->debit_quantity;
                            echo "<tr>";
                            echo "<td class='top-border right-border' align='left'>" . $stockInDetail->category_name . "</td>";
                            echo "<td class='top-border right-border' align='left'>" . $stockInDetail->product_name . "</td>";
                            echo "<td class='top-border right-border' align='left'>" . $stockInDetail->variant_name . "</td>";
                            echo "<td class='top-border right-border' align='right'>" . number_format($quantity,2) . "</td>";
                            echo "<td class='top-border right-border' align='left'>" . $stockInDetail->unit_name . "</td>";

                            echo "</tr>";
                        }
                        ?>
                    </table>
                    <br>

                    <div class="generated-footer">
                        <div class="left_signature">
                            Printed By:<b> {{ auth()->user()->full_name }} </b>
                        </div>
                        <div class="right_signature">
                            Printed Date & Time: <b> <?php echo date("F j, Y, g:i a"); ?></b>
                        </div>
                    </div>
                    <div class="footer">
                        <br> {{ config('constants.REPORT_FOOTER_CREDIT') }}
                    </div>
                </div>
                <br>
            </div>  
        </div>  
        <br>
    </body>
</html>