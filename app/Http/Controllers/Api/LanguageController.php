<?php
// app/Http/Controllers/Api/LanguageController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        return Language::orderBy('is_default', 'desc')->orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:languages,code',
            'name' => 'required|string',
            'direction' => 'required|in:LTR,RTL',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        if ($request->is_default) {
            // Reset default flag for others
            Language::where('is_default', true)->update(['is_default' => false]);
        }

        $language = Language::create($request->all());

        return response()->json($language, 201);
    }

    public function update(Request $request, $id)
    {
        $language = Language::findOrFail($id);

        $request->validate([
            'code' => 'string|unique:languages,code,' . $id,
            'name' => 'string',
            'direction' => 'in:LTR,RTL',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        if ($request->is_default) {
            Language::where('is_default', true)->update(['is_default' => false]);
        }

        $language->update($request->all());

        return response()->json($language);
    }

    public function destroy($id)
    {
        $language = Language::findOrFail($id);
        $language->delete();

        return response()->json(null, 204);
    }

public function getDefaultLanguage()
{
    $language = \App\Models\Language::where('is_default', 1)->first();

    if (!$language) {
        return response()->json(['default_language' => 'en', 'direction' => 'ltr']);
    }

    return response()->json([
        'default_language' => $language->code,
        'direction' => $language->direction,
    ]);
}


}
