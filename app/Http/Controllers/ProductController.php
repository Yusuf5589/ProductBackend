<?php

namespace App\Http\Controllers;

use App\Jobs\ReportMailJob;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $data;
    public function __construct(ProductRepository $productRepository){
        $this->data = $productRepository;
    }

    
    public function createProduct(Request $req){
        $req->validate([
            "name" => "required|max:50",
            "description" => "required|max:150",
            "piece" => "required|int",
            "price" => "required|int",
            "productId" => "required|int",
            "categoryId" => "required|int",
        ], $this->data->valError());

        try {
            
            $this->data->createProductRep($req->all());
            
            return response()->json([
                "status" => "success",
                "message" => "Başarıyla ürün kaydedildi.",
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                "status"=> "error",
                "message"=> $th->getMessage(),
            ]);
        }

    }



    public function deleteProduct($id){
        try {

            $this->data->deleteProductRep($id);
            return response()->json([
                "status" => "success",
                "message" => "Başarıyla ürün silindi.",
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status"=> "error",
                "message"=> $th->getMessage(),
            ]);
        }
    }


    public function productAll(){
        try {
            $prductall = $this->data->productAllRep();
            return response()->json($prductall);
        } catch (\Throwable $th) {
            return response()->json([
                "status"=> "error",
                "message"=> $th->getMessage(),
            ]);
        }
    }



    public function updateProduct(Request $req){

        $req->validate([
            "id" => "required|int|exists:product,id",
            "name" => "required|max:50",
            "description" => "required|max:150",
            "piece" => "required|int",
            "price" => "required|int",
            "productId" => "required|int",
            "categoryId" => "required|int",
        ], $this->data->valError());

        try {
            $this->data->updateProductRep($req->all());

            return response()->json([
                "status" => "success",
                "message" => "Ürünler güncellendi.",
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status"=> "error",
                "message"=> $th->getMessage(),
            ]);
        }
        
    }



    public function productFind($id){
        try {
            return $this->data->productFindRep($id);
        } catch (\Throwable $th) {
            return response()->json([
                "status"=> "error",
                "message"=> $th->getMessage(),
            ]);
        }
    }



}
