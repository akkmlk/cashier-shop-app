<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $users = User::query()->when($search, function($q, $search) {
            return $q->where('name', 'like', "%$search%")
                ->orWhere('username', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        })
        ->orderBy('id')
        ->paginate(5);
        
        if ($search) $users->appends(['search' => $search]);

        return view('user.index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255'],
            'username' => ['required', 'max:255', 'unique:users,username'],
            'email' => ['required', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:admin,petugas'],
            'password' => ['required', 'max:255', 'min:8', 'confirmed'],
        ]);

        $request->merge(
            ['password' => bcrypt($request->password)
        ]);

        User::create($request->all());
        return redirect()->route('user.index')->with('store', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('user.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'max:255'],
            'username' => ['required', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['required', 'max:255', 'unique:users,email, ' . $user->id],
            'role' => ['required', 'in:admin,petugas'],
            'password' => ['nullable', 'max:255', 'min:8', 'confirmed'],
        ]);

        if ($request->password) {
            $request->merge([
                'password' => bcrypt($request->password),
            ]);

            $user->update($request->all());
        } else {
            $user->update($request->only('name', 'username', 'email', 'role'));
        }

        return redirect()->route('user.index')->with('update', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('destroy', 'success');
    }
}
