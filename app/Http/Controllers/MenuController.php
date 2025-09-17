<?php

namespace App\Http\Controllers;

use App\Models\{MenuItem, Category};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $items = MenuItem::with('category')->latest()->paginate(12);
        return view('menu.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('menu.create', compact('categories'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'is_available'=> 'required|boolean',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|max:2048', // jpg/png/webp up to 2MB
        ]);

        if ($r->hasFile('image')) {
            $data['image_path'] = $r->file('image')->store('menu', 'public');
        }

        MenuItem::create($data);
        return redirect()->route('menu.index')->with('ok', 'تمت الإضافة');
    }

    public function edit(MenuItem $menu)
    {
        $categories = Category::orderBy('name')->get();
        return view('menu.edit', ['item' => $menu, 'categories' => $categories]);
    }

    public function update(Request $r, MenuItem $menu)
    {
        $data = $r->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'is_available'=> 'required|boolean',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|max:2048',
            'remove_image'=> 'nullable|boolean',
        ]);

        // remove old
        if ($r->boolean('remove_image') && $menu->image_path) {
            Storage::disk('public')->delete($menu->image_path);
            $data['image_path'] = null;
        }

        // upload new
        if ($r->hasFile('image')) {
            if ($menu->image_path) {
                Storage::disk('public')->delete($menu->image_path);
            }
            $data['image_path'] = $r->file('image')->store('menu', 'public');
        }

        $menu->update($data);
        return redirect()->route('menu.index')->with('ok', 'تم التعديل');
    }

    public function destroy(MenuItem $menu)
    {
        if ($menu->image_path) {
            Storage::disk('public')->delete($menu->image_path);
        }
        $menu->delete();
        return back()->with('ok', 'تم الحذف');
    }
}
