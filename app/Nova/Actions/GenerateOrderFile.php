<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateOrderFile extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;
    public $onlyOnDetail = true;
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $order = $models[0];

        $date = date('Y-m-d-H-i-s');
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/documents/shartnoma.docx'));
        $templateProcessor->setValue('user_name', $order->name);
        $templateProcessor->setValue('address', $order->address);
        $templateProcessor->setValue('phone', $order->phone);
        $templateProcessor->setValue('date', $order->created_at);
        $templateProcessor->setValue('payment_type', $order->payment_type);
        $templateProcessor->setValue('payment_status', $order->payment_status);
        $templateProcessor->setValue('order_id', $order->id);
        $products = $order->products;
        $count = count($products);
        $templateProcessor->cloneRow('product_id', $count);
        $overal = 0;
        for ($i=1; $i <= $count; $i++) { 
            $templateProcessor->setValue('product_id#' . $i, $products[$i-1]->id);
            $templateProcessor->setValue('product_name#' . $i, $products[$i-1]->title);
            $templateProcessor->setValue('quantity#' . $i, $products[$i-1]->pivot->quantity);
            $templateProcessor->setValue('cost#' . $i, $products[$i-1]->pivot->cost);
            $templateProcessor->setValue('total#' . $i, $products[$i-1]->pivot->total);
            $overal += $products[$i-1]->pivot->total;
        }
        $templateProcessor->setValue('overal', $overal);

        $file = "{$order->id}-{$date}-order.docx";
        $url = "\docs\order\\". $file;
        $file_path = "app\public" . $url;
        
        $templateProcessor->saveAs(storage_path($file_path));
        return Action::download(url('storage/'.$url), $file);
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
