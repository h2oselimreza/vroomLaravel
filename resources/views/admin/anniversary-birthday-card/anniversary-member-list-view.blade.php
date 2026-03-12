@extends('layouts.app')
@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Member Anniversary & Birthday Card</h1>
    <ul class="breadcrumb">
        <li><a href="#"> Home</a></li>
        <li><a href="#">/ Anniversary</a></li>
        <li><a href="#">/ Member Anniversary Card</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="text-right">
                    <h4><b>Selected Member: <span id="selectedMember">0</span></b></h4>
                </div>

                <div class="table-responsive">
                    <form action="{{ url('admin/AnniversaryCard/showMemberAnniversaryCard') }}" method="POST">
                        @csrf

                        <table class="table table-bordered table-hover custom-table" id="datatable">
                            <thead>
                                <tr class="bg-primary">
                                    <th>SL</th>
                                    <th>Member ID</th>
                                    <th>Member Name</th>
                                    <th>Block</th>
                                    <th>Road</th>
                                    <th>Member Type</th>
                                    <th>Occupation</th>
                                    <th>Blood Group</th>
                                    <th>Contact No</th>
                                    <th>Anniversary</th>
                                    <th>DOB</th>
                                    <th class="no-sort" style="width:50px">
                                        <input type="checkbox" id="selectall" onClick="selectAll(this)" />
                                    </th>
                                </tr>
                            </thead>

                            <tfoot>
                                <tr>
                                    <th>SL</th>
                                    <th>Member ID</th>
                                    <th>Member Name</th>
                                    <th>Block</th>
                                    <th>Road</th>
                                    <th>Member Type</th>
                                    <th>Occupation</th>
                                    <th>Blood Group</th>
                                    <th>Contact No</th>
                                    <th>Anniversary</th>
                                    <th>DOB</th>
                                </tr>
                            </tfoot>

                            <tbody>
                                @php $count = 1; @endphp

                                @foreach ($members as $member)
                                    <tr>
                                        <td class="td-center">{{ $count }}</td>
                                        <td>{{ $member->member_id }}</td>
                                        <td>{{ $member->member_name }}</td>
                                        <td>{{ $member->block_name }}</td>
                                        <td>{{ $member->road_name }}</td>
                                        <td>{{ $member->member_type_name }}</td>
                                        <td>{{ $member->member_occupation_name }}</td>
                                        <td>{{ $member->blood_group }}</td>
                                        <td>{{ $member->primary_mobile }}</td>
                                        <td>{{ $member->anniversary }}</td>
                                        <td>{{ $member->dob }}</td>

                                        <td class="td-center">
                                            <input type="checkbox"
                                                   name="memberIdArr[]"
                                                   id="memberIdCheckbox{{ $count }}"
                                                   onclick="showCheckedMemberCount()"
                                                   value="{{ $member->id }}" />
                                        </td>
                                    </tr>

                                    @php $count++; @endphp
                                @endforeach

                            </tbody>
                        </table>

                        <div style="text-align:right;">
                            <input type="hidden" name="cardType" value="{{ $cardType }}">
                            <input type="submit" class="btn btn-success" value="Show Card">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    table = $('#datatable').DataTable({
        // processing: true,
        // serverSide: true,
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;

                // ❌ Skip Action column (last column index = 7)
                if (column.index() === 10) return;

                var select = $('<select class="form-control" style="width:100%"><option value="">All</option></select>')
                    .appendTo($(column.footer()).empty())
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());

                        column
                            .search(val ? '^' + val + '$' : '', true, false)
                            .draw();
                    });

                column.data().unique().sort().each(function (d) {

                    // ✅ Convert HTML → plain text
                    var text = $('<div>').html(d).text().trim();

                    if (text) {
                        select.append('<option value="' + text + '">' + text + '</option>');
                    }
                });
            });
        }
    });

    function selectAll(source) {
        checkboxes = document.getElementsByName('memberIdArr[]');
        for(var i in checkboxes)
          checkboxes[i].checked = source.checked;

        showCheckedMemberCount();
    }
    function showCheckedMemberCount() {
        checkboxes = document.getElementsByName('memberIdArr[]');
        var memberCheckBoxIdArr = new Array();
        var j  =  1;
        for (var i in checkboxes){
            if($("#memberIdCheckbox"+j).is(':checked')) {
                memberCheckBoxIdArr.push(checkboxes[i].id);
            }
            j = j+ 1;
        }
        $("#selectedMember").html(memberCheckBoxIdArr.length);
    }
</script>
@endpush
