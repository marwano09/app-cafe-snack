<?php

namespace App\Http\Controllers;

use App\Models\{MenuItem, Category};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        // Read filters from query string
        $q           = trim((string) $request->query('q', ''));
        $category_id = $request->query('category_id');

        $query = MenuItem::with('category');

        if ($q !== '') {
            $query->where('name', 'like', "%{$q}%");
        }

        if (!empty($category_id)) {
            $query->where('category_id', $category_id);
        }

        $items = $query->latest()->paginate(12)->withQueryString();

        // Needed for the filter dropdown
        $categories = Category::orderBy('name')->get();

        // Pass everything the view uses
        return view('menu.index', compact('items', 'categories', 'q', 'category_id'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('menu.create', compact('categories'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
            'category_id'  => 'required|exists:categories,id',
            'image'        => 'nullable|image|max:2048', // jpg/png/webp up to 2MB
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
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
            'category_id'  => 'required|exists:categories,id',
            'image'        => 'nullable|image|max:2048',
            'remove_image' => 'nullable|boolean',
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
