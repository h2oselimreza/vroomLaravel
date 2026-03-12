@extends('layouts.app')
@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Member Bulk SMS</h1>
    <ul class="breadcrumb">
        <li><a href="#"> Home</a></li>
        <li><a href="#">/ SMS</a></li>
        <li><a href="#">/ Member Bulk SMS</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="text-right"><h4><b>Selected Member: <span id="selectedMember">0</span></b></h4></div>
                <div class="table-responsive">
                    <form action="{{ route('admin.show-member-sms-panel-from-list') }}" method="POST">
                        @csrf
                        <table class="table table-bordered table-hover custom-table" id="dataTableCheckBoxWithDownload">
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
                                    <th class="no-sort" style="width:50px"><input type="checkbox" id="selectAll" /></th>
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
                                </tr>
                            </tfoot>
                            <tbody>
                                @php $count = 1; @endphp
                                @foreach($members as $member)
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
                                        <td class="td-center">
                                            <input type="checkbox" class="rowCheckbox" value="{{ $member->id }}" />
                                        </td>
                                    </tr>
                                    @php $count++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                        <div style="text-align:right;padding-right:25px">
                            <input type="submit" class="btn btn-success save_button" value="Show Custom SMS Panel">
                        </div>
                        <input type="hidden" name="member_ids" id="member_ids">
                    </form>
                </div> 
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script language="JavaScript">
   $(document).ready(function () {
        var table = $('#dataTableCheckBoxWithDownload').DataTable({
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;

                    // ❌ Skip Action column (last column index = 9)
                    if (column.index() === 9) return;

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

        var selected = [];

        // Restore checked state on draw (pagination/search)
        table.on('draw', function () {
            $('.rowCheckbox').each(function () {
                $(this).prop('checked', selected.includes($(this).val()));
            });
        });

        // Handle individual row checkbox click
        $(document).on('change', '.rowCheckbox', function () {
            var value = $(this).val();
            if (this.checked) {
                if (!selected.includes(value)) {
                    selected.push(value);
                }
            } else {
                selected = selected.filter(id => id !== value);
            }

            // Store in hidden field
            $("#selectedMember").html(selected.length);
            $('#member_ids').val(selected.join(','));
        });

        // Handle "Select All" checkbox
        $('#selectAll').on('change', function () {
            var checked = this.checked;
            $('.rowCheckbox').each(function () {
                $(this).prop('checked', checked).trigger('change');
            });
        });
    });
</script>
@endpush
