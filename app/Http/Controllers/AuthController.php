<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }

  public function signup(Request $request)
  {
    $this->validate($request, [
      'name' => 'required|string',
      'email' => 'required|email|unique:users',
      'password' => 'required|min:8|max:16|confirmed',
      'password_confirmation' => 'required|min:8|max:16',
    ], [
      'name.required' => 'Campo usuário é obrigatório',
      'email.required' => 'Campo email é obrigatório',
      'email.email' => 'Email inválido',
      'email.unique' => 'Email já utilizado',
      'password.required' => 'Campo senha é obrigatório',
      'password.min' => 'Senha deve ter no mínimo 8 caracteres',
      'password.max' => 'Senha deve ter no máximo 16 caracteres',
      'password_confirmation.required' => 'O campo confirmar senha é obrigatório',
      'password_confirmation.min' => 'Confirmar senha deve ter no mínimo 8 caracteres',
      'password_confirmation.max' => 'Confirmar senha deve ter no máximo 16 caracteres',
      'password.confirmed' => 'Senhas não coincidem'
    ]);


    try {
      $user = new User();

      $user->name = $request->input('name');
      $user->email = $request->input('email');
      $plainPassword = $request->input('password');
      $user->password = app('hash')->make($plainPassword);
      $user->save();

      if (!$token = Auth::attempt(['email' => $user->email, 'password' => $plainPassword])) {
        return response()->json(['message' => 'Credenciais inválidas'], 401);
      }

      return response()->json(['user' => $user,'token' => 'Bearer '.$token, 'message' => 'Conta criada com sucesso!!'], 201);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Falha ao criar usuário!!'], 500);
    }
  }

  /**
   * Get a JWT via given credentials.
   *
   * @param  Request  $request
   * @return Response
   */
  public function signin(Request $request)
  {
    $this->validate($request, [
      'email' => 'required|string',
      'password' => 'required|string',
    ], [
      'email.required' => 'Campo email é obrigatório',
      'password.required' => 'Campo senha é obrigatório',
      'password.min' => 'Senha deve ter no mínimo 8 caracteres',
      'password.max' => 'Senha deve ter no máximo 16 caracteres',
    ]);

    $credentials = $request->only(['email', 'password']);

    if (!$token = Auth::attempt($credentials)) {
      return response()->json(['message' => 'Credenciais inválidas'], 401);
    }

    return $this->respondWithToken($token);
  }
}
