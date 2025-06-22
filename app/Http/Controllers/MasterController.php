<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $masterTables =  DB::select("SHOW TABLES LIKE '%_masters'");
        if (!empty($masterTables)) {
            $columnName = 'Tables_in_' . DB::getDatabaseName() . ' (%_masters)';
            $masterTables = array_column($masterTables, $columnName);
        }
        return view('master.view', compact('masterTables'));
    }

    protected function getModelFromTable($table)
    {
        return match ($table) {
            'role_masters' => \App\Models\RoleMaster::class,
            'status_masters' => \App\Models\StatusMaster::class,
            'test_masters'  =>  \App\Models\TestMaster::class,
            default => null,
        };
    }

    public function getTableData(Request $request)
    {
        $table = $request->table;
        $search = $request->search ?? '';
        $perPage = 5;

        // Get dynamic columns (excluding system ones)
        $columns = Schema::getColumnListing($table);
        $exclude = ['id', 'created_at', 'updated_at', 'deleted_at', 'created_by'];
        $displayColumns = array_values(array_diff($columns, $exclude));

        $query = DB::table($table)->whereNull('deleted_at');

        $data = $query->paginate($perPage);

        return response()->json([
            'data' => $data->items(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'columns' => $displayColumns,
        ]);
    }

    public function fetchRecord(Request $request)
    {
        $table = $request->table;
        $id = $request->id;

        $record = DB::table($table)->where('id', $id)->first();

        return response()->json($record);
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
        $table = $request->input('table');
        $modelClass = $this->getModelFromTable($table);

        if (!$modelClass) {
            return response()->json(['error' => 'Invalid table.'], 400);
        }

        $data = collect($request->all())
            ->except(['table', '_token', '_method', 'id'])
            ->toArray();

        if ($table === 'role_masters') {
            $data['created_by'] = auth()->id();
        }

        $modelClass::create($data);

        return response()->json(['success' => true]);
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
    public function update(Request $request, $id)
    {
        $table = $request->input('table');
        $modelClass = $this->getModelFromTable($table);

        if (!$modelClass) {
            return response()->json(['error' => 'Invalid table.'], 400);
        }

        $data = collect($request->all())
            ->except(['table', '_token', '_method', 'id'])
            ->toArray();

        $record = $modelClass::findOrFail($id);
        $record->update($data);

        return response()->json(['success' => true]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $table = $request->input('table');
        $modelClass = $this->getModelFromTable($table);

        if (!$modelClass) {
            return response()->json(['error' => 'Invalid table.'], 400);
        }

        $record = $modelClass::findOrFail($id);
        $record->delete();

        return response()->json(['success' => true]);
    }
}
