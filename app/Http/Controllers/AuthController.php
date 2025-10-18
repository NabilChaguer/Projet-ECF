<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function afficherFormulaireLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mot_de_passe' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->mot_de_passe])) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Connexion réussie ✅');
        }

        return back()->withErrors([
            'email' => 'Les identifiants sont incorrects.',
        ]);
    }

    public function afficherFormulaireRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'pseudo' => 'required|string|min:3|max:50|unique:utilisateurs,pseudo',
            'email' => 'required|email|unique:utilisateurs,email',
            'mot_de_passe' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
        ], [
            'mot_de_passe.regex' => 'Le mot de passe doit contenir une majuscule, une minuscule, un chiffre et un symbole.',
            'pseudo.unique' => 'Ce pseudo est déjà utilisé.',
            'email.unique' => 'Cet email est déjà enregistré.',
        ]);

        Utilisateur::create([
            'pseudo' => $request->pseudo,
            'email' => $request->email,
            'password' => Hash::make($request->mot_de_passe),
            'credit' => 20,
            'nom' => '',
            'prenom' => '',
            'telephone' => '',
            'adresse' => '',
            'date_naissance' => now(),
        ]);

        return redirect()->route('login.formulaire')->with('success', 'Compte créé avec succès ! Vous bénéficiez de 20 crédits 🎉');
    }

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.formulaire')->with('success', 'Vous êtes maintenant déconnecté 👋');
    }
}
