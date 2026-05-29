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
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'second_last_name' => 'nullable|string|max:255',
                'document_number' => 'required|string|max:20|unique:clients,document_number',
                'phone_number' => 'nullable|string|max:20',
                'email' => 'required|email|max:255|unique:clients,email',
                'address' => 'nullable|string|max:500',
                'birth_date' => 'nullable|date|before:today',
            ], [
                // Mensajes personalizados en español
                'first_name.required' => 'El nombre es obligatorio',
                'last_name.required' => 'El apellido paterno es obligatorio',
                'document_number.required' => 'El documento es obligatorio',
                'document_number.unique' => 'Este documento ya está registrado',
                'email.required' => 'El correo electrónico es obligatorio',
                'email.email' => 'El correo electrónico debe ser válido',
                'email.unique' => 'Este correo electrónico ya está registrado',
                'birth_date.date' => 'La fecha de nacimiento debe ser una fecha válida',
                'birth_date.before' => 'La fecha de nacimiento debe ser anterior a hoy',
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
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'second_last_name' => 'nullable|string|max:255',
                'document_number' => 'sometimes|required|string|max:20|unique:clients,document_number,' . $id,
                'phone_number' => 'nullable|string|max:20',
                'email' => 'sometimes|required|email|max:255|unique:clients,email,' . $id,
                'address' => 'nullable|string|max:500',
                'birth_date' => 'nullable|date|before:today',
            ], [
                // Mensajes personalizados en español
                'first_name.required' => 'El nombre es obligatorio',
                'last_name.required' => 'El apellido paterno es obligatorio',
                'document_number.required' => 'El documento es obligatorio',
                'document_number.unique' => 'Este documento ya está registrado',
                'email.required' => 'El correo electrónico es obligatorio',
                'email.email' => 'El correo electrónico debe ser válido',
                'email.unique' => 'Este correo electrónico ya está registrado',
                'birth_date.date' => 'La fecha de nacimiento debe ser una fecha válida',
                'birth_date.before' => 'La fecha de nacimiento debe ser anterior a hoy',
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