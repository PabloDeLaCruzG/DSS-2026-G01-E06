<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ProfessionalProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfessionalProfileController extends Controller
{
    public function create()
    {
        $user = Auth::user();

        if ($user->professionalProfile) {
            return redirect()->route('profile.index')
                ->with('info', 'Ya tienes una solicitud de vendedor profesional registrada.');
        }

        return view('user.professional.form');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->professionalProfile) {
            return redirect()->route('profile.index')
                ->with('info', 'Ya tienes una solicitud registrada.');
        }

        $request->validate([
            'cif'               => 'required|string|max:20|unique:professional_profiles,cif',
            'company_name'      => 'required|string|max:255',
            'website'           => 'nullable|url|max:255',
            'verification_docs' => 'required|file|mimes:pdf|max:5120',
        ], [
            'cif.unique'               => 'Este CIF ya está registrado en la plataforma.',
            'verification_docs.mimes'  => 'El documento debe ser un archivo PDF.',
            'verification_docs.max'    => 'El PDF no puede superar los 5 MB.',
        ]);

        $path = $request->file('verification_docs')->store('docs', 'public');

        ProfessionalProfile::create([
            'user_id'          => Auth::id(),
            'cif'              => $request->cif,
            'company_name'     => $request->company_name,
            'website'          => $request->website,
            'verification_docs'=> $path,
            'is_verified'      => false,
        ]);

        return redirect()->route('profile.index')
            ->with('success', '¡Solicitud enviada! Un administrador revisará tu documentación.');
    }
}
