<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;

class GlobalModelObserver
{
    public function creating($model)
    {
        $model->created_by = Auth::id();
        if (Auth::check() && Auth::user()->hasRole('agent')){
            $model->store_id = Auth::user()->store_id;
        }
        else{
            $model->store_id = request('store_id');
        }
    }
    public function updating($model)
    {
        $model->updated_by = Auth::id();

//        if (Auth::check()  && Auth::user()->hasRole('agent')){
//            $model->store_id = Auth::user()->store_id;
//        }
//        else{
//            $model->updated_by = Auth::id();
//            if($model !== 'User'){
//                if (request()->has('store_id'))
//                {
//                    $model->store_id = request('store_id');
//                }
//            }
//        }

    }
}
