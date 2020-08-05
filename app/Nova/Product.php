<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Hnassr\NovaKeyValue\KeyValue;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Eminiarts\Tabs\Tabs;
use Shaxzodbek\ProductProperty\ProductProperty;
use App\Nova\Actions\NotifyTelegramChanel;
class Product extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Product';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title',
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
            
            new Tabs('Tabs', [
              'Main'    => [
                    ID::make()->sortable(),
                    Images::make('Gallary')
                      ->conversionOnIndexView('thumb')
                      ->autofill(),
                    Text::make('Title')
                    ->sortable()
                    ->rules('required', 'max:255')
                    ->autofill(),
                    Textarea::make('Description'),
                    Text::make('Cost')
                    ->rules('required', 'numeric')
                    ->autofill()
                    ->hideFromIndex(function (ResourceIndexRequest $request) {
                        return $request->viaRelationship();
                    }),
                    Text::make('Count')
                    ->rules('required', 'numeric')
                    ->autofill(),
                    Text::make('Order')
                    ->rules('required', 'numeric')
                    ->hideWhenCreating()
                    ->hideFromIndex(function (ResourceIndexRequest $request) {
                        return $request->viaRelationship();
                    }),
                    BelongsTo::make('Category'),     
                    Text::make('Vendor market')
                        ->hideWhenCreating(),
                    Text::make('Telegram', 'telegram_notification_id')
                        ->hideWhenCreating(),
                    BelongsToMany::make('orders')
                        ->fields(new OrderProductFields),
                ],
          ]),
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
            new NotifyTelegramChanel
        ];
    }
}
