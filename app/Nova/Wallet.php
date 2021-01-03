<?php

namespace App\Nova;

use App\Nova\Actions\PaymentPoint;
use App\Nova\Actions\Withdraw;
use App\Traits\AirpayOnly;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class Wallet extends Resource
{
    use AirpayOnly;

    public function getId()
    {
        return $this['user']['id'];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        if (self::isAirpay())
            return parent::indexQuery($request, $query);
        else
            return $query->whereUserId(auth()->id());
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Wallet::class;

    public function title()
    {
        return $this->user->name . " Wallet";
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
            BelongsTo::make('User', 'user', User::class)
                ->hideWhenCreating()
                ->hideWhenUpdating(),
            Number::make('Balance')
                ->displayUsing(function ($amount) {
                    return 'Rp ' . number_format($amount);
                })
                ->hideWhenCreating()
                ->hideWhenUpdating(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            Withdraw::make(),
            PaymentPoint::make()
        ];
    }
}
