<?php

namespace App\Http\Controllers;

use App\Events\contactEvent;
use App\Models\Contact;
use App\Models\User;
use App\Notifications\ContactCreatedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller

{
    public function index()
    {
        return Inertia::render('Contacts/Index', [
            'contacts' => Contact::orderBy('created_at', 'desc')->get()


        ]);
    }

    public function store(Request $request)
    {
        // $user = Auth::user();
        // dd($user);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'country_code' => 'required|string|size:2',
        ]);

        $contact = Contact::create($validated);
        // $user = auth()->user();
        // $user = Auth::user();
        event(new contactEvent($contact));
        // $user->notify(new ContactCreatedNotification($contact));
        return redirect()->back();
    }

    public function destroy(Contact $contact)
    {
        // Suppression du contact
        $contact->delete();

        // Retourner une réponse JSON de succès
        return response()->json([
            'message' => 'Contact supprimé avec succès.'
        ], 200); // 200 signifie que l'opération a été effectuée avec succès
    }

    // Récupérer les contacts en JSON pour l'API
    public function getContacts()
    {
        return response()->json(Contact::orderBy('created_at', 'desc')->get());
    }


    public function update(Request $request, Contact $contact)
    {
        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'country_code' => 'required|string|size:2',
        ]);

        // Mise à jour des informations du contact
        $contact->update($validated);

        // Retourner une réponse JSON après la mise à jour
        return response()->json([
            'message' => 'Contact mis à jour avec succès.',
            'contact' => $contact
        ], 200);
    }
}
