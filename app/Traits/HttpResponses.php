<?php 

    namespace App\Traits;

    trait HttpResponses {
        protected function success($data, $message=null, $code=200){
            return response()->json([
                "status"=>"La petición fue satisfactoria.",
                "mensaje"=>$message,
                "data"=>$data,
            ], $code);
        }

        protected function error($data, $message=null, $code){
            return response()->json([
                "status"=>"Ocurrió un error.",
                "mensaje"=>$message,
                "data"=>$data,
            ], $code);
        }
    }

?>