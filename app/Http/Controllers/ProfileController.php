<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $image_path = null;
        if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
            $request->validate([
                "profile_image" =>  "image|max:2048"
            ]);
            try {
                //Check file exists
                $existing = $user->profile_image;
                if (!is_null($existing)) {
                    Storage::disk('public')->delete($existing);
                }
                //Store file and get path
                $file = $request->file('profile_image');
                $filename = $file->hashName();
                $image_path = $file->storeAs('assets/pictures/userprofile', $filename, 'public');
                $user->profile_image = $image_path;
            } catch (\Exception $e) {
                return response()->json(['errors' => $e->getMessage(), 'trace' => $e->getTrace()], 500);
            }
        } else {
            $request->validate(
                [
                    "name"  =>  "required",
                    "email" =>  "required",
                ],
                [
                    "name"  =>  [
                        "required"  =>  "Name field cannot be empty."
                    ],
                    "email" =>  [
                        "required"  => "Email field cannot be empty."
                    ]
                ]
            );

            $user->name = $request->name;
            $user->email = $request->email;
        }

        $user->save();
        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
