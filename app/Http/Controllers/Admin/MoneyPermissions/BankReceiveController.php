<?php
namespace App\Http\Controllers\Admin\MoneyPermissions;

use Exception;
use App\Models\EmployeeData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExportPrinterFactory;
use App\ModelsMoneyPermissions\BankReceivePermission;
use App\ModelsMoneyPermissions\BankExchangePermission;
use App\ModelsMoneyPermissions\LockerExchangePermission;
use App\Http\Requests\MoneyPermissions\BankReceiveRequest;
use App\Http\Requests\MoneyPermissions\BankReceiveEditRequest;
use App\Http\Controllers\DataExportCore\MoneyPermissions\BankReceives;

class BankReceiveController extends Controller
{
    use ReceiveTrait;

    function index(Request $req) {
        if (!auth()->user()->can('view_bank_receive_permissions')) {
            return redirect(route('admin:home'))->with(['authorization' => __('words.unauthorized')]);
        }
        $status = $req->has('permission_status') && $req->permission_status != '' ? $req->permission_status : NULL;
        $receiver_id = $req->has('receiver_id') && $req->receiver_id != '' ? $req->receiver_id : NULL;
        $date_from = $req->has('date_from') && $req->date_from != '' ? $req->date_from : NULL;
        $date_to = $req->has('date_to') && $req->date_to != '' ? $req->date_to : date('Y-m-d');
        $rows = $req->has('rows') && $req->rows != '' ? $req->rows : 10;
        $key = $req->has('key') && $req->key != '' ? $req->key : NULL;
        $branch = $req->has('branch_id') && authIsSuperAdmin() && $req->branch_id != '' ? $req->branch_id : NULL;
        if (!authIsSuperAdmin()) $branch = auth()->user()->branch_id;

        $search_form = (new CommonLogic)->builde_receive_search_form(route('admin:bank-receives.index'));
        $receives = BankReceivePermission::select(
            'permission_number' ,'amount' ,'operation_date' ,'status' ,'created_at' ,'updated_at' ,
            'bank_exchange_permission_id' ,'employee_id' ,'id' ,'source_type'
        )
        ->with([
            'exchange_permission' => function ($q) {
                $q->select('id' ,'permission_number');
            },
            'locker_exchange_permission' => function ($q) {
                $q->select('id' ,'permission_number');
            },
            'employee' => function ($q) {
                $q->select('id' ,'name_ar' ,'name_en');
            },
            'branch' => function ($q) {
                $q->select('id' ,'name_ar' ,'name_en');
            }
        ])
        ->when($status ,function ($q) use ($status) {
            $q->where('status' ,$status);
        })
        ->when($branch ,function ($q) use ($branch) {
            $q->where('branch_id' ,$branch);
        })
        ->when($receiver_id ,function ($q) use ($receiver_id) {
            $q->where('employee_id' ,$receiver_id);
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
                'exchange-number' => 'bank_exchange_permission_id',
                'source-type' => 'source_type',
                'money-receiver' => 'employee_id',
                'amount' => 'amount',
                'operation-date' => 'operation_date',
                'status' => 'status',
            ];
            $receives = $receives->orderBy($sort_fields[$sort_by], $sort_method);
        } else {
            $receives = $receives->orderBy('id', 'desc');
        }
        
        if ($req->has('invoker') && in_array($req->invoker, ['print', 'excel'])) {
            if (
                ($req->invoker == 'print' && !auth()->user()->can('print_bank_receive_permissions')) ||
                ($req->invoker == 'excel' && !auth()->user()->can('export_bank_receive_permissions'))
            ) {
                return redirect(route('admin:bank-receives.index'))->with(['authorization' => __('words.unauthorized')]);
            }
            $visible_columns = $req->has('visible_columns') ? $req->visible_columns : [];
            return (new ExportPrinterFactory(new BankReceives($receives, $visible_columns), $req->invoker))();
        }

        $receives = $receives->paginate($rows);

        return view('admin.money-permissions.bank-receive.index' ,compact('receives' ,'search_form'));
    }

    function create() {
        if (!auth()->user()->can('create_bank_receive_permissions')) {
            return redirect(route('admin:bank-receives.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        $branch = authIsSuperAdmin() ? NULL : auth()->user()->branch_id;
        $lang = app()->getLocale();
        $bank_exchange_permissions = BankExchangePermission::when($branch ,function ($q) use ($branch) {
            $q->where('branch_id' ,$branch);
        })
        ->with([
            'fromBank' => function ($q) use ($lang) {
                $q->select('id' ,'name_'.$lang.' as trans_name');
            },
            'toBank' => function ($q) use ($lang) {
                $q->select('id' ,'name_'.$lang.' as trans_name');
            }
        ])
        ->whereNull('bank_receive_permission_id')
        ->where('status' ,'approved')
        ->where('destination_type' ,'bank')
        ->get();
        $locker_exchange_permissions = LockerExchangePermission::when($branch ,function ($q) use ($branch) {
            $q->where('branch_id' ,$branch);
        })
        ->with([
            'fromLocker' => function ($q) use ($lang) {
                $q->select('id' ,'name_'.$lang.' as trans_name');
            },
            'toBank' => function ($q) use ($lang) {
                $q->select('id' ,'name_'.$lang.' as trans_name');
            }
        ])
        ->whereNull('locker_receive_permission_id')
        ->where('status' ,'approved')
        ->where('destination_type' ,'bank')
        ->get();
        $exchange_permissions = $locker_exchange_permissions->merge($bank_exchange_permissions);
        if (count($exchange_permissions) <= 0) {
            return redirect(route('admin:bank-receives.index'))->with([
                'message' => __('words.no-exchange-permission'),
                'alert-type' => 'error'
            ]);
        }
        $last_bank_receive = BankReceivePermission::orderBy('id' ,'desc')->select('permission_number')->first();
        $permission_number = $last_bank_receive ? ((int)$last_bank_receive->permission_number + 1) : 200000001;
        $employees = EmployeeData::select('id' ,'name_ar' ,'name_en')->get();
        return view('admin.money-permissions.bank-receive.create' ,compact('exchange_permissions' ,'permission_number' ,'employees'));
    }

    function store(BankReceiveRequest $req) {
        if (!auth()->user()->can('create_bank_receive_permissions')) {
            return redirect(route('admin:bank-receives.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        $data = $req->all();

        $exchange = $data['source_type'] == 'locker' ?
            LockerExchangePermission::select(
                'amount', 'branch_id', 'id'
            )->where('id' ,$data['bank_exchange_permission_id'])->first()
            : BankExchangePermission::select(
                'amount', 'branch_id', 'id'
            )->where('id' ,$data['bank_exchange_permission_id'])->first();
        $exchange_key = $data['source_type'] == 'locker' ? 'locker_receive_permission_id' : 'bank_receive_permission_id';
        
        if (!$exchange || $exchange->$exchange_key != NULL) {
            return redirect(route('admin:bank-receives.index'))->with([
                'message' => __('words.exchange-permission-received'),
                'alert-type' => 'error'
            ]);
        }

        $data['branch_id'] = $exchange->branch_id;
        $data['amount'] = $exchange->amount;
        
        $receive_id = BankReceivePermission::create($data)->id;
        $receive_key = $data['source_type'] == 'locker' ? 'locker_receive_permission_id' : 'bank_receive_permission_id';
        $exchange->update([$receive_key => $receive_id]);

        return redirect(route('admin:bank-receives.index'))->with([
            'message' => __('words.permission-is-created'),
            'alert-type' => 'success'
        ]);
    }

    function show($id) {
        if (!auth()->user()->can('view_bank_receive_permissions')) {
            return response(['message' => __('words.unauthorized')] ,400);
        }
        try {
            $receive = BankReceivePermission::with([
                'exchange_permission' => function ($q) {
                    $q->select(
                        'id' ,'permission_number' ,'from_bank_id' ,'to_bank_id'
                    )
                    ->with([
                        'fromBank' => function ($q) {
                            $q->select('id' ,'name_ar' ,'name_en');
                        },
                        'toBank' => function ($q) {
                            $q->select('id' ,'name_ar' ,'name_en');
                        },
                    ]);
                },
                'locker_exchange_permission' => function ($q) {
                    $q->select(
                        'id' ,'permission_number' ,'from_locker_id' ,'to_locker_id'
                    )
                    ->with([
                        'fromLocker' => function ($q) {
                            $q->select('id' ,'name_ar' ,'name_en');
                        },
                        'toBank' => function ($q) {
                            $q->select('id' ,'name_ar' ,'name_en');
                        },
                    ]);
                },
                'employee' => function ($q) {
                    $q->select('id' ,'name_ar' ,'name_en');
                },
                'branch'
            ])->findOrFail($id);
        } catch (Exception $e) {
            return response(['message' => __('words.permission-not-exists')] ,400);
        }
        $code = view('admin.money-permissions.bank-receive.show' ,compact('receive'))->render();
        return response(['code' => $code]);
    }

    private function receive_maintainable($id) {
        $receive = BankReceivePermission::with([
            'exchange_permission' => function ($q) {
                $q->select(
                    'id' ,'from_bank_id' ,'to_bank_id' ,'branch_id' ,'note' ,'amount' ,'permission_number'
                )
                ->with(['fromBank', 'toBank' ]);
            },
            'locker_exchange_permission' => function ($q) {
                $q->select(
                    'id' ,'from_locker_id' ,'to_locker_id' ,'branch_id' ,'note' ,'amount' ,'permission_number'
                )
                ->with(['fromLocker', 'toBank']);
            }
        ])->find($id);
        if (!$receive) throw new Exception(__('words.permission-not-exists'));
        if ($receive->status != 'pending') {throw new Exception(__('words.permission-not-pending'));}
        return $receive;
    }

    function update(BankReceiveEditRequest $req ,$id) {
        if (!auth()->user()->can('edit_bank_receive_permissions')) {
            return redirect(route('admin:bank-receives.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        return $this->run_update($req ,$id);
    }

    function approve($id) {
        if (!auth()->user()->can('approve_bank_receive_permissions')) {
            return redirect(route('admin:bank-receives.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        try {
            DB::beginTransaction();
            $receive = $this->receive_maintainable($id);
            $bank = $receive->source_type == 'bank' ? $receive->exchange_permission->toBank : $receive->locker_exchange_permission->toBank;
            $money_permission_account = (new CommonLogic)->account_relation_exists('receive' ,'bank');
            $bank_account = (new CommonLogic)->get_bank_account_tree($bank);
            $receive->update(['status' => 'approved']);
            $bank_process = new BankProcess($receive->amount ,$bank);
            $bank_process->increment();
            $restriction_process = new BankRestrictionProcess($money_permission_account ,$bank_account);
            $restriction_process->set_receive_permission($receive);
            $restriction_process();
            if ($receive->source_type == 'bank') CommonLogic::create_bank_transfer($receive);
            else CommonLogic::create_locker_bank_transaction($receive);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect(route('admin:bank-receives.index'))->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
        DB::commit();
        return redirect(route('admin:bank-receives.index'))->with([
            'message' => __('words.permission-is-approved'),
            'alert-type' => 'success'
        ]);
    }
    
    function get_invoker_name() {
        return StaticNames::BANK;
    }

}
