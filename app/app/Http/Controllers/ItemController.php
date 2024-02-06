<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MarketPlace;
use App\Models\Item;

class ItemController extends Controller
{
    public function create(){
        $marketplace = MarketPlace::all();
        // return $marketplace;
        return view('item.create', compact('marketplace'));
    }

    public function store( Request $request)
    {

        // return $request;
        // Create a new instance of the MarketPlaceDetail model
        $Item = new Item;

        $Item->fill([
            'site_id' => $request->site_id,
            'name1' => $request->name1,
            'value1' => $request->value1,
            'name2' => $request->name2,
            'value2' => $request->value2,
            'name3' => $request->name3,
            'value3' => $request->value3,
            'name4' => $request->name4,
            'value4' => $request->value4,
        ]);

        // Save the model to the database
        $Item->save();

        // Redirect to a view or return a response
        return redirect()->back()->with('success', 'Created Successfully');
    }
}
