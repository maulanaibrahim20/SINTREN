<?php

namespace App\Http\Controllers\Api\Pangan;

use App\Http\Controllers\Controller;
use App\Models\Pangan\SubjenisPangan;
use Illuminate\Http\Request;

class SubjenisPanganController extends Controller
{
    public function getSubjenisPangan()
    {
        $subjenispangan = SubjenisPangan::all();
        $responseData = [
            'status' => 'success',
            'message' => 'Get data successful',
            'subjenis_pangan' => $subjenispangan
        ];
        return response()->json($responseData);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
