<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Libraries\Tools;
use App\Models\Marker;
use App\Models\MarkerMedia;
use App\Models\Plate;

class MarkerService
{
    public function store(Request $request)
    {
        $plateNumber = Tools::formatPlateNumber($request->plate_number);
        if(null == ($plate = Plate::where('number', $plateNumber)->first())){
            $plate = Plate::create([
                'number' => $plateNumber,
            ]);
        }

        $marker = Marker::where([
            ['plate_id', '=', $plate->id],
            ['type', '=', $request->type],
        ])->first();

        if(null != $marker){
            throw new \Exception('Tablice rejestracyjne o podanym numerze zostały już dodane.');
        }

        $marker = Marker::create([
            'plate_id' => $plate->id,
            'type' => $request->type,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'radius' => ($request->radius != null ? str_replace(',', '.', $request->radius) : null),
            'phone_number_prefix_id' => 1,
            'phone_number' => ($request->phone_number != null ? Tools::simplifyPhoneNumber($request->phone_number) : null),
            'email' => $request->email,
            'additional_info' => $request->additional_info,
            'notify_when_found' => ('on' == $request->notify_when_found),
        ]);

        if(null != $request->media && is_array($request->media) && count($request->media) > 0){
            foreach($request->media as $key => $mediaId){
                if($key < 5){
                    MarkerMedia::create([
                        'marker_id' => $marker->id,
                        'media_id' => $mediaId,
                    ]);
                }
            }
        }

        return true;
    }
}