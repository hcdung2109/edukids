<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $news = News::query()
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%' . $request->q . '%'))
            ->latest('published_at')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.news.index', compact('news'));
    }

    public function create(): View
    {
        return view('admin.news.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'images.*' => ['nullable', 'image', 'max:2048'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ]);
        $validated['is_published'] = $request->boolean('is_published');
        unset($validated['image'], $validated['images']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('news/covers', 'public');
        }

        $news = News::create($validated);

        if ($request->hasFile('images')) {
            $sort = 0;
            foreach ($request->file('images') as $file) {
                if (! $file->isValid()) {
                    continue;
                }
                $path = $file->store('news/gallery/' . $news->id, 'public');
                $news->images()->create(['path' => $path, 'sort_order' => $sort++]);
            }
        }

        return redirect()->route('admin.news.index')->with('success', 'Đã thêm bài viết.');
    }

    public function edit(News $news): View
    {
        $news->load('images');
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'images.*' => ['nullable', 'image', 'max:2048'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ]);
        $validated['is_published'] = $request->boolean('is_published');
        unset($validated['image'], $validated['images']);

        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $validated['image'] = $request->file('image')->store('news/covers', 'public');
        }

        $news->update($validated);

        if ($request->hasFile('images')) {
            $sort = (int) $news->images()->max('sort_order') + 1;
            foreach ($request->file('images') as $file) {
                if (! $file->isValid()) {
                    continue;
                }
                $path = $file->store('news/gallery/' . $news->id, 'public');
                $news->images()->create(['path' => $path, 'sort_order' => $sort++]);
            }
        }

        return redirect()->route('admin.news.index')->with('success', 'Đã cập nhật bài viết.');
    }

    public function destroy(News $news): RedirectResponse
    {
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Đã xóa bài viết.');
    }

    public function destroyImage(News $news, int $image): RedirectResponse
    {
        $imageModel = $news->images()->findOrFail($image);
        $imageModel->delete();
        return back()->with('success', 'Đã xóa ảnh.');
    }
}
