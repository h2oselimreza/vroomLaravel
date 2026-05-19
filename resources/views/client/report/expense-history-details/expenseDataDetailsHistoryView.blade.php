@extends('client.layouts.app')
@section('content')
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
<div class="block-header">
    <h2>EXPENSE DETAILS HISTORY REPORT</h2><br>
    <div class="breadcrumb breadcrumb-bg-blue-grey">
        <li><a href="#"> Home</a></li>
        <li><a href="#"> Report</a></li>
        <li><a href="{{ route('client.report.expense-details-history') }}"> Expense Details History</a></li>
    </div>
</div>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="body">
                <div class="row">
                    <div id="showMoreProductDiv"></div>
                    <input type="hidden" value="<?php echo $fromDate ?? null ?>" id="fromDate">
                    <input type="hidden" value="<?php echo $toDate ?? null ?>" class="form-control" id="toDate" >
                    <input type="hidden" value="<?php echo $vehicleIdStr ?? null ?>" id="vehicleStr">
                    <input type="hidden" value="<?php echo $expenseHeadCode ?? null ?>" class="form-control" id="expenseHeadCode" >
                    <input type="hidden" value="<?php echo $vendorCode ?? null ?>" id="vendorCode">
                </div>

                <div class="row">

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <input name="b_print" type="button" id="printBtn" class="btn btn-primary" onClick="printdiv('my-div');" value=" Print " disabled>
                    </div>

                    <div id="my-div" class="my-div">
                        <style>
                            .content-div{width: 760px;font-family:Calibri;background-color: #FFF;}
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
                            .right-border-gray{border-right: 1px solid #ddd}
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
                                    <td align="center" class="body-heading2">Expense History</td>
                                </tr>
                                <tr>
                                    <td align="center" class="body-heading2">From <?php echo get_date_format1($fromDate) ?> To <?php echo get_date_format1($toDate) ?></td>
                                </tr>
                            </table>

                            <table id="expenseTable" class="description" cellpadding="0" cellspacing="0" width="726">
                                <tr>
                                    <th class="bottom-border right-border" width="150"><b>Date</b></th>
                                    <th class="bottom-border right-border" width="150"><b>Expense No</b></th>
                                    <th class="bottom-border right-border" width="120"><b>Vehicle</b></th>
                                    <th class="bottom-border right-border" width="120"><b>Vendor</b></th>
                                    <th class="bottom-border right-border" width="120"><b>Expense</b></th>
                                    <th class="bottom-border right-border" width="60"><b>Quantity</b></th>
                                    <th class="bottom-border right-border" width="120"><b>Unit Price<br>(BDT)</b></th>
                                    <th class="bottom-border right-border" width="60"><b>Adjust<br>(BDT)</b></th>
                                    <th class="bottom-border right-border" width="120"><b>Amount<br>(BDT)</b></th>
                                    <th class="bottom-border right-border" width="140"><b>Remarks</b></th>
                                </tr>
                            </table>
                            <div class="text-center"><h4 class="text-danger" id="noExpenseFound"></h4></div>
                            <hr>
                            <div class="generated-footer">
                                <div class="left_signature">
                                    Printed By:<b> {{ auth()->user()->full_name }} </b>
                                </div>
                                <div class="right_signature">
                                    Printed Date & Time: <b><?php echo date("F j, Y, g:i a"); ?></b>
                                </div>
                            </div>
                            <div class="footer">
                                <br> {{ config('constants.REPORT_FOOTER_CREDIT')}}
                            </div>
                        </div>
                        <br>
                    </div>  




                </div>

            </div>
        </div>
    </div>
</div>    
@endsection
@push('scripts')
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
<script>
    var iterationNumber = 0;
    var SHOW_MORE_DATA_COUNT = '{{ config("constants.WEB_SHOW_MORE_DATA_COUNT") }}';
    var expenseDetailsUrl = "/client/Expense/showExpense?expenseNo=";
    var generalExpenseDetailsUrl = "/client/Expense/showGeneralExpense?expenseNo=";

    function getReportData() {
        serial = 0;
        iterationNumber = 0;
        loadData(1);
    }
    var getStockDataFlag = 1;

    function loadData(noExpenseflag) {

        if (getStockDataFlag === 0) {
            $("#printBtn").attr("disabled", false);
            return false;
        }

        if (noExpenseflag === 1) {
            showLoader();
        }

        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();
        var vehicleStr = $('#vehicleStr').val();
        var expenseHeadCode = $('#expenseHeadCode').val();
        var vendorCode = $('#vendorCode').val();

        $.ajax({
            type: 'POST',
            url: "{{ route('client.report.getExpenseDetailsHistoryData') }}",
            data: {
                iterationNumber: iterationNumber,
                fromDate: fromDate,
                toDate: toDate,
                vehicleStr: vehicleStr,
                expenseHeadCode: expenseHeadCode,
                vendorCode: vendorCode,
                _token: "{{ csrf_token() }}"
            },
            success: function (result) {
                console.log(result);
                iterationNumber++;
                hideLoader();
                // Laravel returns JSON automatically
                var resultObj = (typeof result === "string")
                    ? JSON.parse(result)
                    : result;

                var expenseLength = resultObj.expenses.length;

                var tableTd = "";
                var tableTr = "";
                var loopValue = expenseLength;

                if (expenseLength > SHOW_MORE_DATA_COUNT) {

                    loopValue = expenseLength - 1;
                    $('#showMoreProductDiv').html('');

                } else {

                    getStockDataFlag = 0;
                }

                for (var i = 0; i < loopValue; i++) {

                    tableTd = "";
                    serial++;
                    tableTd += getExpenseTd(resultObj, i);
                    tableTr += "<tr>" + tableTd + "</tr>";
                }

                $('#expenseTable').append(tableTr);

                if (tableTr === "" && noExpenseflag === 1) {

                    $('#noExpenseFound').text('No Expense Found');
                }
            },

            complete: function () {

                loadData(2);
                getStockDataFlag++;
            }
        });
    }
    function getExpenseTd(resultObj, i) {
        var vehicle = "";
        var remarks = "";
        var vendorTitle = "";
        var expenseUrl = generalExpenseDetailsUrl;
        if (resultObj.expenses[i].registration_no) {
            vehicle = resultObj.expenses[i].registration_no;
            expenseUrl = expenseDetailsUrl;
        }
        if (resultObj.expenses[i].remarks) {
            remarks = resultObj.expenses[i].remarks;
        }
        vendorTitle = resultObj.expenses[i].guest_vendor_title;
        if (resultObj.expenses[i].vendor_title) {
            vendorTitle = resultObj.expenses[i].vendor_title;
        }
        var html = "<td class='right-border-gray bottom-border-gray'>" + resultObj.expenses[i].expense_date + "</td>\n\
                    <td class='right-border-gray bottom-border-gray'><span class='pointer'><a target='_blank' href='" + expenseUrl + resultObj.expenses[i].expense_no + "'>" + resultObj.expenses[i].expense_no + "</a></span></td>\n\
                    <td class='right-border-gray bottom-border-gray'>" + vehicle + "</td>\n\
                    <td class='right-border-gray bottom-border-gray'>" + vendorTitle + "</td>\n\
                    <td class='right-border-gray bottom-border-gray'>" + resultObj.expenses[i].expense_title + "</td>\n\
                    <td class='right-border-gray bottom-border-gray' align='center'>" + resultObj.expenses[i].quantity + "</td>\n\
                    <td class='right-border-gray bottom-border-gray' align='right'>" + resultObj.expenses[i].unit_price + "</td>\n\
                    <td class='right-border-gray bottom-border-gray' align='right'>" + resultObj.expenses[i].adjust + "</td>\n\
                    <td class='right-border-gray bottom-border-gray' align='right'>" + resultObj.expenses[i].amount + "</td>\n\
                    <td class='right-border-gray bottom-border-gray'>" + remarks + "</td>";

        return html;
    }
    $(function () {
        getReportData();
    });
</script>
@endpush