<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ConcessionExecution\CreateRequest;
use App\Models\Concession;
use App\Models\ConcessionExecution;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConcessionExecutionController extends Controller
{
    public function save(CreateRequest $request)
    {

        try {

            $concession = Concession::find($request['item_id']);

            $data = $request->validated();

            $concessionExecution = $concession->concessionExecution;

            if ($concession->concessionExecution) {

                $concessionExecution->update($data);

            }else {

                $data['concession_id'] = $request['item_id'];

                ConcessionExecution::create($data);
            }

            return redirect()->back()->with(['message'=> __('Execution time saved successfully'), 'alert-type'=>'success']);

        } catch (\Exception $e) {

            return redirect()->back()->with(['message'=> __('sorry, please try later'), 'alert-type'=>'error']);
        }
    }
}
