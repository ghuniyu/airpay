<?php

namespace App\Nova;

use App\Traits\AirpayOnly;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Transaction extends Resource
{
    use AirpayOnly;

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
    public static $model = \App\Models\Transaction::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

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
            ID::make(__('ID'), 'id')
                ->hideWhenCreating(),
            BelongsTo::make('User', 'user', User::class),
            Select::make('Type')->options([
                'debit' => 'Debit',
                'credit' => 'Credit',
            ])->displayUsingLabels(),
            Number::make('Gross Amount')
                ->displayUsing(function ($amount) {
                    return 'Rp ' . number_format($amount ?? 0);
                }),
            Text::make('Info'),
            Textarea::make('Note'),
            Text::make('Payment Info', 'note'),
            Select::make('Status')
                ->options(['created' => 'Created', 'pending' => 'Pending', 'success' => 'Success', 'completed' => 'Completed', 'canceled' => 'Canceled'])
                ->displayUsingLabels(),
            DateTime::make('Date', 'created_at')
                ->format('D MMM Y, hh:mm:ss')
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
        return [];
    }
}
