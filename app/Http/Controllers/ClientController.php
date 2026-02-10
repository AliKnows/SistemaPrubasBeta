<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientController extends Controller
{
    
    public function index()
    {
        try {
            $clients = Client::all();
            return response()->json([
                'success' => true,
                'data' => $clients,
                'message' => 'Clientes obtenidos correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los clientes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/clients
     */
    public function store(Request $request)
    {
        try {
            // Validación de datos
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'apellido_paterno' => 'required|string|max:255',
                'apellido_materno' => 'nullable|string|max:255',
                'documento' => 'required|string|max:20|unique:clients,documento',
                'numero_telefonico' => 'nullable|string|max:20',
                'correo_electronico' => 'required|email|max:255|unique:clients,correo_electronico',
                'direccion' => 'nullable|string|max:500',
                'fecha_nacimiento' => 'nullable|date|before:today',
            ], [
                // Mensajes personalizados en español
                'nombre.required' => 'El nombre es obligatorio',
                'apellido_paterno.required' => 'El apellido paterno es obligatorio',
                'documento.required' => 'El documento es obligatorio',
                'documento.unique' => 'Este documento ya está registrado',
                'correo_electronico.required' => 'El correo electrónico es obligatorio',
                'correo_electronico.email' => 'El correo electrónico debe ser válido',
                'correo_electronico.unique' => 'Este correo electrónico ya está registrado',
                'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida',
                'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Crear el cliente
            $client = Client::create($request->all());

            return response()->json([
                'success' => true,
                'data' => $client,
                'message' => 'Cliente creado exitosamente'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/clients/{id}
     */
    public function show(string $id)
    {
        try {
            $client = Client::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $client,
                'message' => 'Cliente encontrado'
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT/PATCH /api/clients/{id}
     */
    public function update(Request $request, string $id)
    {
        try {
            $client = Client::findOrFail($id);

            // Validación de datos
            $validator = Validator::make($request->all(), [
                'nombre' => 'sometimes|required|string|max:255',
                'apellido_paterno' => 'sometimes|required|string|max:255',
                'apellido_materno' => 'nullable|string|max:255',
                'documento' => 'sometimes|required|string|max:20|unique:clients,documento,' . $id,
                'numero_telefonico' => 'nullable|string|max:20',
                'correo_electronico' => 'sometimes|required|email|max:255|unique:clients,correo_electronico,' . $id,
                'direccion' => 'nullable|string|max:500',
                'fecha_nacimiento' => 'nullable|date|before:today',
            ], [
                // Mensajes personalizados en español
                'nombre.required' => 'El nombre es obligatorio',
                'apellido_paterno.required' => 'El apellido paterno es obligatorio',
                'documento.required' => 'El documento es obligatorio',
                'documento.unique' => 'Este documento ya está registrado',
                'correo_electronico.required' => 'El correo electrónico es obligatorio',
                'correo_electronico.email' => 'El correo electrónico debe ser válido',
                'correo_electronico.unique' => 'Este correo electrónico ya está registrado',
                'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida',
                'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Actualizar el cliente
            $client->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $client,
                'message' => 'Cliente actualizado exitosamente'
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/clients/{id}
     */
    public function destroy(string $id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cliente eliminado exitosamente'
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}