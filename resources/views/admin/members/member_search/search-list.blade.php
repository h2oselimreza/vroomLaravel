
@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">Search Member List</h1>
    <ul class="breadcrumb">
        <li><a href="#"> Home</a></li>
        <li><a href="#">/ Master Data</a></li>
        <li><a href="#">/ Member</a></li>
    </ul>
</div>

<div class="container">
    <div class="card shadow">
        <div class="card-body">
            <form target="_blank" action="{{ route('admin.member.search.print') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered table-hover custom-table" id="datatable">
                        <thead>
                            <tr class="bg-primary">
                                <th>SL</th>
                                <th>Member ID</th>
                                <th>Donar ID</th>
                                <th>Member Name</th>
                                <th>Member Type</th>
                                <th>Contact No</th>
                                <th>Block</th>
                                <th>Road</th>
                                <th>Plot</th>
                                <th>Flat</th>

                                <th>Gender</th>
                                <th>DOB</th>
                                <th>Anniversary</th>

                                <th>Status</th>
                                <th>Action</th>
                                <th class="no-sort" style="width:50px"><input type="checkbox" id="selectAll" /></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $count = 1; @endphp
                            @foreach($members as $member)
                                <tr>
                                    <td class="td-center">{{ $count++ }}</td>
                                    <td>{{ $member->member_id }}</td>
                                    <td>{{ $member->donar_member_id }}</td>
                                    <td>{{ $member->member_name }}</td>
                                    <td>{{ $member->member_type_name }}</td>
                                    <td>{{ $member->primary_mobile }}</td>
                                    <td>{{ $member->block_name }}</td>
                                    <td>{{ $member->road_name }}</td>
                                    <td>{{ $member->society_plot }}</td>
                                    <td>{{ $member->society_flat }}</td>
                                    <td>{{ ucfirst($member->gender) }}</td>
                                    <td>{{ $member->dob }}</td>
                                    <td>{{ $member->anniversary }}</td>
                                    <td class="td-center">
                                        @if($member->is_active == 1)
                                            Active
                                        @elseif($member->is_active == 0)
                                            Inactive
                                        @endif
                                    </td>
                            
                                <td class='td-center'>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu pull-right">
                                            <li><a href="">Update</a></li>
                                            <li role="separator" class="divider"></li>
                                            
                                            <?php
                                                if($member->is_active == 1){
                                            ?>
                                                        <li><a href="" >Inactive</a></li>
                                            <?php
                                                }elseif($member->is_active == 0){
                                            ?>
                                                    <li><a href="" >Active</a></li>
                                            <?php
                                                }
                                            ?>
                                            
                                            
                                        </ul>
                                    </div>
                                </td>
                                <td class='td-center'>
                                    <input type='checkbox' class="rowCheckbox" value="<?php echo $member->id ?>" />
                                </td>
                                </tr>
                            <?php
                                $count++;
                            ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="">
                    <div class="form-inline">
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <label class="form-label"> Print Type </label>
                                <select class="form-control" name="printType">
                                    <option value="profile">Member Profile</option>
                                    <option value="voterlist">Voter List</option>
                                    <option value="english">English</option>
                                    <option value="bangla">Bangla</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"> Order By </label>
                                <select class="form-control" name="orderBy">
                                    <option value="member_id">Life Member Id</option>
                                    <option value="donar_member_id">Donor Member Id</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" data-bs-toggle="tooltip" data-bs-placement="top" title="This will work only when Print Type is Voter List or English or Bangla!"> Print With </label>
                                <select class="form-control" name="printId">
                                    <option value="both">Both Member Id</option>
                                    <option value="member_id">Only Life Member Id</option>
                                    <option value="donar_member_id">Only Donor Member Id</option>
                                </select>
                            </div>
                            <div class="col-md-3 my-auto">
                                <input type="hidden" name="member_ids" id="member_ids">
                                <input type="submit" class="btn btn-success save_button" style="margin-top:29px" value="Print">
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script language="JavaScript">

   var table = $('#datatable').DataTable();

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
            $('#member_ids').val(selected.join(','));
        });

        // Handle "Select All" checkbox
        $('#selectAll').on('change', function () {
            var checked = this.checked;
            $('.rowCheckbox').each(function () {
                $(this).prop('checked', checked).trigger('change');
            });
        });

</script>
@endpush
