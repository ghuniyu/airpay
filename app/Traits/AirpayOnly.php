<?php

namespace App\Traits;

use Illuminate\Http\Request;

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
        return auth()->user()->role->id == 'airpay';
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
        self::isAirpay();
    }
}
