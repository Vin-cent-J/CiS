<?php

namespace App\Http\Controllers;

use App\Models\Feature;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feature $features)
    {
        //
    }
}
