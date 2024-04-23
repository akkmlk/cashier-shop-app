<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return view('user.profile', [
            'user' => $request->user(),
        ]);
    }

    public function edit(Request $request)
    {
        return view('user.profile-edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255'],
            'username' => ['required', 'max:255', 'unique:users,username,' . $request->user()->id],
            'password' => ['nullable', 'max:255', 'min:8'],
        ]);

        if ($request->password) {
            $request->merge([
                'password' => bcrypt($request->password),
            ]);

            $request->user()->update($request->all());
            return redirect()->route('profile.show')->with('update', 'success');
        } else {
            $request->user()->update($request->all());
            return redirect()->route('profile.show')->with('update', 'success');
        }
    }
}
