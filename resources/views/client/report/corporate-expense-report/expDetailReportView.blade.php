<html>
    <head>
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
                    .bottom-border-gray{border-bottom: 1px solid #ddd;} 
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
                            <td align="center" class="body-heading2">Statement of Vehicle and Head Wise Expense</td>
                        </tr>
                        <tr>
                            <td align="center" class="body-heading2">From <?php echo get_date_format1($data['fromDate']) ?> To <?php echo get_date_format1($data['toDate']) ?></td>
                        </tr>
                    </table>

                    <table class="description" cellpadding="0" cellspacing="0" width="726">
                        <tr>
                            <th class="bottom-border right-border" width="150"><b>Expense Head</b></th>
                            <th class="bottom-border right-border" width="120"><b>Total Times of Transaction</b></th>
                            <th class="bottom-border right-border" width="100"><b>Quantity</b></th>
                            <th class="bottom-border right-border" width="140"><b>Unit Price(AVG)<br>(BDT)</b></th>
                            <th class="bottom-border right-border" width="140"><b>Total Mileage<br>(KM)</b></th>
                            <th class="bottom-border right-border" width="140"><b>AVG Expense per KM<br>(BDT)</b></th>
                            <th class="bottom-border right-border" width="110"><b>Expense (BDT)</b></th>
                        </tr>
<!--                        <tr>
                            <th class="bottom-border right-border" width="590"><b>Description</b></th>
                            <th class="bottom-border right-border" width="136"><b>Expense (BDT)</b></th>
                        </tr>-->
                        <?php
                        $totalExpense = 0;
                        $vehicle = "";
                        $expenseCategory = "";
                        $expenseHead = "";
                        $subTotal = 0;
                        $subTotalFlag = 0;
                        $inTotal = 0;
                        $inTotalFlag = 0;
                        $grandTotal = 0;
                        foreach ($data['expenseDetails'] as $expenseDetail) {
                            if ($expenseDetail->vehicle != $vehicle) {
                               // $registrationNo = $expenseDetail['registration_no'];
                               
                               $purchaseDate=get_date_format1($expenseDetail->purchase_date);
                               $purchaseCost=$expenseDetail->purchase_cost;
                               $presentBookValue=$expenseDetail->present_book_value;
                               $vehiclePurchaseInfo="";
                               $vehicleOtherInfo=$expenseDetail->vehicle_type_name." ".$expenseDetail->brand_name." ".$expenseDetail->brand_model_name;
                                $registrationNo = $expenseDetail->registration_no;
                                
                                if ($purchaseCost>0 && $presentBookValue>0){
                                    $vehiclePurchaseInfo="<b>Purchase Date: </b>".$purchaseDate."<b> Purchase Cost: </b>".number_format($purchaseCost,2)."<b> Present Book Value: </b>".number_format($presentBookValue,2);
                                }
                                 if ($purchaseCost>0 && $presentBookValue<1){
                                    $vehiclePurchaseInfo="<b>Purchase Date: </b>".$purchaseDate."<b> Purchase Cost: </b>".number_format($purchaseCost,2)."<b> Present Book Value: </b>1.00";
                                }

                                if ($subTotalFlag) {
                                    echo "<tr>";
                                    echo "<td class='right-border' align='right' colspan='6'><b>Sub Total</b></td>";
                                    echo "<td class='right-border' align='right'><b><u>" . number_format($subTotal, 2) . "</u></b></td>";
                                    echo "</tr>";
                                    $subTotal = 0;
                                    $subTotalFlag = 0;
                                }

                                if ($inTotalFlag) {
                                    echo "<tr>";
                                    echo "<td class='right-border' align='right' colspan='6'><b>In Total</b></td>";
                                    echo "<td class='right-border double-underline' align='right'><b><u>" . number_format($inTotal, 2) . "</u></b></td>";
                                    echo "</tr>";
                                    $inTotal = 0;
                                    $inTotalFlag = 0;
                                }


                                echo "<tr>";
                                echo "<td class='right-border top-border vehicle-reg' align='center' colspan='6'><b>" . $registrationNo . "</b><small><i>(".$vehicleOtherInfo.")</i></small><br>".$vehiclePurchaseInfo. "</td>";
                                echo "<td class='top-border right-border' align='right'></td>";
                                echo "</tr>";
                                $vehicle = $expenseDetail->vehicle;
                                $expenseCategory = "";
                                $expenseHead = "";
                            }
                            if ($vehicle == $expenseDetail->vehicle && $expenseDetail->cost_category != $expenseCategory) {
                                if ($subTotalFlag) {
                                    echo "<tr>";
                                    echo "<td class='right-border' align='right' colspan='6'><b>Sub Total</b></td>";
                                    echo "<td class='right-border' align='right'><b><u>" . number_format($subTotal, 2) . "</u></b></td>";
                                    echo "</tr>";
                                    $subTotal = 0;
                                    $subTotalFlag = 0;
                                }
                                echo "<tr>";
                                echo "<td class='right-border' align='left' colspan='6'><b><u>" . $expenseDetail->category_name . "</u></b></td>";
                                echo "<td class='right-border' align='right'></td>";
                                echo "</tr>";
                                $expenseCategory = $expenseDetail->cost_category;
                            }

                            if ($vehicle == $expenseDetail->vehicle && $expenseCategory == $expenseDetail->cost_category && $expenseDetail->expense_head != $expenseHead) {
                                echo "<tr>";
                                echo "<td class='bottom-border-gray' align='left'>" . $expenseDetail->expense_head_name . "</td>";
                                echo "<td class='bottom-border-gray' align='center'>" . $expenseDetail->total_tran . " </td>";
                                echo "<td class='bottom-border-gray' align='center'>" . $expenseDetail->total_quantity . " " . $expenseDetail->unit_name . " </td>";
                                echo "<td class='bottom-border-gray' align='right'>" . number_format($expenseDetail->average_unit_price, 2) . "</td>";
                                echo "<td class='bottom-border-gray' align='center'>" . number_format($expenseDetail->total_mileage, 2) . "</td>";
                                $avgExpense = "0.00";
                                if ($expenseDetail->total_mileage) {
                                    if($expenseDetail->total_mileage != 0){
                                        $avgExpense = number_format($expenseDetail->total_expense / $expenseDetail->total_mileage, 2);
                                    }

                                }
                                echo "<td class='right-border bottom-border-gray' align='center'>" . $avgExpense . "</td>";

                                echo "<td class='right-border bottom-border-gray' align='right'>" . $expenseDetail->total_expense . "</td>";
                                echo "</tr>";
                                $subTotal += $expenseDetail->total_expense;
                                $subTotalFlag = 1;
                                $expenseHead = $expenseDetail->expense_head;
                                $inTotalFlag = 1;
                                $inTotal += $expenseDetail->total_expense;
                                $grandTotal += $expenseDetail->total_expense;
                            }
                            $totalExpense += $expenseDetail->total_expense;
                        }
                        echo "<tr>";
                        echo "<td class='right-border' align='right' colspan='6'><b>Sub Total</b></td>";
                        echo "<td class='right-border' align='right'><b><u>" . number_format($subTotal, 2) . "</u></b></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td class='right-border' align='right' colspan='6'><b>In Total</b></td>";
                        echo "<td class='right-border double-underline' align='right'><b><u>" . number_format($inTotal, 2) . "</u></b></td>";
                        echo "</tr>";
                        ?>
                    </table>


                    <table class="description" cellpadding="0" cellspacing="0" width="726">
                        <tr>
                            <th class="bottom-border right-border" width="590"><b>Expense Category</b></th>
                            <th class="bottom-border right-border" width="136"><b>Expense SubTotal (BDT)</b></th>
                        </tr>
                        <?php
                        foreach ($data['categoryDetails'] ?? [] as $categoryDetail) {
                            echo "<tr>";
                            echo "<td class='bottom-border right-border'>$categoryDetail->category_name</td>";
                            echo "<td class='bottom-border right-border' align='right'>" . number_format($categoryDetail->total_expense, 2) . "</td>";
                            echo "</tr>";
                        }
                        echo "<tr>";
                        echo "<td class='top-border right-border' align='right'><b>Grand Total</b></td>";
                        echo "<td class='top-border right-border' align='right'><b>" . number_format($grandTotal, 2) . "</b></td>";
                        echo "</tr>";
                        ?>
                    </table>

                    <?php
                    $grandTotalInWords = "";
                    if ($grandTotal) {
                        $numberArr = explode('.', number_format($grandTotal, 2, '.', ''));
                        $grandTotalInTaka = numberConvertToWords($numberArr[0]);
                        $grandTotalInFraction = numberConvertToWords($numberArr[1]);
                        $grandTotalInWords = $grandTotalInTaka . " Taka Only";
                        if ($grandTotalInFraction) {
                            $grandTotalInWords .= " And " . $grandTotalInFraction . " Poisa Only";
                        }
                    }
                    ?>
                    <table class="m-t-20" border="0" cellpadding="0" cellspacing="0"  width="726">
                        <tr>
                            <td align="left" class="quo-status r-p-5 p-b-5"><b>In Words: </b> <?php echo $grandTotalInWords; ?></td>
                        </tr>
                    </table>


                    <hr>
                    <div class="generated-footer">
                        <div class="left_signature">
                            Printed By:<b> {{ auth()->user()->full_name }}</b>
                        </div>
                        <div class="right_signature">
                            Printed Date & Time: <b><?php echo date("F j, Y, g:i a"); ?></b>
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