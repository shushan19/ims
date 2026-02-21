<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\MenuItem;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function show(MenuItem $menuItem)
    {
        $menuItem->load('ingredients');
        $ingredients = Ingredient::orderBy('name')->get();

        return view('recipes.show', compact('menuItem', 'ingredients'));
    }

    public function store(Request $request, MenuItem $menuItem)
    {
        $data = $request->validate([
            'ingredient_id'    => 'required|exists:ingredients,id',
            'quantity_required'=> 'required|numeric|min:0.001',
        ]);

        $menuItem->ingredients()->syncWithoutDetaching([
            $data['ingredient_id'] => ['quantity_required' => $data['quantity_required']]
        ]);

        return back()->with('success', 'Recipe ingredient saved.');
    }

    public function update(Request $request, MenuItem $menuItem, Ingredient $ingredient)
    {
        $data = $request->validate([
            'quantity_required' => 'required|numeric|min:0.001',
        ]);

        $menuItem->ingredients()->updateExistingPivot($ingredient->id, $data);

        return back()->with('success', 'Quantity updated.');
    }

    public function destroy(MenuItem $menuItem, Ingredient $ingredient)
    {
        $menuItem->ingredients()->detach($ingredient->id);

        return back()->with('success', 'Ingredient removed from recipe.');
    }
}
