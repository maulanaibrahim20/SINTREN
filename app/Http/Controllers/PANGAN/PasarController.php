<?php

namespace App\Http\Controllers\PANGAN;
use App\Models\Pasar\Pasar;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\Pasar\CreateRequest;
use App\Http\Requests\Pasar\UpdateRequest;
class PasarController extends Controller
{
    protected $pasar;

    public function __construct(Pasar $pasar)
    {
        $this->pasar = $pasar;
    }
    public function index()
    {
        $data = [
            'title' => 'Data Pasar',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Data Pasar',
            'button_create' => 'Tambah Data Pasar',
            'users' => $this->pasar::orderBy('created_at', 'asc')->get(),
        ];
        return view('pangan.views.pasar.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Data Pasar',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Pasar',
            'breadcrumb_active' => 'Tambah Data Pasar',
        ];
        return view('pangan.views.pasar.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->pasar->create([
                'name' => $request->name,
            ]);
            DB::commit();
            Alert::success('success', 'success Data Pasar Berhasil Ditambahkan!');
            return redirect('/dinas_pangan/data/data_pasar')->with('success', 'Data Pasar Berhasil Ditambahkan');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollback();
            $errorMessage = 'Gagal Menambahkan Data: ' . $e->getMessage();
            Alert::error('Error', $errorMessage);
            return back()->withInput()->withErrors($errorMessage);
        }
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
