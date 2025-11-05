<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contact::query();

        // Filtro por nombre
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filtro por teléfono
        if ($request->filled('phone')) {
            $query->where(function($q) use ($request) {
                $q->where('phone', 'like', '%' . $request->phone . '%')
                  ->orWhere('whatsapp', 'like', '%' . $request->phone . '%');
            });
        }

        // Filtro por correo
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        $contacts = $query->latest()->paginate(12)->withQueryString();
        
        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'maps_url' => 'nullable|url',
            'nit' => 'nullable|string|max:50',
            'dpi' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('contacts', 'public');
        }

        Contact::create($validated);

        return redirect()->route('contacts.index')->with('success', 'Contacto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        return view('contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'maps_url' => 'nullable|url',
            'nit' => 'nullable|string|max:50',
            'dpi' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Eliminar foto anterior si existe
            if ($contact->photo) {
                Storage::disk('public')->delete($contact->photo);
            }
            $validated['photo'] = $request->file('photo')->store('contacts', 'public');
        }

        $contact->update($validated);

        return redirect()->route('contacts.index')->with('success', 'Contacto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        // Eliminar foto si existe
        if ($contact->photo) {
            Storage::disk('public')->delete($contact->photo);
        }

        $contact->delete();

        return redirect()->route('contacts.index')->with('success', 'Contacto eliminado exitosamente.');
    }
}
