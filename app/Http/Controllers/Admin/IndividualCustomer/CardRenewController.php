<?php

namespace App\Http\Controllers\Admin\IndividualCustomer;

use App\Http\Controllers\Controller;
use App\Repositories\IndividualCustomerRepository;
use Illuminate\Http\Request;

class CardRenewController extends Controller
{
    public function index(IndividualCustomerRepository $individualCustomerRepository) {

        $companies = $individualCustomerRepository->getCardAssignedIndividualAcc(config('constants.INDIVIDUAL_CUST'));
        return view('admin.individual-customer.card-renew-list',compact('companies'));
    }
}
