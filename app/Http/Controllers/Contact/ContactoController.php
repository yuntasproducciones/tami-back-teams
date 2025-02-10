<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use Illuminate\Http\Request;
use App\Http\Requests\PostContact\PostContacto;
use App\Traits\HttpResponseHelper;
use Illuminate\Http\JsonResponse;

class ContactoController extends Controller
{
    public function createContact(PostContacto $request) : JsonResponse
    {
        try{
            Contacto::create($request->all());

            return HttpResponseHelper::make()
                ->successfulResponse('Contacto creado correctamente')
                ->send();

        }catch(\Exception $e){
            return HttpResponseHelper::make()
                ->internalErrorResponse('Ocurrio un problema al procesar la solicitud.'.
                 $e->getMessage())
                ->send();
        }
    }

    public function showAll() : JsonResponse
    {
        try {
        $contactos = Contacto::all();

        // Verificar si se encontraron contactos
        if ($contactos->isEmpty()) {
            return HttpResponseHelper::make()
                ->successfulResponse('No se encontraron contactos.')
                ->send();
        }

        return HttpResponseHelper::make()
            ->successfulResponse('Contactos obtenidos correctamente.')
            ->setData($contactos)
            ->send();

        } catch (\Exception $e) {
            return HttpResponseHelper::make()
                ->internalErrorResponse('Ocurrió un problema al procesar la solicitud. ' . $e->getMessage())
                ->send();
        }
    }

    public function updateContact(PostContacto $request, Contacto $contacto) : JsonResponse
    {
        try {
            $contacto->update($request->all());
    
            return HttpResponseHelper::make()
                ->successfulResponse('Contacto actualizado correctamente')
                ->send();
    
        } catch (\Exception $e) {
            return HttpResponseHelper::make()
                ->internalErrorResponse('Ocurrió un problema al procesar la solicitud. ' . $e->getMessage())
                ->send();
        }
    }

    public function destroyContact(Contacto $contacto) : JsonResponse
    {
        try {
            $contacto->delete();
    
            return HttpResponseHelper::make()
                ->successfulResponse('Se ha eliminado el contacto correctamente')
                ->send();
    
        } catch (\Exception $e) {
            return HttpResponseHelper::make()
                ->internalErrorResponse('Ocurrió un problema al procesar la solicitud. ' . $e->getMessage())
                ->send();
        }
    }
}
