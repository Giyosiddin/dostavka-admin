<?php

namespace App\Nova;

use Eminiarts\Tabs\Tabs;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Textarea;
use Hnassr\NovaKeyValue\KeyValue;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use App\Nova\Actions\GenerateListFile;
use App\Nova\Actions\GenerateOrderFile;
use App\Nova\Actions\GenerateOrdersFile;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Shaxzodbek\ProductProperty\ProductProperty;

class Order extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Order';

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
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Phone')->rules('required', 'numeric'),
            Text::make('Name')->rules('required'),
            Text::make('Address'),
            Select::make('Status')
                ->options([
                    '0' => 'New',
                    '1' => 'Processing',
                    '2' => 'Complete',
                    '3' => 'Cancelled',
                ])
                ->displayUsingLabels(),   
            Select::make('Payment type')
                ->options([
                    'cash' => 'Naqt pul',
                    'click' => 'Click',
                    'payme' => 'Payme'
                ])
                ->displayUsingLabels(),   
            Select::make('Payment status')
                ->options([
                    'waiting' => 'Kutilmoqda',
                    'processing' => 'Amalga oshirilmoqda',
                    'completed' => 'Yakunlandi'
                ])
                ->displayUsingLabels(),   
            Text::make('overal'),
            BelongsToMany::make('Products')
                ->fields(new OrderProductFields),


        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new GenerateListFile,
            new GenerateOrdersFile,
            new GenerateOrderFile
        ];
    }
}
