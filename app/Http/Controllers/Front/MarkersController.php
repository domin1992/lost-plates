<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marker;
use App\Rules\PhoneNumberValidationRule;
use App\Services\MarkerService;
use App\Transformers\MarkerTransformer;
use DB;

class MarkersController extends Controller
{
    public function ajaxStore(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|string|in:lost,found',
            'plate_number' => 'required|string|min:3',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        if('lost' === $request->type){
            $this->validate($request, [
                'phone_number' => ['required', new PhoneNumberValidationRule],
            ]);

            if(null != $request->radius && '' != $request->radius){
                $request['radius'] = str_replace(',', '.', $request['radius']);
                $this->validate($request, [
                    'radius' => 'numeric',
                ]);
            }

            if(null != $request->notify_when_found && false !== $request->notify_when_found){
                $this->validate($request, [
                    'email' => 'required|email',
                ]);
            }
        }
        elseif('found' === $request->type){
            if(null != $request->email){
                $this->validate($request, [
                    'email' => 'email',
                ]);
            }
            if(null != $request->phone_number){
                $this->validate($request, [
                    'phone_number' => [new PhoneNumberValidationRule],
                ]);
            }
        }

        $markerService = new MarkerService;
        try{
            $markerService->store($request);
            return response()->json([
                'message' => 'Pineska została dodana do mapy.',
            ]);
        }
        catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function ajaxIndex(Request $request)
    {
        $results = DB::table('markers as m')
            ->join('plates as p', 'p.id', '=', 'm.plate_id');

        if($request->plate_number != null && $request->plate_number != ''){
            $results = $results->where('p.number', 'LIKE', '%'.$request->plate_number.'%');
        }

        $results = $results->pluck('m.id');
        $markersCollection = Marker::whereIn('id', $results)->get();
        $markers = fractal($markersCollection, new MarkerTransformer)
            ->toArray();

        return response()->json([
            'markers' => $markers,
        ]);
    }

    public function ajaxGetPhoneNumber(Request $request, $id)
    {
        $marker = Marker::findOrFail($id);

        return response()->json([
            'phone_number' => $marker->formattedPhoneNumber(),
        ]);
    }

    public function ajaxGetEmail(Request $request, $id)
    {
        $marker = Marker::findOrFail($id);

        return response()->json([
            'email' => $marker->email,
        ]);
    }
}
