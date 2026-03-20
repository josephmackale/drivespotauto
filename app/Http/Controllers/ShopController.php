<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\VsVehicleVariant;
use Illuminate\Http\Request;
use App\Models\Category;

class ShopController extends Controller
{
    public function home()
    {
        $featuredProducts = Product::query()
            ->latest()
            ->take(8)
            ->get();

        return view('store.home', [
            'featuredProducts' => $featuredProducts,
        ]);
    }

    public function shop(Request $request)
    {
        $selectedVehicleId = session('shop_vehicle.id');
        $selectedVehicle = session('shop_vehicle.label');
        $vehicle = null;

        if ($selectedVehicleId) {
            $vehicle = VsVehicleVariant::with(['generation.model.make'])
                ->where('id', $selectedVehicleId)
                ->where('is_active', 1)
                ->first();

            // safety: if vehicle no longer exists, clear session
            if (! $vehicle) {
                session()->forget('shop_vehicle');
                $selectedVehicleId = null;
                $selectedVehicle = null;
            }
        }

        $products = Product::query()
            ->when($selectedVehicleId, function ($query) use ($selectedVehicleId) {
                $query->whereHas('vehicleFitments', function ($fitmentQuery) use ($selectedVehicleId) {
                    $fitmentQuery->where('engine_id', $selectedVehicleId);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('store.shop', [
            'products' => $products,
            'categories' => $categories,
            'selectedVehicle' => $selectedVehicle,
            'vehicle' => $vehicle,
        ]);
    }

    public function vehicle($vehicle_key)
    {
        $vehicle = VsVehicleVariant::with(['generation.model.make'])
            ->where('key_canonical', $vehicle_key)
            ->where('is_active', 1)
            ->firstOrFail();

        $make = $vehicle->generation?->model?->make?->name;
        $model = $vehicle->generation?->model?->name;
        $generation = $vehicle->generation?->name;
        $capacity = $vehicle->capacity_l ? $vehicle->capacity_l . 'L' : null;
        $variant = $vehicle->variant_name ?: null;
        $engine = $vehicle->engine_code ?: null;
        $drivetrain = $vehicle->drivetrain ?: null;
        $yearFrom = $vehicle->year_from;
        $yearTo = $vehicle->year_to == 9999 ? 'Present' : $vehicle->year_to;

        $selectedVehicle = trim(
            implode(' ', array_filter([
                $make,
                $model,
                $generation ? "({$generation})" : null,
                $capacity,
                $variant,
                $engine,
                $drivetrain,
            ]))
        );

        session([
            'shop_vehicle.id' => $vehicle->id,
            'shop_vehicle.key' => $vehicle->key_canonical,
            'shop_vehicle.label' => $selectedVehicle,
        ]);

        $products = Product::query()
            ->where('is_active', 1)
            ->whereHas('vehicleFitments', function ($query) use ($vehicle) {
                $query->where('engine_id', $vehicle->engine_id);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()
            ->whereNull('deleted_at')
            ->orderByRaw('CASE WHEN parent_id IS NULL THEN 0 ELSE 1 END')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();

        return view('store.vehicle', [
            'vehicle' => $vehicle,
            'products' => $products,
            'selectedVehicle' => $selectedVehicle,
            'yearFrom' => $yearFrom,
            'yearTo' => $yearTo,
            'categories' => $categories,
            'selectedCategory' => null,
        ]);
    }

    public function vehicleCategory($vehicle_key, $category_slug)
    {
        $vehicle = VsVehicleVariant::with(['generation.model.make'])
            ->where('key_canonical', $vehicle_key)
            ->where('is_active', 1)
            ->firstOrFail();

        $category = Category::query()
            ->where('slug', $category_slug)
            ->whereNull('deleted_at')
            ->firstOrFail();
        
        $make = $vehicle->generation?->model?->make?->name;
        $model = $vehicle->generation?->model?->name;
        $generation = $vehicle->generation?->name;
        $capacity = $vehicle->capacity_l ? $vehicle->capacity_l . 'L' : null;
        $variant = $vehicle->variant_name ?: null;
        $engine = $vehicle->engine_code ?: null;
        $drivetrain = $vehicle->drivetrain ?: null;
        $yearFrom = $vehicle->year_from;
        $yearTo = $vehicle->year_to == 9999 ? 'Present' : $vehicle->year_to;

        $selectedVehicle = trim(
            implode(' ', array_filter([
                $make,
                $model,
                $generation ? "({$generation})" : null,
                $capacity,
                $variant,
                $engine,
                $drivetrain,
            ]))
        );

        session([
            'shop_vehicle.id' => $vehicle->id,
            'shop_vehicle.key' => $vehicle->key_canonical,
            'shop_vehicle.label' => $selectedVehicle,
        ]);

        $categoryIds = collect([$category->id]);

        if (is_null($category->parent_id)) {
            $childIds = Category::query()
                ->where('parent_id', $category->id)
                ->whereNull('deleted_at')
                ->pluck('id');

            $categoryIds = $categoryIds->merge($childIds);
        }

        $products = Product::query()
            ->where('is_active', 1)
            ->whereIn('category_id', $categoryIds)
            ->whereHas('vehicleFitments', function ($query) use ($vehicle) {
                $query->where('engine_id', $vehicle->engine_id);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()
            ->whereNull('deleted_at')
            ->orderByRaw('CASE WHEN parent_id IS NULL THEN 0 ELSE 1 END')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();

        return view('store.vehicle', [
            'vehicle' => $vehicle,
            'products' => $products,
            'selectedVehicle' => $selectedVehicle,
            'yearFrom' => $yearFrom,
            'yearTo' => $yearTo,
            'categories' => $categories,
            'selectedCategory' => $category,
        ]);
    }

    public function vehicleSubcategory($vehicle_key, $category_slug, $subcategory_slug)
    {
        $vehicle = VsVehicleVariant::with(['generation.model.make'])
            ->where('key_canonical', $vehicle_key)
            ->where('is_active', 1)
            ->firstOrFail();

        $category = Category::query()
            ->where('slug', $category_slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $subcategory = Category::query()
            ->where('slug', $subcategory_slug)
            ->where('parent_id', $category->id)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $make = $vehicle->generation?->model?->make?->name;
        $model = $vehicle->generation?->model?->name;
        $generation = $vehicle->generation?->name;
        $capacity = $vehicle->capacity_l ? $vehicle->capacity_l . 'L' : null;
        $variant = $vehicle->variant_name ?: null;
        $engine = $vehicle->engine_code ?: null;
        $drivetrain = $vehicle->drivetrain ?: null;
        $yearFrom = $vehicle->year_from;
        $yearTo = $vehicle->year_to == 9999 ? 'Present' : $vehicle->year_to;

        $selectedVehicle = trim(
            implode(' ', array_filter([
                $make,
                $model,
                $generation ? "({$generation})" : null,
                $capacity,
                $variant,
                $engine,
                $drivetrain,
            ]))
        );

        session([
            'shop_vehicle.id' => $vehicle->id,
            'shop_vehicle.key' => $vehicle->key_canonical,
            'shop_vehicle.label' => $selectedVehicle,
        ]);

        $categoryIds = collect([$subcategory->id]);

        $childIds = Category::query()
            ->where('parent_id', $subcategory->id)
            ->whereNull('deleted_at')
            ->pluck('id');

        $categoryIds = $categoryIds->merge($childIds);

        $products = Product::query()
            ->where('is_active', 1)
            ->whereIn('category_id', $categoryIds)
            ->whereHas('vehicleFitments', function ($query) use ($vehicle) {
                $query->where('engine_id', $vehicle->engine_id);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()
            ->whereNull('deleted_at')
            ->orderByRaw('CASE WHEN parent_id IS NULL THEN 0 ELSE 1 END')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();

        return view('store.shop', [
            'vehicle' => $vehicle,
            'products' => $products,
            'selectedVehicle' => $selectedVehicle,
            'yearFrom' => $yearFrom,
            'yearTo' => $yearTo,
            'categories' => $categories,
            'selectedCategory' => $subcategory,
            'selectedParentCategory' => $category,
        ]);
    }    
    public function clearVehicle()
    {
        session()->forget('shop_vehicle');

        return redirect()->route('shop');
    }

    public function category($slug)
    {
        $selectedVehicleId = session('shop_vehicle.id');
        $selectedVehicle = session('shop_vehicle.label');
        $vehicle = null;

        if ($selectedVehicleId) {
            $vehicle = VsVehicleVariant::with(['generation.model.make'])
                ->where('id', $selectedVehicleId)
                ->where('is_active', 1)
                ->first();

            if (! $vehicle) {
                session()->forget('shop_vehicle');
                $selectedVehicleId = null;
                $selectedVehicle = null;
            }
        }

        $category = Category::query()
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $categoryIds = collect([$category->id]);

        if (is_null($category->parent_id)) {
            $childIds = Category::query()
                ->where('parent_id', $category->id)
                ->whereNull('deleted_at')
                ->pluck('id');

            $categoryIds = $categoryIds->merge($childIds);
        }

        $products = Product::query()
            ->where('is_active', 1)
            ->whereIn('category_id', $categoryIds)
            ->when($selectedVehicleId, function ($query) use ($selectedVehicleId) {
                $query->whereHas('vehicleFitments', function ($fitmentQuery) use ($selectedVehicleId) {
                    $fitmentQuery->where('engine_id', $selectedVehicleId);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('store.shop', [
            'products' => $products,
            'categories' => $categories,
            'selectedVehicle' => $selectedVehicle,
            'vehicle' => $vehicle,
            'currentCategory' => $category,
        ]);
    }
}