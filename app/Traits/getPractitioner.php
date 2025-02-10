<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

trait getPractitioner
{
    public static function handle($request): int
    {
        //Este Store procedure se encarga de traerme el id del practicante
        $procedure = 'CALL usp_practitioners_return_id(:_document_id,:_country_id,:_applier_document_number)';

        $parameters = [
            '_document_id' => $request->document_type_id,
            '_country_id' => $request->country_id,
            '_applier_document_number' => $request->dni,
        ];

        try {
            $practitioner_id = collect(DB::select($procedure, $parameters))->first()->practitioner_id;
        } catch (QueryException $qe) {
            $response = HttpResponseHelper::make()
                ->internalErrorResponse('Error al intentar obtener el ID del practicante')
                ->send();
            throw new HttpResponseException($response);
        } catch (Exception $e) {
            $response = HttpResponseHelper::make()
                ->notFoundResponse('No se encontró el practicante')
                ->send();
            throw new HttpResponseException($response);
        }

        return $practitioner_id;
    }
}
