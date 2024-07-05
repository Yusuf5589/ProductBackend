<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\Isset_;

class ProductRepository{

    public function valError(){
        return[
            "id.required" => "Id kısmını boş bırakamazsınız.",
            "id.int" => "Id kısmı sayısal olmalıdır.",
            "id.exists" => "Böyle bi id bulunmuyor.",
            "name.required" => "İsim kısmını boş bırakamazsınız.",
            "name.max" => "İsim en fazla 50 karakter olabilir.",
            "description.required" => "Açıklama kısmını boş bırakamazsınız.",
            "description.max" => "Açıklama en fazla 150 karakter olabilir.",
            "piece.required" => "Adet kısmını boş bırakamazsınız.",
            "piece.int" => "Adet kısmı sayısal olmalıdır.",
            "price.required" => "Fiyat kısmını boş bırakamazsınız.",
            "price.int" => "Fiyat kısmı sayısal olmalıdır.",
            "productId.required" => "Ürün ID'si kısmını boş bırakamazsınız.",
            "productId.int" => "Ürün ID'si sayısal olmalıdır.",
            "categoryId.required" => "Kategori ID'si kısmını boş bırakamazsınız.",
            "categoryId.int" => "Kategori ID'si sayısal olmalıdır.",
        ];
    }


    public function createProductRep(array $data){
        Product::create([
            "name" => $data["name"],
            "description" => $data["description"],
            "piece" => $data["piece"],
            "price" => $data["price"],
            "productId" => $data["productId"],
            "categoryId" => $data["categoryId"],
        ]);

    }
    

    public function deleteProductRep($id){
        Product::where("id", $id)->delete();
    }    
    
    public function productAllRep(){
        Cache::put("products", Product::get() , 60*60);
        return Cache::get("products");
    }

    public function updateProductRep(array $data){
        return Product::whereId($data["id"])->update([
            "name" => $data["name"],
            "description" => $data["description"],
            "piece" => $data["piece"],
            "price" => $data["price"],
            "productId" => $data["productId"],
            "categoryId" => $data["categoryId"],
        ]);
    }



    public function productFindRep($id){
        $product = Product::whereId($id)->first();
        if(isset($product->id)){
            if(Cache::has("product{$id}")){
                $product = Cache::get("product{$id}");
                return $product;
            }else{
                Cache::put("product{$id}", Product::whereId($id)->first(), 60*60);
                $product = Cache::get("product{$id}");
                return $product;
        }
        }else{
            return response()->json([
                "status"=>"error",
                "message"=> "Böyle bi ürün bulunmuyor"
            ]);
        }
    }

}