<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class AdminNewsController extends Controller
{
   
    public function index()
    {
        $news = News::orderBy('created_at', 'desc')->get();
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'content' => 'required|string',
        ]);

        News::create([
            'title' => $request->input('title'),
            'category' => $request->input('category'),
            'content' => $request->input('content'),
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Announcement posted successfully!');
    }

    
    public function destroy($id)
    {
        $newsItem = News::findOrFail($id);
        $newsItem->delete();

        return redirect()->route('admin.news.index')->with('success', 'Announcement removed successfully.');
    }
}