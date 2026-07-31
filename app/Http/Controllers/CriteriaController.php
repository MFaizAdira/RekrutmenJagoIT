<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function index()
    {
        $criterias = Criteria::orderBy('code', 'asc')->get();
        return view('hcm.criteria.index', compact('criterias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'weight' => 'required|numeric|min:0|max:100',
        ]);

        $criteria = Criteria::findOrFail($id);
        $criteria->update([
            'weight' => $request->weight / 100 // Simpan ke desimal
        ]);

        return redirect()->back()->with('success', 'Bobot ' . $criteria->name . ' diperbarui.');
    }
}
