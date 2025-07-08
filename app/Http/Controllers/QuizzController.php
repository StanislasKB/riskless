<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Quizz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizzController extends Controller
{
    public function index()
    {
        $quizzs = Quizz::paginate(9);
        return view('global_manager.page.quizz.index', compact('quizzs'));
    }
    public function add_view()
    {
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner')) {
            abort(403);
        }
        $formations = Formation::where('created_by', Auth::id())->get();
        return view('global_manager.page.quizz.create', compact('formations'));
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
            'quizz_img' => ['file', 'mimes:jpeg,png,jpg', 'max:2048'],
            'document' => ['required', 'file', 'mimes:pdf', 'max:4096'],
        ]);
        $formation_id = $request->has('formation') && is_numeric($request->formation)
            ? (int) $request->formation
            : null;
        $quizz = Quizz::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'visibility' => $request->visibility,
            'account_id' => Auth::user()->account->id,
            'created_by' => Auth::id(),
            'formation_id' => $formation_id,
            'img_url' => $request->hasFile('quizz_img') ? $request->file('quizz_img')->store('quizz_imgs', 'public') : null,
            'document_url' => $request->file('document')->store('quizzs', 'public')
        ]);
        return back()->with('success', 'Quizz ajouté avec succès');
    }


    public function update_view($id)
    {
        $quizz = Quizz::findOrFail($id);
        if (Auth::id() == $quizz->creator->id) {
            $formations = Formation::where('created_by', Auth::id())->get();
            return view('global_manager.page.quizz.edit', compact('formations', 'quizz'));
        } else {
            abort(403);
        }
    }
    public function update(Request $request, $id)
    {

        $quizz = Quizz::findOrFail($id);
        if (Auth::id() == $quizz->creator->id) {
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
            if ($request->has('formation') && is_numeric($request->formation)) {
                $updateData['formation_id'] = (int) $request->formation;
            }
            if ($request->has('visibility')) {
                $updateData['visibility'] = $request->visibility;
            }
            if ($request->hasFile('quizz_img')) {
                $updateData['img_url'] = $request->file('quizz_img')->store('quizz_imgs', 'public');
            }
            if ($request->hasFile('document')) {
                $updateData['document_url'] = $request->file('document')->store('quizzs', 'public');
            }
            $quizz->update($updateData);
            return back()->with('success', 'Quizz mis à jour avec succès');
        } else {
            abort(403);
        }
    }

    public function delete($id)
    {
        $quizz = Quizz::findOrFail($id);
        if ($quizz->user_id == Auth::user()->id) {
            $quizz->delete();
            return back()->with('success', 'Quizz supprimée avec succès');
        } else {
            abort(403);
        }
    }
}
