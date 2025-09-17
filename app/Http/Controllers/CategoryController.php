<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(12);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'       => ['required','string','max:120','unique:categories,name'],
            'image'      => ['nullable','image','mimes:jpg,jpeg,png,webp,avif','max:2048'],
        ]);

        $imagePath = null;
        if ($r->hasFile('image')) {
            $imagePath = $r->file('image')->store('categories', 'public');
        }

        Category::create([
            'name'       => $data['name'],
            'slug'       => Str::slug($data['name']),
            'image_path' => $imagePath,
        ]);

        return redirect()->route('categories.index')->with('ok', 'تم إنشاء الفئة بنجاح');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $r, Category $category)
    {
        $data = $r->validate([
            'name'  => ['required','string','max:120', Rule::unique('categories','name')->ignore($category->id)],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp,avif','max:2048'],
            'remove_image' => ['nullable','boolean'],
        ]);

        // Image handling
        if ($r->boolean('remove_image')) {
            $category->image_path = null;
        }
        if ($r->hasFile('image')) {
            $category->image_path = $r->file('image')->store('categories', 'public');
        }

        $category->name = $data['name'];
        $category->slug = $category->slug ?: Str::slug($data['name']);
        $category->save();

        return redirect()->route('categories.index')->with('ok', 'تم تحديث الفئة بنجاح');
    }

    public function destroy(Category $category)
    {
        // Optional: block delete if it has items
        // if ($category->items()->exists()) return back()->withErrors('لا يمكن الحذف: تحتوي على أصناف.');
        $category->delete();
        return back()->with('ok', 'تم حذف الفئة');
    }
}
