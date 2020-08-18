<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\BankAccount;
use App\Models\Payment;
use App\Models\Projects;
use App\Models\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BankAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/accounts/index", 'name' => "Accounts"], ['name' => "Add Accounts"]
        ];

        $banks = BankAccount::all();
        $clients = Role::whereRoleSlug('client')
            ->firstOrFail()
            ->users;

        $projects = Projects::where('project_status', '1')->get();

        $adminBanks = BankAccount::where('bank_user_id', '=', null)->get();

        $roles = Role::whereIn('role_slug', ['administrator', 'accountant'])
            ->pluck('role_id')
            ->toArray();

        $cash = 0;

        // Optimize page loading time
        $payments = Payment::wherePaymentBy('cash')
            ->whereHas('activity.activityBy', function ($query) use ($roles) {
                $query->whereIn('role_id', $roles);
            })->get();

        // Do not use in_array for $roles and role_id
        foreach ($payments as $index => $payment) {
            if (in_array(strtolower($payment->payment_purpose), ['employee_transfer', 'employee_refund', 'vendor_payment', 'vendor_refund', 'loan_payment', 'salary', 'office_deposit'])) {
                $cash -= $payment->payment_amount;
            } else {
                $cash += $payment->payment_amount;
            }
        }

        $totalCash = Payment::sum('payment_amount');

        $transferToEmployee = Payment::where('payment_purpose', 'employee_transfer')->sum('payment_amount');
        $refundFromEmployee = Payment::where('payment_purpose', 'employee_refund')->sum('payment_amount');
        $withdrawFromBank = Payment::where('payment_purpose', 'office_withdraw')->sum('payment_amount');
        $depositFromClient = Payment::where('payment_purpose', 'project_money')->sum('payment_amount');
        $salary = Payment::where('payment_purpose', 'salary')->sum('payment_amount');


        return view('front-end.accounts.index')
            ->with([
                'adminBanks' => $adminBanks,
                'banks' => $banks,
                'clients' => $clients,
                'projects' => $projects,
                'cash' => $cash,
                'breadcrumbs' => $breadcrumbs,
                'totalCash' => $totalCash,
                'transferToEmployee' => $transferToEmployee,
                'refundFromEmployee' => $refundFromEmployee,
                'withdrawFromBank' => $withdrawFromBank,
                'depositFromClient' => $depositFromClient,
                'salary' => $salary,
            ]);
    }

    public function storeAccount(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => ['nullable', 'numeric'],
            'name' => ['required', 'string'],
            'number' => ['required', 'string'],
            'bank' => ['required', 'string'],
            'branch' => ['required', 'string'],
            'balance' => ['required', 'numeric'],
            'accountFor' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return Helper::redirectBackWithValidationError($validator);
        }

        $bank = new BankAccount();

        $bank->bank_user_id = ($request->post('user_id')) ? $request->post('user_id') : null;
        $bank->bank_account_name = $request->post('name');
        $bank->bank_account_no = $request->post('number');
        $bank->bank_name = $request->post('bank');
        $bank->bank_branch = $request->post('branch');
        $bank->bank_balance = $request->post('balance');

        if (!$bank->save()) {
            return Helper::redirectBackWithNotification();
        }
        Helper::addActivity('bank', $bank->bank_id, 'Bank Account Added!');

        return Helper::redirectBackWithNotification('success', 'Bank Account Successfully Added!');
    }

    public function getUsers(Request $request)
    {
        $users = Role::whereRoleSlug($request->post('type'))->firstOrFail()->users;

        return view('front-end.accounts.ajax-users')
            ->with([
                'users' => $users
            ]);
    }

    public function transferToEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => ['required', 'string'],
            'bank_id' => ['nullable', 'numeric'],
            'project_id' => ['required', 'numeric'],
            'type' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'date'],
        ]);
        if ($validator->fails()) {
            return Helper::redirectBackWithValidationError($validator);
        }


        if (strtolower($request->post('type')) !== 'cash' && $request->post('bank_id') === null) {
            return Helper::redirectBackWithNotification('error', 'Bank Account must be selected!');
        }
        $employee_id = $request->post('employee_id');
        $employee_bank_id = null;

        if (strpos($request->post('employee_id'), '@') !== false) {
            $employee_id = Str::before($request->post('employee_id'), '@');
            $employee_bank_id = Str::after($request->post('employee_id'), '@');
        }


        $paymentData = [
            'type' => null,
            'to_user' => $employee_id,
            'from_user' => Auth::id(),
            'to_bank_account' => $employee_bank_id,
            'from_bank_account' => (strtolower($request->post('type')) !== 'cash') ? $request->post('bank_id') : null,
            'amount' => $request->post('amount'),
            'project' => $request->post('project_id'),
            'purpose' => 'employee_transfer',
            'by' => strtolower($request->post('type')),
            'date' => $request->post('date'),
            'image' => null,
            'note' => $request->post('note')
        ];

        if (!Helper::createNewPayment($paymentData)) {
            return Helper::redirectBackWithNotification();
        }
        if (strtolower($request->post('type')) === 'bank' || $request->post('payment_by') == 'check') {
            $officeBank = BankAccount::findOrFail($request->post('bank_id'));
            $officeBank->bank_balance = $officeBank->bank_balance - (float)$request->post('amount');
            $officeBank->save();

            $employeeBank = BankAccount::findOrFail($employee_bank_id);
            $employeeBank->bank_balance = $employeeBank->bank_balance + (float)$request->post('amount');
            $employeeBank->save();
        }

        return Helper::redirectBackWithNotification('success', 'Transfer successfully made!');
    }

    public function getManagers(Request $request)
    {

        $roles = Role::whereIn('role_slug', ['manager'])
            ->pluck('role_id')
            ->toArray();

        $users = Projects::findOrFail($request->post('project_id'))->employees()
            ->whereIn('role_id', $roles)
            ->get();

        return view('front-end.accounts.ajax-employees')
            ->with([
                'users' => $users
            ]);
    }

    public function transferToOffice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => ['required', 'string'],
            'bank_id' => ['nullable', 'numeric'],
            'project_id' => ['required', 'numeric'],
            'type' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'date'],
        ]);
        if ($validator->fails()) {
            return Helper::redirectBackWithValidationError($validator);
        }
        if (strtolower($request->post('type')) !== 'bank' && $request->post('bank_id') === null) {
            return Helper::redirectBackWithNotification('error', 'Bank Account must be selected!');
        }
        $employee_id = $request->post('employee_id');
        $employee_bank_id = null;

        if (strpos($request->post('employee_id'), '@') !== false) {
            $employee_id = Str::before($request->post('employee_id'), '@');
            $employee_bank_id = Str::after($request->post('employee_id'), '@');
        } else {
            $employee_bank_id = $this->createAutoGeneratedAccount($employee_id)->bank_id;
        }
        $paymentData = [
            'type' => null,
            'to_user' => $employee_id,
            'from_user' => Auth::id(),
            'to_bank_account' => $employee_bank_id,
            'from_bank_account' => (strtolower($request->post('type')) === 'bank') ? $request->post('bank_id') : null,
            'amount' => $request->post('amount') - ($request->post('amount') * 2),
            'project' => $request->post('project_id'),
            'purpose' => 'employee_refund',
            'by' => strtolower($request->post('type')),
            'date' => $request->post('date'),
            'image' => null,
            'note' => $request->post('note')
        ];

        if (!Helper::createNewPayment($paymentData)) {
            return Helper::redirectBackWithNotification();
        }
        if (strtolower($request->post('type')) === 'bank' || $request->post('payment_by') == 'check') {
            $officeBank = BankAccount::findOrFail($request->post('bank_id'));
            $officeBank->bank_balance = $officeBank->bank_balance + (float)$request->post('amount');
            $officeBank->save();
        }
        $employeeBank = BankAccount::findOrFail($employee_bank_id);
        $employeeBank->bank_balance = $employeeBank->bank_balance - (float)$request->post('amount');
        $employeeBank->save();

        return Helper::redirectBackWithNotification('success', 'Money successfully refunded!');
    }

    protected function createAutoGeneratedAccount(int $id)
    {
        $employee = User::findOrFail($id);
        $bank = new BankAccount();

        $bank->bank_account_name = 'Auto Generated Bank Account!';
        $bank->bank_user_id = $employee->id;
        $bank->save();

        return $bank;
    }

    public function withdrawFromBank(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_id' => ['nullable', 'numeric'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'date'],
        ]);
        if ($validator->fails()) {
            return Helper::redirectBackWithValidationError($validator);
        }
        $paymentData = [
            'type' => null,
            'to_user' => null,
            'from_user' => null,
            'to_bank_account' => null,
            'from_bank_account' => $request->post('bank_id'),
            'amount' => $request->post('amount'),
            'project' => null,
            'purpose' => 'office_withdraw',
            'by' => 'cash',
            'date' => $request->post('date'),
            'image' => null,
            'note' => $request->post('note')
        ];

        if (!Helper::createNewPayment($paymentData)) {
            return Helper::redirectBackWithNotification();
        }

        $officeBank = BankAccount::findOrFail($request->post('bank_id'));
        $officeBank->bank_balance = $officeBank->bank_balance - (float)$request->post('amount');
        $officeBank->save();
        return Helper::redirectBackWithNotification('success', 'Money successfully Withdrawn!');
    }

    public function depositToBank(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_id' => ['nullable', 'numeric'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'date'],
        ]);
        if ($validator->fails()) {
            return Helper::redirectBackWithValidationError($validator);
        }
        $paymentData = [
            'type' => null,
            'to_user' => null,
            'from_user' => null,
            'to_bank_account' => $request->post('bank_id'),
            'from_bank_account' => null,
            'amount' => $request->post('amount'),
            'project' => null,
            'purpose' => 'office_deposit',
            'by' => 'cash',
            'date' => $request->post('date'),
            'image' => null,
            'note' => $request->post('note')
        ];

        if (!Helper::createNewPayment($paymentData)) {
            return Helper::redirectBackWithNotification();
        }

        $officeBank = BankAccount::findOrFail($request->post('bank_id'));
        $officeBank->bank_balance = $officeBank->bank_balance + (float)$request->post('amount');
        $officeBank->save();
        return Helper::redirectBackWithNotification('success', 'Money successfully Deposited!');
    }

    public function rechargeFromCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'numeric'],
            'bank_id' => ['nullable', 'numeric'],
            'project_id' => ['required', 'numeric'],
            'type' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'date'],
        ]);
        if ($validator->fails()) {
            return Helper::redirectBackWithValidationError($validator);
        }
        $payment = Helper::createNewPayment([
            'type' => 'credit',
            'to_user' => null,
            'from_user' => $request->post('user_id'),
            'to_bank_account' => (strtolower($request->post('type')) === 'bank' || strtolower($request->post('type')) === 'check')
                ? $request->post('bank_id') : null,
            'from_bank_account' => null,
            'amount' => $request->post('amount'),
            'project' => $request->post('project_id'),
            'purpose' => 'project_money',
            'by' => $request->post('type'),
            'date' => $request->post('date'),
            'image' => null,
            'note' => $request->post('note')
        ]);
        if (!$payment) {
            return Helper::redirectBackWithNotification();
        }
        if (strtolower($request->post('type')) === 'bank' || $request->post('type') == 'check') {
            $offBank = BankAccount::findOrFail($request->post('bank_id'));
            $offBank->bank_balance = $offBank->bank_balance + (float)$request->post('amount');
            $offBank->save();
        }
        return Helper::redirectBackWithNotification('success', 'Client Money Successfully Received!');
    }

    public function getClientProjects(Request $request)
    {
        $projects = User::findOrFail($request->post('client_id'))->clientProjects()
            ->where('project_status', '=', '1')->get();

        return view('front-end.accounts.ajax-projects')
            ->with([
                'projects' => $projects
            ]);
    }
    public function income() {
        $role_id = Role::whereRoleSlug('accountant')->firstOrFail()->role_id;

        $payments = Payment::where('payment_type','=','credit')
            ->orderBy('payment_date', 'desc')
            ->get();

        $paymentsloan = Payment::wherePaymentType('credit')
            ->where('payment_purpose', '=', 'loan_received')
            ->orderBy('payment_date', 'desc')
            ->get();

        return view('front-end.accounts.income')
            ->with([
                'payments'  => $payments,
                'paymentsloan' => $paymentsloan
            ]);
    }

    public function expense() {
        $roles = Role::whereIn('role_slug', ['administrator', 'accountant'])
            ->pluck('role_id')
            ->toArray();

        $payments = Payment::where('payment_type','=','debit')
            ->whereHas('activity.activityBy', function ($query) use ($roles) {
                $query->whereIn('role_id', $roles);
            })->orderBy('payment_date', 'desc')
            ->get();

        $paymentsExpense = Payment::wherePaymentType('debit')
            ->where('payment_purpose', '=', 'loan_payment')
            ->orderBy('payment_date', 'desc')
            ->get();

        return view('front-end.accounts.expense')
            ->with([
                'payments'     => $payments,
                'paymentsExpense' => $paymentsExpense
            ]);
    }



    public function bankDetails($id)
    {

        if (!Auth::user()->isAdmin() && !Auth::user()->isAccountant()) {
            return Helper::redirectBackWithNotification('error', 'You are not authorised!');
        }

        if ($id == 'cash') {

            $roles = Role::whereIn('role_slug', ['administrator', 'accountant'])
                ->pluck('role_id')
                ->toArray();

            $payments = Payment::where('payment_by', '=', 'cash')
                ->whereHas('activity.activityBy', function ($query) use ($roles) {
                    $query->whereIn('role_id', $roles);
                })
                ->orderBy('payment_date', 'desc')
                ->get();

//            dd($payments);

            $paymentsloanacash = Payment::where('payment_by', '=', 'cash')
                ->whereIn('payment_purpose', ['loan_received', 'loan_payment'])
                ->orderByDesc('payment_date')
                ->whereHas('activity.activityBy', function ($query) use ($roles) {
                    $query->whereIn('role_id', $roles);
                })->get();

            return view('front-end.accounts.show')
                ->with([
//                    'projects'   => $projects
                    'payments' => $payments,
                    'paymentsloanacash' => $paymentsloanacash
                ]);
        }
    }
}

