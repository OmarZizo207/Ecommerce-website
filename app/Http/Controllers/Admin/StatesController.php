<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\DataTables\StateDatatable;

use Illuminate\Http\Request;
use App\Model\State;
use App\Model\City;
use Form;

class StatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StateDatatable $state)
    {
        return $state->render('admin.states.index',['title'=> trans('admin.states')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(request()->ajax()) {
            if (request()->has('country_id')) {
                $select = request()->has('select') ? request('select') : '';

                return Form::select('city_id', City::where('country_id',request('country_id'))->pluck('city_name_'.session('lang'), 'id') , $select, ['class' => 'form-control','placeholder' => trans('admin.choose_city')]);
            }
        }
        return view('admin.states.create',['title'=> trans('admin.create_state')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = $this->validate(request(),[
            'state_name_ar'          => 'required',
            'state_name_en'          => 'required',
            'city_id'                => 'required|numeric',
            'country_id'             => 'required|numeric',
        ], [],[
            'state_name_ar'          => trans('admin.state_name_ar'),
            'state_name_ar'          => trans('admin.state_name_en'),
            'country_id'             => trans('admin.city_id'),
            'country_id'             => trans('admin.country_id'),        
        ]);

        State::create($data);
        session()->flash('success',trans('admin.record_added'));
        return redirect(aurl('states'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $state = State::find($id);
        $title   = trans('admin.edit_record');
        return view('admin.states.edit',compact('state','title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $data = $this->validate(request(),[
            'State_name_ar'          => 'sometime|nullable',
            'State_name_en'          => 'sometime|nullable',
            'city_id'                => 'required|numeric',
            'country_id'             => 'required|numeric',            
        ], [],[
            'state_name_ar'          => trans('admin.state_name_ar'),
            'state_name_en'          => trans('admin.state_name_en'),
            'city_id'                => trans('admin.city_id'),
            'country_id'             => trans('admin.country_id'),            
        ]);


        State::where('id',$id)->update($data);
        session()->flash('success',trans('admin.updated_record'));
        return redirect(aurl('states'));    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        State::find($id)->delete();
        session()->flash('success',trans('admin.deleted_record'));
        return redirect(aurl('states'));
    }

    public function multi_delete()
    {
        if(is_array(request('item'))) {
            foreach(request('item') as $id) {
                State::find($id)->delete();
            }
        } else {
            State::find(request('item'))->delete();
        }
        session()->flash('success',trans('admin.deleted_record'));
        return redirect(aurl('states'));
    }

}
