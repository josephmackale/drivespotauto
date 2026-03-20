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
        [$vehicle, $selectedEngineId, $selectedVehicle] = $this->resolveRememberedVehicle();

        $products = $this->buildProductQuery($selectedEngineId)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = $this->loadShopCategories();

        return view('store.shop', [
            'products' => $products,
            'categories' => $categories,
            'selectedVehicle' => $selectedVehicle,
            'vehicle' => $vehicle,
            'yearFrom' => null,
            'yearTo' => null,
            'selectedCategory' => null,
            'selectedParentCategory' => null,
        ]);
    }

    public function vehicle($vehicle_key)
    {
        $vehicle = $this->resolveVehicleByKey($vehicle_key);

        if (! $vehicle) {
            abort(404);
        }

        $context = $this->makeVehiclePageContext($vehicle);

        $products = $this->buildProductQuery($vehicle->engine_id)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = $this->loadVehicleCategories();

        return view('store.shop', [
            'vehicle' => $context['vehicle'],
            'products' => $products,
            'selectedVehicle' => $context['selectedVehicle'],
            'yearFrom' => $context['yearFrom'],
            'yearTo' => $context['yearTo'],
            'categories' => $categories,
            'selectedCategory' => null,
            'selectedParentCategory' => null,
        ]);
    }

    public function vehicleCategory($vehicle_key, $category_slug)
    {
        $vehicle = $this->resolveVehicleByKey($vehicle_key);

        if (! $vehicle) {
            abort(404);
        }

        $category = Category::query()
            ->where('slug', $category_slug)
            ->whereNull('deleted_at')
            ->firstOrFail();
        
        $context = $this->makeVehiclePageContext($vehicle);

        $categoryIds = $this->resolveCategoryIds($category);

        $products = $this->buildProductQuery($vehicle->engine_id, $categoryIds)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = $this->loadVehicleCategories();

        return view('store.shop', [
            'vehicle' => $context['vehicle'],
            'products' => $products,
            'selectedVehicle' => $context['selectedVehicle'],
            'yearFrom' => $context['yearFrom'],
            'yearTo' => $context['yearTo'],
            'categories' => $categories,
            'selectedCategory' => $category,
            'selectedParentCategory' => null,
        ]);
    }

    public function vehicleSubcategory($vehicle_key, $category_slug, $subcategory_slug)
    {
        $vehicle = $this->resolveVehicleByKey($vehicle_key);

        if (! $vehicle) {
            abort(404);
        }

        $category = Category::query()
            ->where('slug', $category_slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $subcategory = Category::query()
            ->where('slug', $subcategory_slug)
            ->where('parent_id', $category->id)
            ->whereNull('deleted_at')
            ->firstOrFail();
        $context = $this->makeVehiclePageContext($vehicle);

        $categoryIds = $this->resolveCategoryIds($subcategory);

        $products = $this->buildProductQuery($vehicle->engine_id, $categoryIds)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = $this->loadVehicleCategories();

        return view('store.shop', [
            'vehicle' => $context['vehicle'],
            'products' => $products,
            'selectedVehicle' => $context['selectedVehicle'],
            'yearFrom' => $context['yearFrom'],
            'yearTo' => $context['yearTo'],
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
        [$vehicle, $selectedEngineId, $selectedVehicle] = $this->resolveRememberedVehicle();

        $category = Category::query()
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $categoryIds = $this->resolveCategoryIds($category);

        $products = $this->buildProductQuery($selectedEngineId, $categoryIds)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = $this->loadShopCategories();

        return view('store.shop', [
            'products' => $products,
            'categories' => $categories,
            'selectedVehicle' => $selectedVehicle,
            'vehicle' => $vehicle,
            'yearFrom' => null,
            'yearTo' => null,
            'selectedCategory' => $category,
            'selectedParentCategory' => null,
        ]);
    }

    public function subcategory($category_slug, $subcategory_slug)
    {
        [$vehicle, $selectedEngineId, $selectedVehicle] = $this->resolveRememberedVehicle();

        $parentCategory = Category::query()
            ->where('slug', $category_slug)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $subcategory = Category::query()
            ->where('slug', $subcategory_slug)
            ->where('parent_id', $parentCategory->id)
            ->whereNull('deleted_at')
            ->firstOrFail();

        $categoryIds = $this->resolveCategoryIds($subcategory);

        $products = $this->buildProductQuery($selectedEngineId, $categoryIds)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = $this->loadShopCategories();

        return view('store.shop', [
            'products' => $products,
            'categories' => $categories,
            'selectedVehicle' => $selectedVehicle,
            'vehicle' => $vehicle,
            'yearFrom' => null,
            'yearTo' => null,
            'selectedCategory' => $subcategory,
            'selectedParentCategory' => $parentCategory,
        ]);
    }

    private function makeVehicleLabel(VsVehicleVariant $vehicle): string
    {
        $make = $vehicle->generation?->model?->make?->name;
        $model = $vehicle->generation?->model?->name;
        $generation = $vehicle->generation?->name;
        $capacity = $vehicle->capacity_l ? $vehicle->capacity_l . 'L' : null;
        $variant = $vehicle->variant_name ?: null;
        $engine = $vehicle->engine_code ?: null;
        $drivetrain = $vehicle->drivetrain ?: null;

        return trim(
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
    }

    private function rememberVehicle(VsVehicleVariant $vehicle): void
    {
        session([
            'shop_vehicle.key' => $vehicle->key_canonical,
            'shop_vehicle.engine_id' => $vehicle->engine_id,
            'shop_vehicle.label' => $this->makeVehicleLabel($vehicle),
        ]);
    }

    private function resolveVehicleByKey(?string $key): ?VsVehicleVariant
    {
        if (! $key) {
            return null;
        }

        return VsVehicleVariant::with(['generation.model.make'])
            ->where('key_canonical', $key)
            ->where('is_active', 1)
            ->first();
    }

    private function resolveRememberedVehicle(): array
    {
        $key = session('shop_vehicle.key');

        if (! $key) {
            return [null, null, null];
        }

        $vehicle = $this->resolveVehicleByKey($key);

        // 🚨 safety: invalid session
        if (! $vehicle) {
            session()->forget('shop_vehicle');
            return [null, null, null];
        }

        // ✅ normalize session from DB truth
        $this->rememberVehicle($vehicle);

        return [
            $vehicle,
            $vehicle->engine_id,
            $this->makeVehicleLabel($vehicle),
        ];
    }

    private function buildProductQuery(?int $engineId = null, $categoryIds = null)
    {
        return Product::query()
            ->where('is_active', 1)
            ->when($engineId, function ($query) use ($engineId) {
                $query->whereHas('vehicleFitments', function ($fitmentQuery) use ($engineId) {
                    $fitmentQuery->where('engine_id', $engineId);
                });
            })
            ->when($categoryIds, function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds);
            });
    }

    private function resolveCategoryIds(Category $category)
    {
        $categoryIds = collect([$category->id]);

        $childIds = Category::query()
            ->where('parent_id', $category->id)
            ->whereNull('deleted_at')
            ->pluck('id');

        return $categoryIds->merge($childIds);
    }

    private function makeVehiclePageContext(VsVehicleVariant $vehicle): array
    {
        $this->rememberVehicle($vehicle);

        return [
            'vehicle' => $vehicle,
            'selectedVehicle' => $this->makeVehicleLabel($vehicle),
            'yearFrom' => $vehicle->year_from,
            'yearTo' => (int) $vehicle->year_to === 2099 ? 'Present' : $vehicle->year_to,
        ];
    }

    private function loadShopCategories()
    {
        return Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();
    }

    private function loadVehicleCategories()
    {
        return Category::query()
            ->whereNull('deleted_at')
            ->orderByRaw('CASE WHEN parent_id IS NULL THEN 0 ELSE 1 END')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();
    }
}