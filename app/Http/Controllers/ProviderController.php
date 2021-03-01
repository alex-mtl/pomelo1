<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProviderResource;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    const FIELDS = ['first_name', 'last_name'];

    const INDEX_PARAMS = [
        'id' => ['int'],
        'first_name' => ['string'],
        'last_name' => ['string']
    ];

    /**
     * Display a listing of the providers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where = $this->validate($request, self::INDEX_PARAMS);
        $builder = $this->prepareIndexBuilder($where);
        $providers = $builder->paginate(10);
        return ProviderResource::collection($providers);
    }

    private function prepareIndexBuilder(array $where): Builder {
        $builder = Provider::query();
        foreach ($where as $field => $value) {
            switch ($field) {
                case 'first_name':
                case 'last_name':
                    $builder->where($field, 'LIKE', "%{$value}%");
                    break;
                default:
                    $builder->where($field, '=', $value);
            }
        }
        return $builder;
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
