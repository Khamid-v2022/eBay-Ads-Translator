<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SourceMarketPlace;
use App\Models\TargetMarketPlace;
use App\Models\MarketPlace;
use App\Models\Tienda;


class StoreController extends Controller
{
    public function index()
    {
        $tienda = Tienda::all();
        $marketplaces = MarketPlace::all(); 

        return view('store.index' , compact('tienda','marketplaces'));
    }

    function store_market_update(Request $request){

        $source_data = SourceMarketPlace::find($request->input('source_id'));
        if($source_data){
            $source_data->store_id = $request->input('store');
            $source_data->site_id = $request->input('source_marketplace');
            $source_data->save();
        }
        $target_marketplaces = $request->input('target_marketplace');
        
        foreach ($target_marketplaces as $target) {
                
                $targetMarketPlaces = TargetMarketPlace::where('source_id','=',$request->input('source_id'))->get();
            
                foreach($targetMarketPlaces as $target_data){
                   

                    $target_data->source_id = $request->input('source_marketplace');
                    $target_data->site_id = $target;
                    $target_data->save();
                }
                
            
        }
       
        
        return redirect()->back()->with('success','Actualizado con éxito');

    }
    function store_market_edit($id){

        
        $tienda = Tienda::all();
        $marketplaces = MarketPlace::all(); 

        $source_data = SourceMarketPlace::find($id);
        $stores = Tienda::find($source_data['store_id']);
        
        $sites = MarketPlace::find($source_data['site_id']);
        
        $data = [

            'store_id' => $stores['id'],
            'site_id'  => $sites['site_id'],
            'source_id' => $id
        ];
        
        $target_data_array = [];

        
        $target_datas = TargetMarketPlace::where('source_id','=',$id)->get()->toArray();
        foreach($target_datas as $target_data){
            
            $target_sites = MarketPlace::where('site_id', '=', $target_data['site_id'])->get();
            foreach($target_sites as $target_site){
                $target_site_id = $target_site['site_id'];
                
                $target_data_array[] = [
                    'sites_id' => $target_site_id
                ];
            }
            
       }
    //   echo "<pre>";
    //   echo print_r($data);
        return view('store.store_marketplace_edit',compact('tienda','marketplaces','data','target_data_array'));
    }

    public function store_market_list (){
        $sourceMarketplace = TargetMarketPlace::with('source_marketplaces')->get();
            $data = [];
            $uniqueKeys = [];

            foreach ($sourceMarketplace as $marketplace) {
                $sourceid = $marketplace['source_id'];
                $target_siteid = $marketplace['site_id'];
                $source_id = $marketplace['source_marketplaces']['id']; 
                $store_id = $marketplace['source_marketplaces']['store_id'];
                $source_market_id = $marketplace['source_marketplaces']['site_id'];

                $stores = Tienda::find($store_id);
                $store_name = $stores['store_name'];

                $sourcemarketplace_data = MarketPlace::where('site_id', '=', $source_market_id)->first();
                $sourcemark_name = $sourcemarketplace_data['site_name'];

                $key = $source_id .'_'.$store_name . '_' . $sourcemark_name;

                if (!isset($uniqueKeys[$key])) {
                    $uniqueKeys[$key] = true;
                    $data[$key] = [
                        'source_id' => $source_id,
                        'store_name' => $store_name,
                        'source_market' => $sourcemark_name,
                        'target_markets' => [],
                    ];
                }

                $target_data = MarketPlace::where('site_id', '=', $target_siteid)->get();
                foreach ($target_data as $target_places) {
                    $data[$key]['target_markets'][] = $target_places['site_name'];
                }
            }
            $data = array_values($data);
            
            return view('store.store_marketplace_list' , compact('data'));
    }
    public function store_market_destroy(Request $request){
        $source_marketplace = SourceMarketPlace::where('id', $request->source_id)->delete();
        $target_marketplaces = TargetMarketPlace::where('source_id', $request->source_id)->delete();
        // Return a response if needed
        return response()->json(['message' => 'Successfully deleted']);
    }
public function store(Request $request)
{
    $target_marketplace = $request->input('target_marketplace');
    $SourceMarketPlace = new SourceMarketPlace;
    $SourceMarketPlace->store_id = $request->input('store');
    $SourceMarketPlace->site_id = $request->input('source_marketplace');
    $SourceMarketPlace->save();

    foreach($target_marketplace as $targets){
       
            $TargetMarketPlace = new TargetMarketPlace;
            $TargetMarketPlace->source_id = $SourceMarketPlace->id;
            $TargetMarketPlace->site_id = $targets;
            $TargetMarketPlace->save();
            
    }
    return redirect()->route('store.manage')->with('success','Publicar oferta de trabajo creada exitosamente');
    // return $request;
    // $field_count  = $request->input('field_count');

    // for ($i=1; $i <= $field_count; $i++) { 
    //     $sourceStores = $request->input('source_store_name_'.$i);
    //     $targetStores = $request->input('target_store_name_'.$i);

    //     // Store Source Stores
    //     $source_store_id = reset($sourceStores);
    //     $saved = SourceStore::create(['store_id' => $source_store_id]);

    //     if($saved){
    //         // Store Target Stores
    //         foreach ($targetStores as $store_id) {
    //             TargetStore::create([
    //                 'parent_id' => $saved->id,
    //                 'source_store_id' => $saved->store_id,
    //                 'store_id' => $store_id
    //             ]);
    //         }
    //     }

    // }

    // return redirect()->back()->with('success', 'Stores added successfully!');
}


}
