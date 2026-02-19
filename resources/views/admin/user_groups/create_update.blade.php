@extends('layouts.app')

@section('content')

<div class="header dashboard_from">
    <h1 class="page-title">
        {{ isset($userGroup) && $userGroup->exists ? 'Edit Module Group' : 'Add Module Group' }}
    </h1>
</div>

<div class="main-content">
    <div class="card from_card">
        <div class="card-header">
            {{ isset($userGroup) && $userGroup->exists ? 'Update Module Group' : 'Create Module Group' }}
        </div>

        <div class="card-body">

            <form action="{{ isset($userGroup) && $userGroup->exists 
                        ? route('admin.user-groups.update', $userGroup->id) 
                        : route('admin.user-groups.store') }}"
                method="POST">

                @csrf

                @if(isset($userGroup) && $userGroup->exists)
                    @method('PUT')
                @endif

                {{-- Module Group Name --}}
                <div class="row"> 
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Module Group Name :
                        </label>
                        <input type="text"
                               name="module_group_name"
                               class="form-control"
                               placeholder="Module group name"
                               value="{{ old('module_group_name', $userGroup->group_name ?? '') }}"
                               required>
                    </div>
                </div>

                {{-- Module Table --}}
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered custom-table">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th>SL</th>
                                    <th>Module Group</th>
                                    <th>Module</th>
                                    <th>Select</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                    $serial = 1;
                                    $textBoxSerial = 1;

                                    // Get selected modules for update
                                    $selectedModules = old('moduleList') 
                                        ?? (isset($userGroup->modules) ? explode(',', $userGroup->modules) : []);
                                @endphp

                                @foreach($moduleGroups as $moduleGroup)

                                    @php
                                        $groupModules = $modules->where('module_group', $moduleGroup->module_group_code);
                                        $rowspanValue = $groupModules->count();
                                        $loopFlag = true;
                                    @endphp

                                    @foreach($groupModules as $module)
                                        <tr>
                                            @if($loopFlag)
                                                <td class="td-center" rowspan="{{ $rowspanValue }}">
                                                    {{ $serial }}
                                                </td>

                                                <td class="td-center" rowspan="{{ $rowspanValue }}">
                                                    {{ $moduleGroup->module_group_name }}
                                                </td>

                                                @php $loopFlag = false; @endphp
                                            @endif

                                            <td>
                                                {{ $module->modules_name }}
                                            </td>

                                            <td class="td-center">
                                                <input type="checkbox"
                                                       id="md_checkbox_{{ $textBoxSerial }}"
                                                       class="chk-col-light-blue custom-textbox"
                                                       name="moduleList[]"
                                                       value="{{ $module->id }}"
                                                       {{ in_array($module->id, $selectedModules) ? 'checked' : '' }}>
                                            </td>
                                        </tr>

                                        @php $textBoxSerial++; @endphp
                                    @endforeach

                                    @php $serial++; @endphp
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="ps-0 card-footer bg-white d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($userGroup) && $userGroup->exists ? 'Update' : 'Save' }}
                    </button> 

                    <a href="{{ route('admin.modules.index') }}"
                       class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
    $(document).ready(function () {
        // You can add JS here if needed
    });
</script>
@endpush
