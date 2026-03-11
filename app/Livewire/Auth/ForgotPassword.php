<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class ForgotPassword extends Component
{
    public string $email = '';

    protected $layout = 'layouts.app';

    public function submit()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo válido.',
            'email.exists' => 'El correo no está registrado.',
        ]);

        $token = Str::random(64);

// Guarda el token en la tabla password_resets
DB::table('password_reset_tokens')->updateOrInsert(
    ['email' => $this->email],
    [
        'token' => Hash::make($token),
        'created_at' => now()
    ]
);



$url = url(route('password.reset', ['token' => $token, 'email' => $this->email], false));

Mail::to($this->email)->send(new ResetPasswordMail($url));
        session()->flash('status', 'Hemos enviado un enlace para restablecer tu contraseña.');
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
