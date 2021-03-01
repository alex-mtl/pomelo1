<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProviderResource;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    const FIELDS = ['first_name', 'last_name'];
    /**
     * Display a listing of the providers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providers = Provider::paginate(10);
        return ProviderResource::collection($providers);
    }

    /**
     * Store a newly created provider in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $patient = Provider::create($request->only(self::FIELDS));
        return new ProviderResource($patient);
    }

    /**
     * Display the specified provider.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $provider = Provider::findOrFail($id);
        return new ProviderResource($provider);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        /** @var Provider $provider */
        $provider = Provider::findOrFail($id);
        $provider->update($request->only(self::FIELDS));
        return new ProviderResource($provider);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /** @var Provider $provider */
        $provider = Provider::findOrFail($id);
        $provider->delete();
        return response('', 204);
    }
}
