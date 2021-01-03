<?php

namespace App\Nova;

use App\Nova\Actions\RefreshApiKey;
use App\Traits\AirpayOnly;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class ApiKey extends Resource
{
    use AirpayOnly;

    public function getId()
    {
        return $this['id'];
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        if (!self::isAirpay())
            return $query->where('id', auth()->id());

        return parent::indexQuery($request, $query);
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            /*ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make('Role', 'role', Role::class)
                ->readonly(!self::isAirpay()),*/
            Text::make('Name')
                ->sortable()
                ->hideWhenUpdating(!self::isAirpay())
                ->hideWhenCreating(!self::isAirpay())
                ->readonly(!self::isAirpay()),
            Text::make('Api Key', 'api_token')
                ->hideWhenUpdating(!self::isAirpay())
                ->hideWhenCreating(!self::isAirpay())
                ->readonly(!self::isAirpay()),
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
            RefreshApiKey::make()
        ];
    }
}
