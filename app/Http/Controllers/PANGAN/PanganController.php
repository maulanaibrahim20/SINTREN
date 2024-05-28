<?php

namespace App\Http\Controllers\PANGAN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pasar\Pasar;
use App\Models\Pangan\Pangan;

class PanganController extends Controller
{

    protected $pasar;
    protected $pangan;

    public function __construct(Pasar $pasar, Pangan $pangan)
    {
        $this->pasar = $pasar;
        $this->pangan = $pangan;
    }

    public function kirimkan(Request $request)
    {
        try {
            $this->pangan::where('id', $request->id)->update([
                'status' => 'terkirim',
            ]);
            Alert::success('success', 'Success Data Berhasil Dikirimkan!');
            return back()->with('success', 'Data Berhasil DiKirimkan!');
        } catch (\Exception $e) {
            Alert::error('error', 'Error' . $e->getMessage());
            return back()->with('error' . $e->getMessage());
        }
    }

    public function index()
    {
        $data = [
            'pangan'
        ]
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
