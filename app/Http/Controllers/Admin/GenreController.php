<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Support\Str;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::orderBy('name', 'asc')->get();
        return view('admin.genres.index', compact('genres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:genres,name',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Vui lòng nhập tên thể loại!',
            'name.unique' => 'Thể loại này đã tồn tại trong hệ thống!',
        ]);

        $genre = new Genre();
        $genre->name = trim($request->name);
        $genre->slug = Str::slug($request->name);
        $genre->description = $request->description;
        $genre->save();

        return redirect()->route('admin.genres.index')->with('success', 'Thêm thể loại mới thành công!');
    }

    public function update(Request $request, Genre $genre)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:genres,name,' . $genre->id,
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Vui lòng nhập tên thể loại!',
            'name.unique' => 'Tên thể loại đã được sử dụng!',
        ]);

        $oldName = $genre->name;
        $newName = trim($request->name);

        $genre->name = $newName;
        $genre->slug = Str::slug($newName);
        $genre->description = $request->description;
        $genre->save();

        // Synchronize updated genre name in movies
        if ($oldName !== $newName) {
            $movies = Movie::where('genre', 'like', '%' . $oldName . '%')->get();
            foreach ($movies as $movie) {
                $movie->genre = str_replace($oldName, $newName, $movie->genre);
                $movie->save();
            }
        }

        return redirect()->route('admin.genres.index')->with('success', 'Cập nhật thể loại thành công!');
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();
        return redirect()->route('admin.genres.index')->with('success', 'Xóa thể loại thành công!');
    }
}
