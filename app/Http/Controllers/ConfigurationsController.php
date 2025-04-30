<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\SubFeature;
use App\Models\Configuration;
use App\Models\DetailConfiguration;
use Illuminate\Http\Request;

class ConfigurationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tab = 'pos';
        $features = Feature::with("subFeatures.configurations.detailConfigurations")->get();
        echo "<script>console.log('Debug Objects: " . $features . "' );</script>";
        return view('setting', compact('features', 'tab'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($tab)
    {
        $features = Feature::with("subFeatures.configurations.detailConfigurations")->get();
        echo "<script>console.log('Debug Objects: " . $features . "' );</script>";
        return view('setting', compact('features', 'tab'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Feature $features)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feature $features)
    {
        $type = $request->type;
        $id = $request->id;
        $isActive = $request->is_active;

        switch ($type) {
            case 'subFeature':
                $item = SubFeature::findOrFail($id);
                break;
            case 'configuration':
                $item = Configuration::findOrFail($id);
                break;
            case 'detailConfiguration':
                $item = DetailConfiguration::findOrFail($id);
                break;
            default:
                return response()->json(['message' => 'Invalid type'], 400);
        }

        $item->is_active = $isActive;
        $item->save();

        return response()->json(['message' => 'Status updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feature $features)
    {
        //
    }
}
