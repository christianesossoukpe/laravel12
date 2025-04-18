<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
 
    {
        public function index()
        {
            return Inertia::render('Contacts/Index', [
                'contacts' => Contact::all()


            ]);
        }
    
        public function store(Request $request)
        {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'company' => 'required|string|max:255',
                'job_title' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'country_code' => 'required|string|size:2',
            ]);
    
            Contact::create($validated);
    
            return redirect()->back();
        }
    
        public function destroy(Contact $contact)
        {
            $contact->delete();
            
            return redirect()->back();
        }
 
           // Récupérer les contacts en JSON pour l'API
    public function getContacts()
    {
        return response()->json(Contact::all());
    }
}
