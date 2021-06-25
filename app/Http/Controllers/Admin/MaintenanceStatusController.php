<?php

namespace App\Http\Controllers\Admin;


use App\Models\WorkCard;
use App\Services\MaintenanceStatusServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaintenanceStatusController extends Controller
{
    use MaintenanceStatusServices;

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_maintenance_status')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $pending = $this->search('pending',$request);
        $processing = $this->search('processing',$request);
        $finished = $this->search('finished',$request);
        $scheduled = $this->search('scheduled',$request);

        $cardsPending       = $pending['cards']->paginate(10);
        $cardsProcessing    = $processing['cards']->paginate(10);
        $cardsFinished      = $finished['cards']->paginate(10);
        $cardsScheduled     = $scheduled['cards']->paginate(10);

        $count['pending']       = $pending['count'];
        $count['processing']     = $processing['count'];
        $count['finished']      = $finished['count'];
        $count['scheduled']     = $scheduled['count'];

        return view('admin.maintenance_status.index',
            compact('cardsPending', 'cardsProcessing', 'cardsFinished', 'cardsScheduled', 'count'));
    }

    public function getMorePendingCards(Request $request)
    {
        if ($request['page'] == "undefined") {
            $request['page'] = 2;
        }

        $page = $request['page'] + 1;

        $cardsPending = $this->search('pending', $request)['cards']->paginate(10);

        $last_page = $cardsPending->lastPage();

        $firstLoopNumber = ($request['page'] - 1) * 10;

        if ($request->ajax()) {

            $view = view('admin.maintenance_status.ajax_more_pending_cards',
                compact('cardsPending', 'page', 'last_page', 'firstLoopNumber'))->render();
            return response()->json(['html' => $view]);
        }

        return view('admin.maintenance_status.ajax_more_pending_cards',
            compact('cardsPending', 'page', 'last_page', 'firstLoopNumber'));
    }

    public function getMoreProcessingCards(Request $request)
    {

        if ($request['page'] == "undefined") {
            $request['page'] = 2;
        }

        $page = $request['page'] + 1;

        $cardsProcessing = $this->search('processing',$request)['cards']->paginate(10);

        $last_page = $cardsProcessing->lastPage();

        $firstLoopNumber = ($request['page'] - 1) * 10;

        if ($request->ajax()) {

            $view = view('admin.maintenance_status.ajax_more_processing_cards',
                compact('cardsProcessing', 'page', 'last_page', 'firstLoopNumber'))->render();
            return response()->json(['html' => $view]);
        }

        return view('admin.maintenance_status.ajax_more_processing_cards',
            compact('cardsProcessing', 'page', 'last_page', 'firstLoopNumber'));
    }

    public function getMoreFinishedCards(Request $request)
    {
        if ($request['page'] == "undefined") {
            $request['page'] = 2;
        }

        $page = $request['page'] + 1;

        $cardsFinished = $this->search('finished',$request)['cards']->paginate(10);

        $last_page = $cardsFinished->lastPage();

        $firstLoopNumber = ($request['page'] - 1) * 10;

        if ($request->ajax()) {

            $view = view('admin.maintenance_status.ajax_more_finished_cards',
                compact('cardsFinished', 'page', 'last_page', 'firstLoopNumber'))->render();
            return response()->json(['html' => $view]);
        }

        return view('admin.maintenance_status.ajax_more_finished_cards',
            compact('cardsFinished', 'page', 'last_page', 'firstLoopNumber'));
    }

    public function getMoreScheduledCards(Request $request)
    {
        if ($request['page'] == "undefined") {
            $request['page'] = 2;
        }

        $page = $request['page'] + 1;

        $cardsScheduled = $this->search('scheduled',$request)['cards']->paginate(10);

        $last_page = $cardsScheduled->lastPage();

        $firstLoopNumber = ($request['page'] - 1) * 10;

        if ($request->ajax()) {

            $view = view('admin.maintenance_status.ajax_more_scheduled_cards',
                compact('cardsScheduled', 'page', 'last_page', 'firstLoopNumber'))->render();
            return response()->json(['html' => $view]);
        }

        return view('admin.maintenance_status.ajax_more_scheduled_cards',
            compact('cardsScheduled', 'page', 'last_page', 'firstLoopNumber'));
    }
}
