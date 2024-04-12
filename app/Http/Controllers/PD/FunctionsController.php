<?php

namespace App\Http\Controllers\PD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\PD;
use App\Models\Deal;
use App\Models\Product;

class FunctionsController extends Controller
{
// Load deals and its products from Pipedrive using API call    
    function loadDealsProductsPD($id=false) {

        $methods = new MethodsController;
        $start=0;
        $limit=50;
        do {
            $deals=$methods->getDeals($id,$start,$limit);
            if (isset($deals['data'])){
                if ($id){
                    $tmp[0]=$deals['data'];
                    $deals['data']=$tmp;
                }
                foreach ($deals['data'] as $deal) {
                    echo $deal['id']."|".$deal['title'];
                    $products=$methods->getDealProducts($deal['id']);
                    if (isset($products['data']))
                      foreach ($products['data'] as $product)
                        echo("\n>> ".$product['id']."|".$product['name']."|".$product['item_price']."|".$product['duration']."|".$product['quantity']."|".$product['sum']);
                    echo "\n";
                }  
            }
            $start+=$limit;
        }while (isset($deals['data'])&&!$id);
    }

// Write Deals into Integrator's database    
    function writeDeals($reset=false)
    {
        $methods = new MethodsController;

        $start=0;  // From which page will starts
        $limit=50; // How many deals loads per page
        $from=0;   // Insert only deal ids higher than $from

        if ($reset){
            Product::query()->delete();
            Deal::query()->delete();
        }
        else
            $from=Deal::max('id') ?? 0; //if $from is null then 0

        $dealsPD=$methods->countDeals();
        $dealsIN=Deal::count();

        if ($dealsPD==$dealsIN)
          return;

        do {
            $deals=$methods->getDeals(false,$start,$limit);
            if (isset($deals['data']))
                foreach ($deals['data'] as $deal)
                    if ($deal['id']>$from){
                        $new_Deal = new Deal();

                        $new_Deal->id=$deal['id'];
                        $new_Deal->title=$deal['title'];
                        $new_Deal->active=$deal['active'];
                        $new_Deal->status=$deal['status'];
                        $new_Deal->add_Time=$deal['add_time'];

                        $new_Deal->save();   
                        
                        unset($new_Deal);
                    }  
            $start+=$limit;
        }while (isset($deals['data']));

    }


    // Write Products into Integrator's database    
    function writeProducts($reset=false)
    {
        $methods = new MethodsController;
        $table = new Deal;

        if ($reset){
            Product::query()->delete();
            $table->update(['products'=>null]);
        }
        
        $deals=$table->query()->whereNull('products')->get();
//echo "<pre>";
//print_r($deals);
        foreach ($deals as $deal) {
            $total_products=0;
            $products=$methods->getDealProducts($deal->id);
            if (isset($products['data'])){
                Product::where('deal_id',$deal->id)->delete();
                foreach ($products['data'] as $product) {
                    $new_Product = new Product();
                    $total_products++;

                    $new_Product->id=$product['id'];
                    $new_Product->deal_id=$deal->id;
                    $new_Product->name=$product['name'] ?? '(no name)';
                    $new_Product->item_price=$product['item_price'];
                    $new_Product->duration=$product['duration'];
                    $new_Product->quantity=$product['quantity'];
                    $new_Product->sum=$product['sum'];
                    $new_Product->add_time=$product['add_time'];

                    $new_Product->save();   

                    unset($new_Product);
                }
            }
            Deal::where('id','=',$deal->id)->update(['products'=>$total_products]);
        }

    }

}
