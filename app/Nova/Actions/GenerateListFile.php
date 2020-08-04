<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateListFile extends Action
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
        $ids = $models->pluck('id');
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/documents/list.docx'));
        $orders = \App\Order::whereIn('id', $ids)->whereIn('status', ['0','1'])->with('products')->get();
        $date = date('Y-m-d-H-i-s');
        $products = [];
        foreach ($orders as $order){
            foreach ($order->products as $product){
                $id = $product->id;
                if (isset($products[$id])){
                    $products[$id]['quantity'] += $product->pivot->quantity;
                    $products[$id]['total'] += $product->pivot->total;                
                    $products[$id]['orders'] .= ', #'. $order->id;                
                }else{
                    $products[$id] = [
                        'product' => $product,
                        'quantity' => $product->pivot->quantity,
                        'total' => $product->pivot->total,
                        'orders' => '#' . $order->id,
                        'cost' => $product->pivot->cost,
                        'discount' => 0
                    ];
                }
            }
        }
        $templateProcessor->setValue('date', $date);

        $count = count($products);
        $templateProcessor->cloneRow('product_id', $count);
        $overal = 0;
        $i = 1;
        foreach ($products as $id => $item) { 
            $templateProcessor->setValue('product_id#' . $i, $item['product']->id);
            $templateProcessor->setValue('product_title#' . $i, $item['product']->title);
            $templateProcessor->setValue('orders#' . $i, $item['orders']);
            $templateProcessor->setValue('quantity#' . $i, $item['quantity']);
            $templateProcessor->setValue('cost#' . $i, $item['cost']);
            $templateProcessor->setValue('discount#' . $i, $item['discount']);
            $templateProcessor->setValue('total#' . $i, $item['total']);
            $templateProcessor->setValue('vendor_market#' . $i, $item['product']->vendor_market);
            $overal += $item['total'];
            $i++;
        }
        $templateProcessor->setValue('overal', $overal);
        $templateProcessor->setValue('total_discount', 0);

        $templateProcessor->setValue('tax', 0);

        $file = "{$date}-list.docx";
        $url = "/docs/products/". $file;
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
