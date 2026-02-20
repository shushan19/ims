<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Ingredient;
use App\Models\StockMovement;
use Illuminate\Validation\ValidationException;

class StockService
{

    //checking if we have enough stock before placing order if anything is short is will thorw error and stop
    public function verifyStockForOrder(Order $order): void
    {
        // This will collect any ingredients we don't have enough of
        $shortfalls = [];

        // Go through each item the customer ordered
        // e.g. 2x Burgers, 1x Pizza
        foreach ($order->orderItems as $orderItem) {

            // Load this menu item's ingredients from the recipe
            // e.g. Burger needs: Tomato, Cheese, Bread
            $menuItem = $orderItem->menuItem->load('ingredients');

            // Now check each ingredient in the recipe
            foreach ($menuItem->ingredients as $ingredient) {

                // How much of this ingredient do we need?
                // Formula: (quantity per serving) × (how many ordered)
                // Example: Burger needs 50g tomato, customer ordered 2
                //          So we need: 50 × 2 = 100g tomato
                $amountNeeded = $ingredient->pivot->quantity_required * $orderItem->quantity;

                // Do we actually have that much in stock?
                if ($ingredient->current_stock < $amountNeeded) {

                    // Not enough! Add to our shortfalls list
                    $shortfalls[] = "{$ingredient->name}: needs {$amountNeeded} {$ingredient->unit}, "
                        . "only {$ingredient->current_stock} available";
                }
            }
        }

        // If we found ANY shortfalls, throw an error
        // This stops the order from being placed
        if (!empty($shortfalls)) {
            throw ValidationException::withMessages([
                'stock' => $shortfalls
            ]);
        }

        // If we reach here, all stock is sufficient — order can proceed!
    }


    // ========================================================
    // STEP 2: DEDUCT stock when order is marked as "Delivered"
    // ========================================================
    // This runs only when an order status changes to "delivered".
    // We loop through every item, find its recipe, and subtract
    // the ingredients from the current stock.
    // ========================================================

    public function deductStockForOrder(Order $order): void
    {
        // Go through each item in the order
        // e.g. 2x Burgers, 1x Pizza
        foreach ($order->orderItems as $orderItem) {

            // Load this menu item with its recipe ingredients
            $menuItem = $orderItem->menuItem->load('ingredients');

            // Go through each ingredient in the recipe
            foreach ($menuItem->ingredients as $ingredient) {

                // Calculate how much to deduct
                // Example: Burger needs 50g cheese, customer ordered 2
                //          Deduct: 50 × 2 = 100g cheese
                $amountToDeduct = $ingredient->pivot->quantity_required * $orderItem->quantity;

                // Subtract from the ingredient's current stock in database
                // decrement() is a Laravel helper that does:
                // UPDATE ingredients SET current_stock = current_stock - $amountToDeduct
                $ingredient->decrement('current_stock', $amountToDeduct);

                // Write a log entry so we have a history of what was used
                // This creates a row in the stock_movements table
                StockMovement::create([
                    'ingredient_id'  => $ingredient->id,
                    'order_id'       => $order->id,
                    'quantity'       => -$amountToDeduct, // negative = stock went OUT
                    'type'           => 'deduction',
                    'notes'          => "Used for Order #{$order->id}",
                ]);
            }
        }
    }


    // ========================================================
    // STEP 3: MANUALLY ADD stock (e.g. new delivery from supplier)
    // ========================================================
    // This is simple — someone received new stock and wants
    // to update the system. We add to current stock and log it.
    // ========================================================

    public function addStock(Ingredient $ingredient, float $quantity, ?string $notes = null): void
    {
        // Add the quantity to current stock in the database
        // increment() does: UPDATE ingredients SET current_stock = current_stock + $quantity
        $ingredient->increment('current_stock', $quantity);

        // Log this addition in stock_movements
        StockMovement::create([
            'ingredient_id' => $ingredient->id,
            'order_id'      => null,            // no order linked, this is manual
            'quantity'      => $quantity,        // positive = stock came IN
            'type'          => 'manual_add',
            'notes'         => $notes ?? 'Manual stock addition',
        ]);
    }


    // ========================================================
    // BONUS: Find ingredients that are running low
    // ========================================================
    // Returns a list of ingredients where current stock
    // is at or below the minimum stock level.
    // Used to show warnings on the dashboard.
    // ========================================================

    public function getLowStockIngredients()
    {
        // whereColumn() compares two columns in the same table
        // This finds rows where current_stock <= minimum_stock
        return Ingredient::whereColumn('current_stock', '<=', 'minimum_stock')->get();
    }
}
