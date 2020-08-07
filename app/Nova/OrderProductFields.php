<?php

namespace App\Nova;

use Laravel\Nova\Fields\Text;

class OrderProductFields
{   


    /**
     * Get the pivot fields for the relationship.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            Text::make('Quantity'),
            Text::make('Cost'),
            Text::make('Total'),
        ];
    }

}
