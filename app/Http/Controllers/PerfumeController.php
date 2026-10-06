<?php
namespace App\Http\Controllers;

use App\Models\Perfume;
use Illuminate\Http\Request;

class PerfumeController extends Controller
{
    private function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:80'],
            'category' => ['required', 'in:' . implode(',', Perfume::CATEGORIES)],
            'notes'    => ['nullable', 'string', 'max:255'],
            'price'    => ['required', 'numeric', 'min:0', 'max:99999'],
            'stock'    => ['required', 'integer', 'min:0', 'max:100000'],
            'color'    => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ];
    }

    public function collection() { return view('collection', ['perfumes' => Perfume::orderBy('name')->get()]); }

    public function stock(Request $request)
    {
        $perfumes = Perfume::when($request->q, fn ($q, $s) => $q->where('name', 'like', "%$s%")->orWhere('category', 'like', "%$s%"))
            ->orderBy('name')->get();
        return view('stock', ['perfumes' => $perfumes, 'total' => Perfume::sum('stock'), 'low' => Perfume::where('stock', '<=', 5)->count()]);
    }

    public function store(Request $request)
    {
        Perfume::create($request->validate($this->rules()));
        return back()->with('success', 'Perfume added to stock.');
    }

    public function update(Request $request, Perfume $perfume)
    {
        $perfume->update($request->validate($this->rules()));
        return back()->with('success', "{$perfume->name} updated.");
    }

    public function adjust(Request $request, Perfume $perfume)
    {
        $d = $request->validate(['delta' => ['required', 'integer', 'between:-100,100']]);
        $perfume->update(['stock' => max(0, $perfume->stock + $d['delta'])]);
        return back()->with('success', "{$perfume->name} stock is now {$perfume->stock}.");
    }

    public function destroy(Perfume $perfume)
    {
        $perfume->delete();
        return back()->with('success', 'Perfume removed.');
    }
}
