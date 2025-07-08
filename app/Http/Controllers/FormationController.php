<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormationController extends Controller
{
    public function index()
    {
        $formations = Formation::paginate(9);
        return view('global_manager.page.formation.index', compact('formations'));
    }
    public function add_view()
    {
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner')) {
            abort(403);
        }
        return view('global_manager.page.formation.create');
    }
    public function add(Request $request)
    {
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner')) {
            abort(403);
        }
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'status' => ['required', 'in:ACTIVE,INACTIVE'],
            'visibility' => ['required', 'in:ALL,ONLY_MEMBERS'],
            'formation_img' => ['file', 'mimes:jpeg,png,jpg', 'max:2048'],
            'document' => ['required', 'file', 'mimes:pdf', 'max:4096'],
        ]);

        $formation = Formation::create([
            'title' => $request->title,
            'status' => $request->status,
            'description' => $request->description,
            'visibility' => $request->visibility,
            'account_id' => Auth::user()->account->id,
            'created_by' => Auth::id(),
            'img_url' => $request->hasFile('formation_img') ? $request->file('formation_img')->store('formation_imgs', 'public') : null,
            'document_url' => $request->file('document')->store('formations', 'public')
        ]);
        return back()->with('success', 'Formation ajoutée avec success');
    }


    public function update_view($id)
    {
        $formation = Formation::findOrFail($id);
        if (Auth::user()->id == $formation->creator->id) {
            return view('global_manager.page.formation.edit', compact('formation'));
        } else {
            abort(403);
        }
    }
    public function update(Request $request, $id)
    {
        $formation = Formation::findOrFail($id);
        if (Auth::user()->id == $formation->creator->id) {
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'status' => ['required', 'in:ACTIVE,INACTIVE'],
                'visibility' => ['required', 'in:ALL,ONLY_MEMBERS'],
                'formation_img' => ['file', 'mimes:jpeg,png,jpg', 'max:2048'],
                'document' => ['file', 'mimes:pdf', 'max:4096'],
            ]);


            $updateData = [];

            if ($request->has('title')) {
                $updateData['title'] = $request->title;
            }
            if ($request->has('description')) {
                $updateData['description'] = $request->description;
            }
            if ($request->has('status')) {
                $updateData['status'] = $request->status;
            }
            if ($request->has('visibility')) {
                $updateData['visibility'] = $request->visibility;
            }
            if ($request->hasFile('formation_img')) {
                $updateData['img_url'] = $request->file('formation_img')->store('formation_imgs', 'public');
            }
            if ($request->hasFile('document')) {
                $updateData['document_url'] = $request->file('document')->store('formations', 'public');
            }
            $formation->update($updateData);
            return back()->with('success', 'Formation mise à jour avec succès');
        } else {
            abort(403);
        }
    }

    public function delete($id)
    {
        $formation = Formation::findOrFail($id);
        if (Auth::user()->id == $formation->creator->id) {
            $formation->delete();
            return back()->with('success', 'Formation supprimée avec succès');
        } else {
            abort(403);
        }
    }
}
