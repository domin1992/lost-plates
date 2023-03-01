<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'plate_id',
        'type',
        'lat',
        'lng',
        'radius',
        'phone_number_prefix_id',
        'phone_number',
        'email',
        'additional_info',
        'notify_when_found',
    ];

    public function plate()
    {
        return $this->belongsTo(Plate::class, 'plate_id', 'id');
    }

    public function phoneNumberPrefix()
    {
        return $this->belongsTo(PhoneNumberPrefix::class, 'phone_number_prefix_id', 'id');
    }

    public function markerMedia()
    {
        return $this->hasMany(MarkerMedia::class);
    }

    public function getPhoneNumberPrefixFormatted()
    {
        $phoneNumberPrefix = $this->phoneNumberPrefix()->first();
        return (null != $phoneNumberPrefix ? '+'.$phoneNumberPrefix->number : '');
    }

    public function hiddenPhoneNumber()
    {
        if(null == $this->phone_number){
            return null;
        }
        $phoneNumber = substr($this->phone_number, 0, 3);
        return $this->getPhoneNumberPrefixFormatted().$phoneNumber.'xxxxxx';
    }

    public function formattedPhoneNumber()
    {
        if(null == $this->phone_number){
            return null;
        }
        return $this->getPhoneNumberPrefixFormatted().$this->phone_number;
    }

    public function hiddenEmail()
    {
        if(null == $this->email){
            return null;
        }
        $email = substr($this->email, 0, 3);
        return $email.'xxxxxxxxxx';
    }
}
