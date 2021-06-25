<?php
namespace App\Http\Controllers\Admin\MoneyPermissions;

use Exception;
use App\Models\Account;
use App\Models\EmployeeData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExportPrinterFactory;
use App\ModelsMoneyPermissions\BankExchangePermission;
use App\Http\Requests\MoneyPermissions\BankExchangeRequest;
use App\Http\Requests\MoneyPermissions\BankExchangeLockerRequest;
use App\Http\Controllers\DataExportCore\MoneyPermissions\BankExchanges;
use App\Models\Locker;

class BankExchangeController extends Controller {
    use ExchangeTrait;
    
    function index(Request $req) {
        if (!auth()->user()->can('view_bank_exchange_permissions')) {
            return redirect(route('admin:home'))->with(['authorization' => __('words.unauthorized')]);
        }

        $source = $req->has('source_id') && $req->source_id != '' ? $req->source_id : NULL;
        $receiver = $req->has('receiver_id') && $req->receiver_id != '' ? $req->receiver_id : NULL;
        $status = $req->has('permission_status') && $req->permission_status != '' ? $req->permission_status : NULL;
        $date_from = $req->has('date_from') && $req->date_from != '' ? $req->date_from : NULL;
        $date_to = $req->has('date_to') && $req->date_to != '' ? $req->date_to : date('Y-m-d');
        $rows = $req->has('rows') && $req->rows != '' ? $req->rows : 10;
        $key = $req->has('key') && $req->key != '' ? $req->key : NULL;
        $branch = $req->has('branch_id') && authIsSuperAdmin() && $req->branch_id != '' ? $req->branch_id : NULL;
        if (!authIsSuperAdmin()) $branch = auth()->user()->branch_id;

        $search_form = (new CommonLogic)->builde_search_form('bank' ,route('admin:bank-exchanges.index'));
        $exchanges = BankExchangePermission::select(
            'permission_number' ,'amount' ,'operation_date' ,'status' ,'created_at' ,'updated_at' ,
            'from_bank_id' ,'to_bank_id' ,'employee_id' ,'id' ,'destination_type'
        )
        ->with([
            'fromBank' => function ($q) {
                $q->select('id' ,'name_ar' ,'name_en');
            },
            'toBank' => function ($q) {
                $q->select('id' ,'name_ar' ,'name_en');
            },
            'toLocker' => function ($q) {
                $q->select('id' ,'name_ar' ,'name_en');
            },
            'employee' => function ($q) {
                $q->select('id' ,'name_ar' ,'name_en');
            },
            'branch' => function ($q) {
                $q->select('id' ,'name_ar' ,'name_en');
            }
        ])
        ->when($source ,function ($q) use ($source) {
            $q->where('from_bank_id' ,$source);
        })
        ->when($receiver ,function ($q) use ($receiver) {
            $q->where('to_bank_id' ,$receiver);
        })
        ->when($status ,function ($q) use ($status) {
            $q->where('status' ,$status);
        })
        ->when($branch ,function ($q) use ($branch) {
            $q->where('branch_id' ,$branch);
        })
        ->when($date_from ,function ($q) use ($date_from ,$date_to) {
            $q->where('operation_date' ,'>=' ,$date_from)->where('operation_date' ,'<=' ,$date_to);
        })
        ->when($key ,function ($q) use ($key) {
            $q->where('permission_number' ,'like' ,"%$key%")->orWhere('operation_date' ,'like' ,"%$key%");
        });

        if ($req->has('sort_by') && $req->sort_by != '') {
            $sort_by = $req->sort_by;
            $sort_method = $req->has('sort_method') ? $req->sort_method : 'asc';
            if (!in_array($sort_method, ['asc', 'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'permission-number' => 'permission_number',
                'bank-from' => 'from_bank_id',
                'bank-to' => 'to_bank_id',
                'money-receiver' => 'employee_id',
                'amount' => 'amount',
                'operation-date' => 'operation_date',
                'status' => 'status',
            ];
            $exchanges = $exchanges->orderBy($sort_fields[$sort_by], $sort_method);
        } else {
            $exchanges = $exchanges->orderBy('id', 'desc');
        }
        
        if ($req->has('invoker') && in_array($req->invoker, ['print', 'excel'])) {
            if (
                ($req->invoker == 'print' && !auth()->user()->can('print_bank_exchange_permissions')) ||
                ($req->invoker == 'excel' && !auth()->user()->can('export_bank_exchange_permissions'))
            ) {
                return redirect(route('admin:bank-exchanges.index'))->with(['authorization' => __('words.unauthorized')]);
            }
            $visible_columns = $req->has('visible_columns') ? $req->visible_columns : [];
            return (new ExportPrinterFactory(new BankExchanges($exchanges, $visible_columns), $req->invoker))();
        }

        $exchanges = $exchanges->paginate($rows);

        return view('admin.money-permissions.bank-exchange.index' ,compact('exchanges' ,'search_form'));
    }

    function create() {
        if (!auth()->user()->can('create_bank_exchange_permissions')) {
            return redirect(route('admin:bank-exchanges.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        $type = isset($_GET['type']) && $_GET['type'] == 'locker';
        $form_action = $type ? route('admin:bank-exchanges.store-to-locker') : route('admin:bank-exchanges.store');
        $validation_class = $type ?
            'App\Http\Requests\MoneyPermissions\BankExchangeLockerRequest' : 'App\Http\Requests\MoneyPermissions\BankExchangeRequest';
        if (authIsSuperAdmin()) {
            $money_source = Account::select('id', 'name_en', 'name_ar', 'balance')->get();
            $money_destination = $type ? Locker::select('id', 'name_en', 'name_ar', 'balance')->get() : clone $money_source;
        } else {
            $money_source = Account::select('id', 'name_en', 'name_ar', 'balance')
            ->whereDoesntHave('assigned_users')->get();
            $money_source2 = Account::select('id', 'name_en', 'name_ar', 'balance')
            ->whereHas('assigned_users' ,function ($q) {
                $q->where('user_id' ,auth()->user()->id);
            })->get();
            $money_source = $money_source->merge($money_source2);

            if ($type) {
                $money_destination = Locker::select('id', 'name_en', 'name_ar', 'balance')
                ->whereDoesntHave('assigned_users')->get();
                $money_destination2 = Locker::select('id', 'name_en', 'name_ar', 'balance')
                ->whereHas('assigned_users' ,function ($q) {
                    $q->where('user_id' ,auth()->user()->id);
                })->get();
                $money_destination = $money_destination->merge($money_destination2);
            } else {
                $money_destination = clone $money_source;
            }
        }
        $last_bank_exchange = BankExchangePermission::orderBy('id' ,'desc')->select('permission_number')->first();
        $permission_number = $last_bank_exchange ? ((int)$last_bank_exchange->permission_number + 1) : 200000001;
        $employees = EmployeeData::select('id' ,'name_ar' ,'name_en')->get();
        return view('admin.money-permissions.bank-exchange.create' ,[
            'money_source' => $money_source,
            'permission_number' => $permission_number,
            'employees' => $employees,
            'money_destination' => $money_destination,
            'input_title' => $type ? __('Locker To') : __('Account To'),
            'select_one_title' => $type ? __('Select Locker') : __('Select Account'),
            'validation_class' => $validation_class,
            'form_action' => $form_action
        ]);
    }

    function store(BankExchangeRequest $req) {
        if (!auth()->user()->can('create_bank_exchange_permissions')) {
            return redirect(route('admin:bank-exchanges.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        $data = $req->all();
        if (!authIsSuperAdmin()) $data['branch_id'] = auth()->user()->branch_id;
        BankExchangePermission::create($data);
        return redirect(route('admin:bank-exchanges.index'))->with([
            'message' => __('words.permission-is-created'),
            'alert-type' => 'success'
        ]);
    }

    function storeToLocker(BankExchangeLockerRequest $req) {
        if (!auth()->user()->can('create_bank_exchange_permissions')) {
            return redirect(route('admin:bank-exchanges.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        $data = $req->all();
        if (!authIsSuperAdmin()) $data['branch_id'] = auth()->user()->branch_id;
        BankExchangePermission::create($data);
        return redirect(route('admin:bank-exchanges.index'))->with([
            'message' => __('words.permission-is-created'),
            'alert-type' => 'success'
        ]);
    }

    function load_branch_banks() {
        $branch_id = isset($_GET['branch_id']) && $_GET['branch_id'] != '' ? $_GET['branch_id'] : NULL;
        $type = isset($_GET['type']) && $_GET['type'] == 'locker';
        if (!$branch_id) return response(['message' => __('words.No Data Available')] ,400);
        $banks = (new CommonLogic)->fetch_source_money(new Account ,$branch_id);
        try {
            $banks[0];
        } catch (Exception $e) {
            return response(['message' => __('words.No Data Available')] ,400);
        }
        $from_code = view('admin.money-permissions.source-money-select' ,[
            'money_source' => $banks,
            'select_one' => __('Select Bank Account'),
            'include_balance' => true
        ])->render();
        $to_code = view('admin.money-permissions.source-money-select' ,[
            'money_source' => $type ? (new CommonLogic)->fetch_source_money(new Locker ,$branch_id) : $banks,
            'select_one' => __('Select Bank Account'),
            'include_balance' => false
        ])->render();
        return response(['from_code' => $from_code ,'to_code' => $to_code]);
    }

    private function exchange_maintainable($id) {
        $exchange = BankExchangePermission::find($id);
        if (!$exchange) throw new Exception(__('words.permission-not-exists'));
        if ($exchange->status != 'pending') {throw new Exception(__('words.permission-not-pending'));}
        return $exchange;
    }

    function show(Request $req ,$id) {
        if (!auth()->user()->can('view_bank_exchange_permissions')) {
            return response(['message' => __('words.unauthorized')] ,400);
        }
        try {
            $exchange = BankExchangePermission::with([
                'fromBank' => function ($q) {
                    $q->select('id' ,'name_ar' ,'name_en');
                },
                'toBank' => function ($q) {
                    $q->select('id' ,'name_ar' ,'name_en');
                },
                'toLocker' => function ($q) {
                    $q->select('id' ,'name_ar' ,'name_en');
                },
                'employee' => function ($q) {
                    $q->select('id' ,'name_ar' ,'name_en');
                },
                'branch'
            ])->findOrFail($id);
        } catch (Exception $e) {
            return response(['message' => __('words.permission-not-exists')] ,400);
        }
        $code = view('admin.money-permissions.bank-exchange.show' ,compact('exchange'))->render();
        return response(['code' => $code]);
    }

    function approve($id) {
        if (!auth()->user()->can('approve_bank_exchange_permissions')) {
            return redirect(route('admin:bank-exchanges.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        try {
            DB::beginTransaction();
            $exchange = $this->exchange_maintainable($id);
            $fromBank = $exchange->fromBank;
            $money_permission_account = (new CommonLogic)->account_relation_exists('exchange' ,'bank');
            $bank_account = (new CommonLogic)->get_bank_account_tree($fromBank);
            $exchange->update(['status' => 'approved']);
            $bank_process = new BankProcess($exchange->amount ,$fromBank);
            $bank_process->decrement();
            $restriction_process = new BankRestrictionProcess($money_permission_account ,$bank_account);
            $restriction_process->set_exchange_permission($exchange);
            $restriction_process();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect(route('admin:bank-exchanges.index'))->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
        DB::commit();
        return redirect(route('admin:bank-exchanges.index'))->with([
            'message' => __('words.permission-is-approved'),
            'alert-type' => 'success'
        ]);
    }

    function update(BankExchangeRequest $req ,$id) {
        if (!auth()->user()->can('edit_bank_exchange_permissions')) {
            return redirect(route('admin:bank-exchanges.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        return $this->run_update($req ,$id);
    }

    function updateToLocker(BankExchangeLockerRequest $req ,$id) {
        if (!auth()->user()->can('edit_bank_exchange_permissions')) {
            return redirect(route('admin:bank-exchanges.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        return $this->run_update($req ,$id);
    }

    function get_money_sources($exchange) {
        if (authIsSuperAdmin()) {
            $money_source = Account::where('branch_id' ,$exchange->branch_id)->select('id', 'name_en', 'name_ar', 'balance')->get();
        } else {
            $money_source = Account::where('branch_id' ,$exchange->branch_id)->select('id', 'name_en', 'name_ar', 'balance')
            ->whereDoesntHave('assigned_users')->get();
            $money_source2 = Account::where('branch_id' ,$exchange->branch_id)->select('id', 'name_en', 'name_ar', 'balance')
            ->whereHas('assigned_users' ,function ($q) {
                $q->where('user_id' ,auth()->user()->id);
            })->get();
            $money_source = $money_source->merge($money_source2);
        }
        return $money_source;
    }

    function get_money_destinations($exchange) {
        if (authIsSuperAdmin()) {
            $money_destination = $exchange->destination_type == 'locker' ?
                Locker::where('branch_id' ,$exchange->branch_id)->select('id', 'name_en', 'name_ar', 'balance')->get()
                : Account::where('branch_id' ,$exchange->branch_id)->select('id', 'name_en', 'name_ar', 'balance')->get();
        } else {
            $money_destination = $exchange->destination_type == 'locker' ?
                Locker::where('branch_id' ,$exchange->branch_id)->select('id', 'name_en', 'name_ar', 'balance')
                ->whereDoesntHave('assigned_users')->get()
                : Account::where('branch_id' ,$exchange->branch_id)->select('id', 'name_en', 'name_ar', 'balance')
                ->whereDoesntHave('assigned_users')->get();
            $money_destination2 = $exchange->destination_type == 'locker' ?
                Locker::where('branch_id' ,$exchange->branch_id)->select('id', 'name_en', 'name_ar', 'balance')
                ->whereHas('assigned_users' ,function ($q) {
                    $q->where('user_id' ,auth()->user()->id);
                })->get()
                : Account::where('branch_id' ,$exchange->branch_id)->select('id', 'name_en', 'name_ar', 'balance')
                ->whereHas('assigned_users' ,function ($q) {
                    $q->where('user_id' ,auth()->user()->id);
                })->get();
            $money_destination = $money_destination->merge($money_destination2);
        }
        return $money_destination;
    }

    function get_invoker_name() {
        return StaticNames::BANK;
    }

}
