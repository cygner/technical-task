<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $posts = Post::with('user:id,name')->select('id', 'user_id', 'title', 'content', 'image')->get()
                ->map(function ($post) {
                    if ($post->image) {
                        $post->image = Storage::disk('s3')->url("posts/{$post->image}");
                    }
                    return $post;
                });

            return response()->json([
                'posts' => $posts
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
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            $post = $post->create($request->post());

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $image->getUuidName();
                Storage::disk("public")->putFileAs("posts", $image, $imageName);
                Storage::disk("s3")->putFileAs("posts", $image, $imageName);
                $post->image = $imageName;
                $post->save();
            }

            $post->load("user:id,name");
            $post->image = Storage::disk('s3')->url("posts/{$imageName}");

            return response()->json([
                "message" => "Post Created Successfully.",
                "post" => $post,
            ]);

        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'message' => "Something went wrong. Please try again.",
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        try {
            $post->load("user:id,name");
            $post->image = Storage::disk('s3')->url("posts/{$post->image}");
            return response()->json([
                'post' => $post
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
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {

            $post->fill($request->post())->save();

            if ($request->hasFile('image')) {

                if (Storage::disk("public")->exists("posts/{$post->image}")) {
                    Storage::disk("public")->delete("posts/{$post->image}");
                }
                if (Storage::disk("s3")->exists("posts/{$post->image}")) {
                    Storage::disk("s3")->delete("posts/{$post->image}");
                }

                $image = $request->file('image');
                $imageName = $image->getUuidName();
                Storage::disk("public")->putFileAs("posts", $image, $imageName);
                Storage::disk("s3")->putFileAs("posts", $image, $imageName);

                $post->image = $imageName;
                $post->save();

                $post->image = Storage::disk('s3')->url("posts/{$imageName}");
            }

            $post->load("user:id,name");

            return response()->json([
                "message" => "Post Updated Successfully.",
                "post" => $post,
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'message' => "Something went wrong. Please try again.",
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Post $post)
    {
        try {
            if (Storage::disk("public")->exists("posts/{$post->image}")) {
                Storage::disk("public")->delete("posts/{$post->image}");
            }

            if (Storage::disk("s3")->exists("posts/{$post->image}")) {
                Storage::disk("s3")->delete("posts/{$post->image}");
            }

            $post->delete();
            return response()->json([
                "message" => "Post Deleted Successfully.",
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'message' => "Something went wrong. Please try again.",
            ], 500);
        }
    }
}
