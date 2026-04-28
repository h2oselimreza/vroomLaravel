<?php

namespace App\Http\Controllers\Admin\IndividualCustomer;

use App\Http\Controllers\Controller;
use App\Repositories\CommonRepository;
use App\Repositories\IndividualCustomerRepository;
use App\Services\Client\GenerateMonthlyToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IndividualCustomerController extends Controller
{
    public function index(Request $request, CommonRepository $commonRepository) {
        //dd($request->all());
        $statusDropDown = $request->statusDropDown;
        $isActiveFlag = 1;
        if ($statusDropDown) {
            $isActiveFlag = $statusDropDown;
        }
        $companies = $commonRepository->getIndividualAccList($isActiveFlag, config('constants.INDIVIDUAL_CUST'));
        return view('admin.individual-customer.index',compact('companies','isActiveFlag'));
    }

    public function create(){
        return view('admin.individual-customer.create-update');
    }

    public function store(Request $request, GenerateMonthlyToken $generateMonthlyToken)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'mobile'    => 'required|string|max:20',
            'email'     => 'nullable|email|max:255',
            'address'   => 'nullable|string|max:500',
        ], [
            'full_name.required' => 'Individual Account Name is required.',
            'mobile.required'    => 'Mobile number is required.',
            'email.email'        => 'Please enter a valid email address.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.individual.individual-account.create')
                ->withErrors($validator)
                ->withInput();
        }

        $fullName = trim($request->input('full_name'));
        $mobile   = trim($request->input('mobile'));
        $email    = $request->filled('email') ? trim($request->input('email')) : null;
        $address  = $request->filled('address') ? trim($request->input('address')) : null;

        $dateTime = Carbon::now();

        $userId = config('constants.INDV_EMP_CODE')
            . $generateMonthlyToken->get_month_token(config('constants.INDV_EMP_CODE'));

        $companyCode = config('constants.INDV_COMPANY_CODE')
            . $generateMonthlyToken->get_month_token(config('constants.INDV_COMPANY_CODE'));

        $chars = "123456789";
        $plainPassword = app()->environment('production')
            ? substr(str_shuffle($chars), 0, 6)
            : '1234';

        try {
            DB::beginTransaction();

            $exists = DB::table('users')
                ->where('username', $mobile)
                ->exists();

            if ($exists) {
                DB::rollBack();

                return redirect()
                    ->route('admin.individual.individual-account.create')
                    ->with('error', 'Mobile number already exists')
                    ->withInput();
            }

            $authUserId = Auth::user()->user_id;

            $usersData = [
                'user_id'        => $userId,
                'username'       => $mobile,
                'password'       => md5($plainPassword),
                'user_group'     => config('constants.INDV_DEFAULT_GRP'),
                'email'          => $email,
                'user_type_code' => config('constants.USER_TYPE_INDV_EMP'),
                'panel_type'     => config('constants.CLIENT'),
                'full_name'      => $fullName,
                'contact_no'     => $mobile,
                'is_reset'       => 0,
                'created_by'     => $authUserId,
                'created_type'   => config('constants.P_ADMIN'),
                'created_dt_tm'  => $dateTime,
                'updated_by'     => $authUserId,
                'updated_type'   => config('constants.P_ADMIN'),
                'updated_dt_tm'  => $dateTime,
                'is_active'      => 1,
            ];

            DB::table('users')->insert($usersData);

            $companyArr = [
                'company_code'            => $companyCode,
                'package'                 => config('constants.INDV_PACKAGE'),
                'company_type'            => config('constants.INDIVIDUAL_CUST'),
                'title'                   => $fullName,
                'address'                 => $address,
                'company_mobile'          => $mobile,
                'company_email'           => $email,
                'primary_contact_mobile'  => $mobile,
                'primary_contact_person'  => $fullName,
                'primary_contact_email'   => $email,
                'status'                  => 1,
                'created_by'              => $authUserId,
                'created_dt_tm'           => $dateTime,
                'updated_by'              => $authUserId,
                'updated_dt_tm'           => $dateTime,
            ];

            DB::table('corporate_companies')->insert($companyArr);

            $customerEmp = [
                'company'                    => $companyCode,
                'employee_id'                => $userId,
                'employee_name'              => $fullName,
                'primary_mobile'             => $mobile,
                'employee_permanent_address' => $address,
                'customer_type'              => config('constants.INDIVIDUAL_CUST'),
                'emp_type'                   => 'system_manager',
                'created_by'                 => $authUserId,
                'created_type'               => config('constants.P_ADMIN'),
                'created_dt_tm'              => $dateTime,
                'updated_by'                 => $authUserId,
                'updated_type'               => config('constants.P_ADMIN'),
                'updated_dt_tm'              => $dateTime,
                'system_user'                => 1,
            ];

            DB::table('customer_employee')->insert($customerEmp);

            DB::commit();

            // $this->smsMailSend($usersData, $plainPassword);

            return redirect()
                ->route('admin.individual.individual-account.index')
                ->with('success', 'User created successfully');

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->route('admin.individual.individual-account.create')
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }


    public function changeCompanyStatus(Request $request, IndividualCustomerRepository $individualCustomerRepository)
    {
        $companyCode = $request->companyCode;
        $statusFlag  = $request->statusFlag;

        $result = $individualCustomerRepository->changeCompanyStatus($companyCode, $statusFlag);

        return response()->json($result);
    }
}
