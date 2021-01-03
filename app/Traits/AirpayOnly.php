<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait AirpayOnly
{
    public function getId()
    {
        return null;
    }

    public function mine()
    {
        return $this->getId() === auth()->id();
    }

    public static function isAirpay()
    {
        return Str::lower(auth()->user()->role->id) == 'airpay';
    }

    public function authorizedToView(Request $request)
    {
        return self::mine() || self::isAirpay();
    }

    public function authorizedToUpdate(Request $request)
    {
        return self::mine() ||self::isAirpay();
    }

    public function authorizedToDelete(Request $request)
    {
        return self::isAirpay();
    }

    public static function authorizedToCreate(Request $request)
    {
        return self::isAirpay();
    }
}
