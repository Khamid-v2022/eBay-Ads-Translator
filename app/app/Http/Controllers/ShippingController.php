<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MarketPlace;
use App\Models\MarketPlaceDetail;


class ShippingController extends Controller
{
    public function index(){

        $shipping = MarketPlaceDetail::with('marketplace')->get();
        // return $shipping;
        return view('shipping.listing' , compact('shipping'));
    }


    public function create(){

        $shipping = MarketPlace::get();
        // return $shipping;
        return view('shipping.create' , compact('shipping'));
    }


    public function store(Request $request){

        // return $request;
        // Create a new instance of the MarketPlaceDetail model
        $marketPlaceDetail = new MarketPlaceDetail;

        $marketPlaceDetail->fill([
            'site_id' => $request->site_id,
            'shipping_service' => $request->shipping_service,
            'location' => $request->location,
            'shipping_service_cost' => $request->shipping_service_cost,
            'free_shipping' => $request->free_shipping,
            'shipping_type' => $request->shipping_type,
            'dispatch_time_max' => $request->dispatch_time_max,
            'returns_accepted' => $request->returns_accepted,
            'returns_accepted_option' => $request->returns_accepted_option,
        ]);

        // Save the model to the database
        $marketPlaceDetail->save();

        // Redirect to a view or return a response
        return redirect()->back()->with('success', 'Created Successfully');
    }


    public function edit(string $id){

        $shipping = MarketPlaceDetail::where('id', $id)->first();
        $marketplace = MarketPlace::with('marketplaceDetail')->get();
        $marketplaces = MarketPlace::with('marketplaceDetail')->where('site_id', $shipping->site_id)->first();
        // return $marketplace;
        return view('shipping.edit' , compact('shipping', 'marketplace', 'marketplaces'));
    }


    public function update(Request $request, $id)
    {
        // return $request;

        // Find the MarketPlaceDetail by ID
        $marketPlaceDetail = MarketPlaceDetail::findOrFail($id);

        // Update the attributes based on the request data
        $marketPlaceDetail->fill([
            'site_id' => $request->site_id,
            'shipping_service' => $request->shipping_service,
            'location' => $request->location,
            'shipping_service_cost' => $request->shipping_service_cost,
            'free_shipping' => $request->free_shipping,
            'shipping_type' => $request->shipping_type,
            'dispatch_time_max' => $request->dispatch_time_max,
            'returns_accepted' => $request->returns_accepted,
            'returns_accepted_option' => $request->returns_accepted_option,
        ]);

        // Save the updated model to the database
        $marketPlaceDetail->update();

        // Redirect to a view or return a response
        return redirect()->back()->with('success', 'Updated Successfully');
    }


    public function destroy(Request $request)
    {
        // Find the shipping record by ID
        $shipping = MarketPlaceDetail::find($request->id);

        if (!$shipping) {
            // Return a response indicating that the record was not found
            return response()->json(['error' => 'Record not found'], 404);
        }

        // Perform the deletion
        $shipping->delete();

        // Return a success response
        return response()->json(['success' => 'Record deleted successfully']);
    }

}
