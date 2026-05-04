@extends('layouts.app')
@section('content')
<style>
    .panel {
        padding: 0px; 
    }

</style>

<div class="header dashboard_from">
    <h1 class="page-title">Call Log</h1>
    <ul class="breadcrumb">
        <li><a href="#">Home</a> / </li>
        <li><a href="#">  CRM</a> / </li>
        <li><a href="#">Customer Search</a></li>
    </ul>
</div>
<div class="main-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="row">
        <div class="col-sm-12 col-md-12">

            <form action="admin/Crm/customerSearch" method="post">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="personalContactHeading">
                        <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#" href="#basicSearchCollapse" aria-expanded="false" aria-controls="basicSearchCollapse">
                                <i class="fa fa-user"></i> Basic Search
                            </a>
                        </h4>
                    </div>
                    <div id="basicSearchCollapse" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="basicSearchHeading">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group" >
                                        <label> Customer Name </label>
                                        <input type="text" class="form-control" name="customerName" id="customerName">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group" >
                                        <label> Customer Mobile </label>
                                        <input type="text" class="form-control" name="customerMobile" id="customerMobile">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group" >
                                        <label> Customer ID </label>
                                        <input type="text" class="form-control" name="customerId" id="customerId">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="personalContactHeading">
                        <h4 class="panel-title">
                            <a class="collapsed" id="personalContactLink" role="button" data-toggle="collapse" data-parent="#" href="#advanceSearchCollapse" aria-expanded="false" aria-controls="advanceSearchCollapse">
                                <i class="fa fa-bars"></i> Advance Search
                            </a>
                        </h4>
                    </div>
                    <div id="advanceSearchCollapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="advanceSearchHeading">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">

                                    <div id="vehicleServiceDiv">
                                        <div id="serviceTableDiv">
                                            <input type="hidden" id="serviceVarCodeStr" value="">
                                            <input type="hidden" id="takenServiceVarCount" name="takenServiceVarCount" value="">
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-info btn-xs" onclick="setShowServiceModal()" ><i class="fa fa-plus"></i> Select Service</button>


                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group" >
                                        <label>From Date</label>
                                        <div class="form-group" >
                                            <input type="text" class="form-control dateInput" name="fromDate" id="fromDate">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group" >
                                        <label>To Date</label>
                                        <div class="form-group" >
                                            <input type="text" class="form-control dateInput" name="toDate" id="toDate">
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <input class="btn btn-block btn-success" type="submit" value="Search">
            </form>
            <br>
            <div class="panel panel-default" style="padding:10px"> 

                <div class="row" >
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="text-center">
                            <h4><b>Search Result</b></h4>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover custom-table" id="dataTable1">
                                <thead>
                                    <tr class="bg-primary">
                                         <th>SL</th>
                                        <th>Name</th>
                                        <th>Individual Code</th>
                                        <th>Address</th>
                                        <th>Mobile Number</th>

                                        <?php
                                        if ($searchFlag == '2') {
                                            ?>
                                            <th>Taken Service</th>
                                            <th>Service Date</th>
                                            <?php
                                        }
                                        ?>

                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <?php
                                        if ($searchFlag == '2') {
                                            ?>
                                            <th> </th>
                                            <th></th>
                                            <?php
                                        }
                                        ?>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($companies as $company) {
                                        echo "<tr>";
                                        echo "<td class='td-center'>$count</td>";
                                        echo "<td>$company[title]</td>";
                                        echo "<td>$company[company_code]</td>";
                                        echo "<td>$company[address]</td>";
                                        echo "<td>$company[company_mobile]</td>";
                                        if ($searchFlag == '2') {
                                            echo "<td>$company[service_variant_name]</td>";
                                            echo "<td>$company[final_date]</td>";
                                        }
                                        echo "<td class='td-center'>";
                                        if ($company['is_active'] == 1) {
                                            echo "<span class='text-success'>Active</span>";
                                        } else {
                                            echo "<span class='text-danger'>Inactive</span>";
                                        }
                                        echo "</td>";
                                        echo "<td class='td-center'>";
                                        ?> 
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu pull-right">
                                            <li> <a href="admin/Crm/addCallLogShow?customerId=<?php echo $company['company_code'] ?>&callerType=customer" target="_blank">Make Call</a></li>
                                        </ul>
                                    </div>
                                    <?php
                                    echo"</td>";
                                    $count++;
                                    echo "</tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<button type="button" class="btn btn-default btn-sm waves-effect hidden" data-toggle="modal" data-target="#serviceModal" id="serviceModalShowBtn"></button>
<div class="modal fade" id="serviceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Service</h4>
            </div>
            <div class="modal-body">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <?php
                    $flag = 1;
                    $serviceCount = 1;
                    foreach ($distinctServices as $distinctService) {
                        ?>
                        <div class="panel panel1 panel-default">
                            <div class="panel-heading custom-panel-heading" role="tab" id="headingOne">
                                <p class="panel-title custom-panel-title1 p-t-0 p-b-0">
                                    <a role="button" data-toggle="collapse" data-parent="#" href="#generalCollapseOne<?php echo $distinctService['service'] ?>" aria-expanded="true" aria-controls="generalCollapseOne<?php echo $distinctService['service'] ?>">
                                        <i class="fa fa-tags"></i> <?php echo $distinctService['service_name'] ?>
                                    </a>
                                </p>
                            </div>
                            <div id="generalCollapseOne<?php echo $distinctService['service'] ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <table class="table table-striped custom-table">
                                        <?php
                                        $serviceVarSerial = 1;
                                        foreach ($serviceVariants as $serviceVariant) {
                                            if ($serviceVariant['service'] == $distinctService['service']) {
                                                $flag = 0;
                                                echo "<tr>";
                                                echo "<td>$serviceVarSerial</td>";
                                                echo "<td class='td-left' style='width:80%'>" . $serviceVariant['service_variant_name'] . "</td>";
                                                echo "<td class='td-right' style='width:10%'>BDT " . $serviceVariant['unit_price'] . "</td>";
                                                echo "<td class='td-left' style='width:5%'>" . $serviceVariant['unit_name'] . "</td>";
                                                echo "<td class='td-left'>";
                                                echo "<input type='checkbox' name='serviceVarCheckBox$serviceCount' id='serviceVarCheckBox$serviceCount'>";
                                                echo "</td>";
                                                echo "<input type='hidden' id='serviceVariantCode$serviceCount' value='$serviceVariant[variant_code]'>";
                                                echo "<input type='hidden' id='serviceVariantName$serviceCount' value='$serviceVariant[service_variant_name]'>";
                                                echo "<input type='hidden' id='serviceVariantUnitName$serviceCount' value='$serviceVariant[unit_name]'>";
                                                echo "<input type='hidden' id='serviceVariantUnitPrice$serviceCount' value='$serviceVariant[unit_price]'>";
                                                $serviceVarSerial++;
                                                $serviceCount++;
                                                echo "</tr>";
                                            }
                                        }
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <input type="hidden" id="serviceVariantCount" value="<?php echo $serviceCount ?>" >
                </div>
                <?php
                if ($flag) {
                    ?>
                    <span class="text-danger">No service has been add to Home Service</span>
                    <?php
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" id="serviceModalSelectBtn" onclick="setAddService()">SELECT</button>
                <button type="button" class="btn btn-link waves-effect" id="serviceModalCloseBtn" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
@push('scripts')
<script>
$(document).ready(function () {
    $('#datatable, .dataTable').DataTable({
        columnDefs: [
            {
                defaultContent: "-",
                targets: "_all"
            }
        ]
    });
});
</script>

<script>

    // $(document).ready(function () {
    //     $('#dataTable1').DataTable({
    //         initComplete: function () {
    //             this.api().columns().every(function () {
    //                 var column = this;
    //                 var select = $('<select><option value=""></option></select>')
    //                         .appendTo($(column.footer()).empty())
    //                         .on('change', function () {
    //                             var val = $.fn.dataTable.util.escapeRegex(
    //                                     $(this).val()
    //                                     );

    //                             column
    //                                     .search(val ? '^' + val + '$' : '', true, false)
    //                                     .draw();
    //                         });

    //                 column.data().unique().sort().each(function (d, j) {
    //                     select.append('<option value="' + d + '">' + d + '</option>')
    //                 });
    //             });
    //         },
    //         dom: 'Bfrtip',
    //         buttons: [
    //             'copy', 'csv', 'excel', 'pdf'
    //         ]
    //     });
    // });

    $(function () {
        var availableTags = <?php echo $customerName ?>;
        $("#customerName").autocomplete({
            source: availableTags
        });

        var availableTags = <?php echo $customerMobile ?>;
        $("#customerMobile").autocomplete({
            source: availableTags
        });

        var availableTags = <?php echo $customerId ?>;
        $("#customerId").autocomplete({
            source: availableTags
        });
    });

    function setShowServiceModal() {
        var serviceVariantCount = $("#serviceVariantCount").val();
        var serviceVarCodeStr = $("#serviceVarCodeStr").val();
        $('#serviceModalShowBtn').click();
        for (var i = 1; i < serviceVariantCount; i++) {
            $('#serviceVarCheckBox' + i).prop('checked', false);
        }
        if (typeof serviceVarCodeStr !== 'undefined') {
            if (serviceVarCodeStr) {
                var serviceVarCodeArr = serviceVarCodeStr.split(',');
                for (var i = 1; i < serviceVariantCount; i++) {
                    if (jQuery.inArray($("#serviceVariantCode" + i).val(), serviceVarCodeArr) !== -1) {
                        $('#serviceVarCheckBox' + i).prop('checked', true);
                    } else {
                        $('#serviceVarCheckBox' + i).prop('checked', false);
                    }
                }
            }
        }
    }

    function setAddService() {
        var serviceVariantCount = $("#serviceVariantCount").val();
        var serviceVariantCode;
        var serviceVariantName;
        var serviceTableStr = "";
        var serviceVarCodeArr = new Array();
        var takenServiceVarCount = 1;

        var takenServieVarCountFinal = $("#takenServiceVarCount").val();
        if (typeof takenServieVarCountFinal === 'undefined') {
            takenServieVarCountFinal = 0;
        }

        var i = 1;
        for (var x = 1; x < serviceVariantCount; x++) {
            if ($("#serviceVarCheckBox" + x).is(':checked')) {
                serviceVariantCode = $("#serviceVariantCode" + x).val();
                serviceVariantName = $("#serviceVariantName" + x).val();
                serviceTableStr += '<tr id="serviceTakenTd' + i + '">\n\
                                        <td class="td-left">' + serviceVariantName + '</td>\n\
                                        <td class="td-center"><i class="fa fa-remove pointer text-danger" onclick="removeService(' + i + ')"></i>\n\
                                        <input type="hidden" id="takenServiceVarCode' + i + '" name="takenServiceVarCode' + i + '" value="' + serviceVariantCode + '">\n\
                                    </tr>';
                serviceVarCodeArr.push(serviceVariantCode);
                takenServiceVarCount++;
                i++;
            }
        }
        $('#serviceTableDiv').remove();
        if (serviceTableStr !== "") {
            var newRow = $(document.createElement('div')).attr("id", 'serviceTableDiv');
            var serviceTableDiv = '<table class="table table-bordered custom-table">\n\
                                <tr class="bg-info">\n\
                                    <th colspan="5"><b>Service</b></th>\n\
                                </tr>\n\
                                <tr>\n\
                                    <th width="50%"><b>Service Name</b></th>\n\
                                    <th width="10%"><b>Action</b></th>\n\
                                </tr>\n\
                                ' + serviceTableStr + '\n\
                                <input type="hidden" id="serviceVarCodeStr' + '" value="' + serviceVarCodeArr.join() + '">\n\
                                <input type="hidden" id="takenServiceVarCount' + '" name="takenServiceVarCount' + '" value="' + takenServiceVarCount + '">\n\
                            </table>';
            newRow.after().html(serviceTableDiv);
            newRow.appendTo("#vehicleServiceDiv");
        }
        $('#serviceModalCloseBtn').click();

    }

    function removeService(serviceSerial) {
        $('#serviceTakenTd' + serviceSerial).remove();
        var serviceVarCodeArr = new Array();
        var takenServiceVarCode;
        var takenServiceVarCount = $('#takenServiceVarCount').val();
        for (var i = 1; i < takenServiceVarCount; i++) {
            takenServiceVarCode = $('#takenServiceVarCode' + i).val();

            if (typeof takenServiceVarCode !== 'undefined') {
                serviceVarCodeArr.push(takenServiceVarCode);
            }
        }

        if (serviceVarCodeArr.length !== 0) {
            $('#serviceVarCodeStr').val(serviceVarCodeArr.join());
        } else {
            $('#serviceTableDiv').remove();
        }

    }
</script>
@endpush
