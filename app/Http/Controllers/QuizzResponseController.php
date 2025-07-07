<?php

namespace App\Http\Controllers;

use App\Models\Quizz;
use App\Models\QuizzResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizzResponseController extends Controller
{
   public function index(Request $request)
    {

        $results = Auth::user()->quizzResponses()->get();
        return view('dashboard.pages.responses.index', compact('results'));
    }




    public function submit_response(Request $request, $id)
    {
        $request->validate([
            'document' => ['required', 'file', 'mimes:pdf', 'max:4096'],
        ]);
        $quizz = Quizz::findOrFail($id);
        $response = QuizzResponse::create([
            'status' => 'IN_PROGRESS',
            'user_id' => Auth::user()->id,
            'quizz_id' => $quizz->id,
            'document_url' => $request->file('document')->store('quizz_responses', 'public')
        ]);
        return back()->with('success', 'Vos réponses ont été soumises. Vous aurez la note bientôt');
    }


    public function submit_score(Request $request, $id)
    {
        $request->validate([
            'score' => ['required', 'integer']
        ]);

        $response = QuizzResponse::findOrFail($id);
        if ( $response->quizz->creator->id== Auth::id()) {
            $response->update([
                'score' => $request->score,
                'status' => 'VALIDATED'
            ]);
            return back()->with('success', 'Le score a été mis à jour');
        } else {
            abort(403);
        }
       
    }
}
