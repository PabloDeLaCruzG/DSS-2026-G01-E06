<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfessionalProfile;

class ProfessionalProfileController extends Controller
{
    public function index()
    {
        $pending  = ProfessionalProfile::with('user')->where('is_verified', false)->latest()->get();
        $verified = ProfessionalProfile::with('user')->where('is_verified', true)->latest()->get();

        return view('admin.professionals.index', compact('pending', 'verified'));
    }

    public function verify(ProfessionalProfile $profile)
    {
        $profile->update(['is_verified' => true]);

        return back()->with('success', "Perfil de {$profile->company_name} verificado correctamente.");
    }

    public function reject(ProfessionalProfile $profile)
    {
        $name = $profile->company_name;
        $profile->delete();

        return back()->with('success', "Solicitud de {$name} rechazada y eliminada.");
    }
}
