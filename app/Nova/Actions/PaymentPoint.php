<?php

namespace App\Nova\Actions;

use Hubertnnn\LaravelNova\Fields\DynamicSelect\DynamicSelect;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class PaymentPoint extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            $buy = $model->buy("{$fields->get('ppob')} to {$fields->get('destination')}", $fields->get('value'));
            switch ($buy) {
                case 1:
                    return Action::message('Pembelian Berhasil');
                case -1:
                    return Action::danger('Pembelian Gagal, Saldo Tidak Mencukupi');
                case 0:
                    return Action::danger('Pembelian Gagal, Terjadi Kesalahan');
            }
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public
    function fields()
    {
        $ppob = collect([
            'Token PLN',
            'Tagihan PLN',
            'Pulsa',
            'Paket Data',
            'OVO',
            'Go-Pay',
            'Linkaja',
        ]);

        $ppob = $ppob->keyBy(function ($item) {
            return Str::slug($item);
        });

        return [
            Select::make('PPOB', 'ppob')
                ->searchable()
                ->options($ppob)
                ->rules('required'),
            Number::make('Nomor Pelanggan', 'destination')
                ->rules('required'),
            Number::make('Nominal', 'value')
                ->rules('required', 'gt:0')
        ];
    }
}
