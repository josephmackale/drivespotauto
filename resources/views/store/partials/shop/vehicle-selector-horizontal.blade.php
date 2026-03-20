<div class="w-full">
    <div class="rounded-2xl bg-slate-900/35 backdrop-blur-sm border border-white/10 p-4 md:p-5">
        <form
            x-data="vehicleSelector()"
            x-init="init()"
            @submit.prevent="goToVehiclePage()"
            class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center min-w-0"
        >

            {{-- MAKE --}}
            <div class="relative min-w-0 md:col-span-3" x-data="{ open: false }">

                <span class="absolute left-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-gray-200 text-gray-500 text-xs font-bold flex items-center justify-center z-10">
                    1
                </span>

                <button
                    type="button"
                    @click="open = !open"
                    class="w-full h-12 border border-gray-300 bg-white pl-12 pr-10 text-left text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <span x-show="!selectedMakeLabel" class="block pr-6">
                        Select make
                    </span>

                    <span
                        x-show="selectedMakeLabel"
                        class="block pr-6 truncate"
                        x-text="selectedMakeLabel"
                    ></span>
                </button>

                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </span>

                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition
                    class="absolute left-0 right-0 mt-2 bg-white border border-gray-300 shadow-lg z-30 overflow-hidden"
                >
                    <div class="max-h-[300px] overflow-y-auto">

                        <template x-if="makes.length === 0">
                            <div class="p-4 text-sm text-gray-500">Loading makes...</div>
                        </template>

                        <template x-for="make in makes" :key="make.id">
                            <button
                                type="button"
                                @click="
                                    selectedMake = make.id;
                                    selectedMakeLabel = make.name;
                                    open = false;
                                    onMakeChange();
                                "
                                class="w-full text-left px-4 py-3 border-b border-gray-100 hover:bg-blue-50"
                                :class="String(selectedMake) === String(make.id) ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700'"
                            >
                                <span x-text="make.name"></span>
                            </button>
                        </template>

                    </div>
                </div>

            </div>

            {{-- MODEL --}}
            <div class="relative min-w-0 md:col-span-4" x-data="{ open: false }">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-gray-200 text-gray-500 text-xs font-bold flex items-center justify-center z-10">
                    2
                </span>

                <button
                    type="button"
                    @click="if (selectedMake && !loadingModels) open = !open"
                    :disabled="!selectedMake || loadingModels"
                    class="w-full h-12 border border-gray-300 bg-white pl-12 pr-10 text-left text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100 disabled:text-gray-400"
                >
                    <span x-show="!selectedGenerationLabel" class="block pr-6">
                        <span x-text="loadingModels ? 'Loading models...' : 'Select model'"></span>
                    </span>

                    <span x-show="selectedGenerationLabel" class="block pr-6 truncate" x-text="selectedGenerationLabel"></span>
                </button>

                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </span>

                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition
                    class="absolute left-0 right-0 mt-2 bg-white border border-gray-300 shadow-lg z-30 overflow-hidden"
                >
                    <div class="sticky top-0 z-10 bg-white p-3 border-b border-gray-200">
                        <input
                            type="text"
                            x-model="modelSearch"
                            placeholder="Search model"
                            class="w-full h-12 rounded-lg border border-gray-300 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>

                    <div class="max-h-[340px] overflow-y-auto">
                        <template x-if="loadingModels">
                            <div class="p-4 text-sm text-gray-500">Loading models...</div>
                        </template>

                        <template x-if="!loadingModels && filteredModels().length === 0">
                            <div class="p-4 text-sm text-gray-500">No models found.</div>
                        </template>

                        <template x-for="model in filteredModels()" :key="model.id">
                            <div class="border-b border-gray-100">
                                <button
                                    type="button"
                                    @click="toggleModel(model)"
                                    class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-blue-50"
                                    :class="String(activeModelId) === String(model.id) ? 'bg-gray-50' : 'bg-white'"
                                >
                                    <span class="font-medium text-gray-800" x-text="model.name"></span>

                                    <span class="text-gray-500">
                                        <span x-show="!isModelExpanded(model.id)">+</span>
                                        <span x-show="isModelExpanded(model.id)">−</span>
                                    </span>
                                </button>

                                <div x-show="isModelExpanded(model.id)" x-transition class="bg-gray-50 border-t border-gray-200">
                                    <template x-if="loadingGenerations && String(activeModelId) === String(model.id)">
                                        <div class="px-6 py-2 text-sm text-gray-500">Loading generations...</div>
                                    </template>

                                    <template x-if="!loadingGenerations && (!model.generations || model.generations.length === 0)">
                                        <div class="px-6 py-2 text-sm text-gray-500">No generations available.</div>
                                    </template>

                                    <template x-for="generation in model.generations" :key="generation.id">
                                        <button
                                            type="button"
                                            @click="selectGeneration(generation); open = false"
                                            class="w-full text-left px-8 py-2 text-sm border-t border-gray-200 hover:bg-blue-50"
                                            :class="String(selectedGeneration) === String(generation.id) ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700'"
                                        >
                                            <span x-text="generation.label"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- ENGINE --}}
            <div class="relative min-w-0 md:col-span-4" x-data="{ open: false }">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-gray-200 text-gray-500 text-xs font-bold flex items-center justify-center z-10">
                    3
                </span>

                <button
                    type="button"
                    @click="if (selectedGeneration && !loadingEngines) open = !open"
                    :disabled="!selectedGeneration || loadingEngines"
                    class="w-full h-12 border border-gray-300 bg-white pl-12 pr-10 text-left text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100 disabled:text-gray-400"
                >
                    <span x-show="!selectedEngineLabel" class="block pr-6">
                        <span x-text="loadingEngines ? 'Loading engines...' : 'Select engine'"></span>
                    </span>

                    <span
                        x-show="selectedEngineLabel"
                        class="block pr-6 truncate"
                        x-text="selectedEngineLabel"
                    ></span>
                </button>

                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </span>

                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition
                    class="absolute left-0 right-0 mt-2 bg-white border border-gray-300 shadow-lg z-30 overflow-hidden"
                >
                    <div class="max-h-[340px] overflow-y-auto">
                        <template x-if="loadingEngines">
                            <div class="p-4 text-sm text-gray-500">Loading engines...</div>
                        </template>

                        <template x-if="!loadingEngines && (!engines || engines.length === 0)">
                            <div class="p-4 text-sm text-gray-500">No engines available.</div>
                        </template>

                        <template x-for="group in engines" :key="group.fuel_type">
                            <div>
                                <div class="sticky top-0 z-10 bg-gray-100 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-gray-600 border-b border-gray-200">
                                    <span x-text="group.fuel_type"></span>
                                </div>

                                <template x-for="engine in group.options" :key="engine.id">
                                    <button
                                        type="button"
                                        @click="selectEngine(engine); open = false"
                                        class="w-full text-left px-4 py-3 border-b border-gray-100 hover:bg-blue-50"
                                        :class="String(selectedEngine) === String(engine.id) ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700'"
                                    >
                                        <span class="block" x-text="engine.label"></span>
                                    </button>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- SEARCH --}}
            <div class="min-w-0 md:col-span-1">
                <button
                    type="submit"
                    :disabled="!selectedVehicleKey"
                    class="w-full h-12 bg-blue-600 hover:bg-blue-700 text-white font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed"
                >
                    Search
                </button>
            </div>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('shop') }}" class="text-sm uppercase text-blue-300 hover:text-white hover:underline">
                Can’t find your car in the catalogue?
            </a>
        </div>
    </div>
</div>

<script>
    function vehicleSelector() {
        return {
            makes: [],
            models: [],
            engines: [],

            selectedMake: '',
            selectedModel: '',
            selectedGeneration: '',
            selectedEngine: '',
            selectedVehicleKey: '',

            selectedMakeLabel: '',
            selectedModelLabel: '',
            selectedGenerationLabel: '',
            selectedEngineLabel: '',

            loadingModels: false,
            loadingGenerations: false,
            loadingEngines: false,

            modelSearch: '',
            activeModelId: '',

            async init() {
                await this.loadMakes();
            },

            async loadMakes() {
                try {
                    const response = await fetch('{{ route('vehicle.makes') }}');
                    this.makes = await response.json();
                } catch (error) {
                    console.error(error);
                    this.makes = [];
                }
            },

            async onMakeChange() {
                this.selectedModel = '';
                this.selectedGeneration = '';
                this.selectedEngine = '';
                this.selectedVehicleKey = '';

                this.selectedModelLabel = '';
                this.selectedGenerationLabel = '';
                this.selectedEngineLabel = '';

                this.models = [];
                this.engines = [];

                this.modelSearch = '';
                this.activeModelId = '';

                const make = this.makes.find(item => String(item.id) === String(this.selectedMake));
                this.selectedMakeLabel = make ? make.name : '';

                if (!this.selectedMake) return;

                this.loadingModels = true;

                try {
                    const response = await fetch(`{{ route('vehicle.models') }}?make_id=${this.selectedMake}`);
                    const data = await response.json();

                    this.models = data.map(model => ({
                        ...model,
                        generations: [],
                        generationsLoaded: false,
                    }));
                } catch (error) {
                    console.error(error);
                    this.models = [];
                } finally {
                    this.loadingModels = false;
                }
            },

            async toggleModel(model) {
                if (String(this.activeModelId) === String(model.id)) {
                    this.activeModelId = '';
                    return;
                }

                this.activeModelId = model.id;
                this.selectedModel = model.id;
                this.selectedModelLabel = model.name;
                this.selectedGeneration = '';
                this.selectedGenerationLabel = '';
                this.selectedEngine = '';
                this.selectedEngineLabel = '';
                this.selectedVehicleKey = '';
                this.engines = [];

                if (model.generationsLoaded) {
                    return;
                }

                this.loadingGenerations = true;

                try {
                    const response = await fetch(`{{ route('vehicle.generations') }}?model_id=${model.id}`);
                    model.generations = await response.json();
                    model.generationsLoaded = true;
                } catch (error) {
                    console.error(error);
                    model.generations = [];
                } finally {
                    this.loadingGenerations = false;
                }
            },

            isModelExpanded(modelId) {
                return String(this.activeModelId) === String(modelId);
            },

            async selectGeneration(generation) {
                this.selectedGeneration = generation.id;
                this.selectedGenerationLabel = generation.label;
                this.selectedEngine = '';
                this.selectedEngineLabel = '';
                this.selectedVehicleKey = '';
                this.engines = [];

                this.loadingEngines = true;

                try {
                    const response = await fetch(`{{ route('vehicle.engines') }}?generation_id=${generation.id}`);
                    this.engines = await response.json();
                } catch (error) {
                    console.error(error);
                    this.engines = [];
                } finally {
                    this.loadingEngines = false;
                }
            },

            selectEngine(engine) {
                this.selectedEngine = engine ? engine.id : '';
                this.selectedEngineLabel = engine ? engine.label : '';
                this.selectedVehicleKey = engine ? engine.vehicle_key : '';
            },

            goToVehiclePage() {
                if (!this.selectedVehicleKey) return;

                window.location.href = `/shop/vehicle/${this.selectedVehicleKey}`;
            },

            filteredModels() {
                const term = this.modelSearch.trim().toLowerCase();

                if (!term) {
                    return this.models;
                }

                return this.models.filter(model =>
                    (model.name || '').toLowerCase().includes(term)
                );
            }
        };
    }
</script>