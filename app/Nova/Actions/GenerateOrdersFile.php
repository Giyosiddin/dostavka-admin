<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateOrdersFile extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $onlyOnIndex = true;
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $orders = $models;

        $date = date('Y-m-d-H-i-s');
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/documents/buyurtmalar.docx'));
        
        $count = count($orders);
        $templateProcessor->cloneRow('id', $count);
        $overal = 0;
        for ($i=1; $i <= $count; $i++) { 
            $products = $orders[$i-1]->products;
            $products_name = '';
            foreach ($products as $product){
                $products_name .= $product->title;
            }
            $templateProcessor->setValue('id#' . $i, $orders[$i-1]->id);
            $templateProcessor->setValue('order_products#'. $i, $products_name);
            $templateProcessor->setValue('phone#'. $i, $orders[$i-1]->phone);
            $templateProcessor->setValue('name#'. $i, $orders[$i-1]->name);
            $templateProcessor->setValue('address#'. $i, $orders[$i-1]->address);
            $templateProcessor->setValue('total#'. $i, $orders[$i-1]->overal);
            $templateProcessor->setValue('payment_type#'. $i, $orders[$i-1]->payment_type);
            $templateProcessor->setValue('payment_status#'. $i, $orders[$i-1]->payment_status);
            $overal += $orders[$i-1]->overal;
        }
        $templateProcessor->setValue('overal', $overal);

        $file = "{$date}-orders.docx";
        $url = "/docs/orders/". $file;
        $file_path = "app/public" . $url;
        
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
