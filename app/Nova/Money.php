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
use App\Nova\Metrics\IncomeMoney;
use App\Nova\Metrics\OutcomeMoney;
use App\Nova\Metrics\ProfitMoney;
use Laravel\Nova\Fields\DateTime;

class Money extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Money';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';
    public static $description = 'description';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'type',
        'amout',
        'description'
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
            Text::make('Amout')->rules('required', 'numeric'),
            Textarea::make('Description','description'),
            Select::make('Type')
                ->options([
                    'income' => 'Income',
                    'debit' => 'Debit',
                    'outcome' => 'Outcome'
                ]),   
            DateTime::make('Created', 'created_at')->format('DD MMM YYYY'),
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
        return [
            new IncomeMoney,
            new OutcomeMoney,
            new ProfitMoney


        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
        ];
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

        ];
    }
}
