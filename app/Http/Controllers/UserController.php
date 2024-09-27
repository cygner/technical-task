<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::select('id', 'name', 'email')->get();
            return response()->json([
                'users' => $users
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'message' => "Something went wrong. Please try again.",
            ], 500);
        }
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
    public function show(User $user)
    {
        try {
            $user->load("posts:id,user_id,title,content,image");
            $user->posts->map(function ($post) {
                if ($post->image) {
                    $post->image = Storage::disk('s3')->url("posts/{$post->image}");
                }
                return $post;
            });
            return response()->json([
                'user' => $user
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'message' => "Something went wrong. Please try again.",
            ], 500);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
