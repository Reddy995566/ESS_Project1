<?php $__env->startSection('step_title', 'Step 3: Categories & Organization'); ?>
<?php $__env->startSection('step_description', 'Organize your product with categories, brands, and tags'); ?>

<?php $__env->startSection('step_content'); ?>
    <?php
        $currentStep = 3;
        $prevStepRoute = '/seller/products/' . $product->id . '/edit?step=2';
    ?>

    <form id="stepForm" action="<?php echo e(route('seller.products.update', $product->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <input type="hidden" name="step" value="3">

        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-yellow-50">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-orange-600 to-yellow-600 rounded-lg flex items-center justify-center shadow-lg">
                        <span class="text-white text-xl">🏷️</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Categories & Organization</h2>
                        <p class="text-gray-600 font-medium">Organize your product for better discovery</p>
                    </div>
                </div>
            </div>

            <div class="p-8 space-y-6">
                <!-- Category, Fabric & SKU (Same Line - 3 Columns) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Category -->
                    <div x-data="categoryDropdown()">
                        <label class="block text-sm font-semibold text-gray-800 mb-2">Category <span
                                class="text-red-500">*</span></label>
                        <div class="relative w-full">
                            <button @click="open = !open" type="button"
                                class="w-full px-4 py-3 text-left border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400 flex justify-between items-center">
                                <span x-text="selectedText || 'Select Category'" class="truncate"></span>
                                <svg class="w-4 h-4 ml-2 text-gray-500 transition-transform duration-200 flex-shrink-0"
                                    :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                class="absolute z-20 mt-2 w-full bg-white border-2 border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                <input type="text" placeholder="Search category..." x-model="search"
                                    class="w-full px-3 py-2 border-b border-gray-200 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <template x-for="category in filteredCategories" :key="category.id">
                                    <div @click="selectCategory(category)"
                                        class="px-4 py-2 cursor-pointer hover:bg-blue-100" x-html="category.displayName">
                                    </div>
                                </template>
                                <div x-show="filteredCategories.length === 0" class="px-4 py-2 text-gray-400">No results
                                    found.</div>
                            </div>
                            <select name="category_id" x-ref="hiddenSelect" class="hidden" required>
                                <option value="">Select Category</option>
                                <?php $__currentLoopData = $allCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Choose main category</p>
                        <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Fabric -->
                    <div x-data="fabricDropdown()">
                        <label class="block text-sm font-semibold text-gray-800 mb-2">Fabric <span
                                class="text-gray-500 text-xs">(Optional)</span></label>
                        <div class="relative w-full">
                            <button @click="open = !open" type="button"
                                class="w-full px-4 py-3 text-left border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400 flex justify-between items-center">
                                <span x-text="selectedText || 'Select Fabric'" class="truncate"></span>
                                <svg class="w-4 h-4 ml-2 text-gray-500 transition-transform duration-200 flex-shrink-0"
                                    :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                class="absolute z-20 mt-2 w-full bg-white border-2 border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                <input type="text" placeholder="Search fabric..." x-model="search"
                                    class="w-full px-3 py-2 border-b border-gray-200 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <template x-for="fabric in filteredFabrics" :key="fabric.id">
                                    <div @click="selectFabric(fabric)" class="px-4 py-2 cursor-pointer hover:bg-teal-100"
                                        x-text="fabric.name"></div>
                                </template>
                                <div x-show="filteredFabrics.length === 0" class="px-4 py-2 text-gray-400">No results found.
                                </div>
                            </div>
                            <select name="fabric_id" x-ref="hiddenSelect" class="hidden">
                                <option value="">Select Fabric</option>
                                <?php $__currentLoopData = $fabrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fabric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($fabric->id); ?>"><?php echo e($fabric->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Optional fabric type</p>
                        <?php $__errorArgs = ['fabric_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- SKU -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-2">SKU <span
                                class="text-blue-500 text-xs">(Auto)</span></label>
                        <input type="text" name="sku" id="productSku" readonly value="<?php echo e(old('sku', $product->sku ?? '')); ?>"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                            placeholder="PRD-XXXXXXXX">
                        <p class="text-xs text-blue-500 mt-1">✨ Auto-generated code</p>
                        <?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Collections -->
                <div x-data="collectionsDropdown()">
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Collections <span
                            class="text-gray-500 text-xs">(Multiple)</span></label>
                    <div class="relative w-full">
                        <button @click="open = !open" type="button"
                            class="w-full px-4 py-3 text-left border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400 flex justify-between items-center min-h-[48px]">
                            <div class="flex flex-wrap gap-1" x-show="selectedCollections.length > 0">
                                <template x-for="collection in selectedCollections" :key="collection.id">
                                    <span
                                        class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-sm flex items-center gap-1">
                                        <span x-text="collection.name"></span>
                                        <button type="button" @click.stop="removeCollection(collection)"
                                            class="hover:text-purple-900">×</button>
                                    </span>
                                </template>
                            </div>
                            <span x-show="selectedCollections.length === 0" class="text-gray-500">Select Collections</span>
                            <svg class="w-4 h-4 ml-2 text-gray-500 transition-transform duration-200"
                                :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute z-20 mt-2 w-full bg-white border-2 border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            <input type="text" placeholder="Search collections..." x-model="search"
                                class="w-full px-3 py-2 border-b border-gray-200 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <template x-for="collection in filteredCollections" :key="collection.id">
                                <div @click="toggleCollection(collection)"
                                    class="px-4 py-2 cursor-pointer hover:bg-purple-100 flex items-center justify-between"
                                    :class="{ 'bg-purple-50': isSelected(collection) }">
                                    <span x-text="collection.name"></span>
                                    <span x-show="isSelected(collection)" class="text-purple-600">✓</span>
                                </div>
                            </template>
                            <div x-show="filteredCollections.length === 0" class="px-4 py-2 text-gray-400">No results found.
                            </div>
                        </div>
                        <select name="collections[]" multiple class="hidden">
                            <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($collection->id); ?>">
                                    <?php echo e($collection->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <!-- Tags -->
                <div x-data="tagsDropdown()">
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Tags <span
                            class="text-gray-500 text-xs">(Multiple)</span></label>
                    <div class="relative w-full">
                        <button @click="open = !open" type="button"
                            class="w-full px-4 py-3 text-left border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-gray-400 flex justify-between items-center min-h-[48px]">
                            <div class="flex flex-wrap gap-1" x-show="selectedTags.length > 0">
                                <template x-for="tag in selectedTags" :key="tag.id">
                                    <span
                                        class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-sm flex items-center gap-1">
                                        <span x-text="tag.name"></span>
                                        <button type="button" @click.stop="removeTag(tag)"
                                            class="hover:text-blue-900">×</button>
                                    </span>
                                </template>
                            </div>
                            <span x-show="selectedTags.length === 0" class="text-gray-500">Select Tags</span>
                            <svg class="w-4 h-4 ml-2 text-gray-500 transition-transform duration-200"
                                :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute z-20 mt-2 w-full bg-white border-2 border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            <input type="text" placeholder="Search tags..." x-model="search"
                                class="w-full px-3 py-2 border-b border-gray-200 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <template x-for="tag in filteredTags" :key="tag.id">
                                <div @click="toggleTag(tag)"
                                    class="px-4 py-2 cursor-pointer hover:bg-blue-100 flex items-center justify-between"
                                    :class="{ 'bg-blue-50': isSelected(tag) }">
                                    <span x-text="tag.name"></span>
                                    <span x-show="isSelected(tag)" class="text-blue-600">✓</span>
                                </div>
                            </template>
                            <div x-show="filteredTags.length === 0" class="px-4 py-2 text-gray-400">No results found.</div>
                        </div>
                        <select name="tags[]" multiple class="hidden">
                            <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($tag->id); ?>">
                                    <?php echo e($tag->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Add multiple tags for better product discovery</p>
                </div>

            </div>
        </div>
    </form>

    <?php $__env->startPush('scripts'); ?>
        <script>
            // Test alert to confirm JavaScript is loading
            console.log('✅ Step 3 JavaScript loaded!');
            window.addEventListener('DOMContentLoaded', function () {
                console.log('✅ DOM loaded - Alpine.js should be working');
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        <script>
            // Category Dropdown (Single Selection)
            function categoryDropdown() {
                return {
                    open: false,
                    search: '',
                    selectedText: '',
                    categories: [
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                                        { id: '<?php echo e($cat->id); ?>', name: '<?php echo e(addslashes($cat->name)); ?>', displayName: '<?php echo e(addslashes($cat->name)); ?>' },
                            <?php $__currentLoopData = $cat->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                                                                                    { id: '<?php echo e($child->id); ?>', name: '<?php echo e(addslashes($child->name)); ?>', displayName: '&nbsp;&nbsp;&nbsp;→ <?php echo e(addslashes($child->name)); ?>' },
                                <?php $__currentLoopData = $child->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grandchild): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    { id: '<?php echo e($grandchild->id); ?>', name: '<?php echo e(addslashes($grandchild->name)); ?>', displayName: '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;→→ <?php echo e(addslashes($grandchild->name)); ?>' },
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                        ],

                    get filteredCategories() {
                        if (!this.search) return this.categories;
                        const searchLower = this.search.toLowerCase();
                        return this.categories.filter(category =>
                            category.name.toLowerCase().includes(searchLower)
                        );
                    },

                    selectCategory(category) {
                        this.selectedText = category.displayName.replace(/&nbsp;/g, ' ').replace(/→/g, '').trim();
                        this.$refs.hiddenSelect.value = category.id;
                        this.open = false;
                        this.search = '';

                        // Generate SKU when category is selected
                        generateSKU();
                    },

                    init() {
                        // Initialize selected category from product model
                        const existingCategoryId = '<?php echo e(old('category_id', $product->category_id ?? '')); ?>';
                        if (existingCategoryId) {
                            const category = this.categories.find(c => c.id == existingCategoryId);
                            if (category) {
                                this.selectedText = category.displayName.replace(/&nbsp;/g, ' ').replace(/→/g, '').trim();
                                this.$refs.hiddenSelect.value = category.id;
                            }
                        }
                    }
                };
            }

            // Fabric Dropdown (Single Selection)
            function fabricDropdown() {
                return {
                    open: false,
                    search: '',
                    selectedText: '',
                    fabrics: <?php echo json_encode($fabrics, 15, 512) ?>,

                    get filteredFabrics() {
                        return this.fabrics.filter(fabric =>
                            fabric.name.toLowerCase().includes(this.search.toLowerCase())
                        );
                    },

                    selectFabric(fabric) {
                        this.selectedText = fabric.name;
                        this.$refs.hiddenSelect.value = fabric.id;
                        this.open = false;
                        this.search = '';
                    },

                    init() {
                        // Initialize selected fabric from product model
                        const existingFabricId = '<?php echo e(old('fabric_id', $product->fabric_id ?? '')); ?>';
                        if (existingFabricId) {
                            const fabric = this.fabrics.find(f => f.id == existingFabricId);
                            if (fabric) {
                                this.selectedText = fabric.name;
                                this.$refs.hiddenSelect.value = fabric.id;
                            }
                        }
                    }
                };
            }

            // Brand Dropdown (Single Selection)
            function brandDropdown() {
                return {
                    open: false,
                    search: '',
                    selectedText: '',
                    brands: <?php echo json_encode($brands, 15, 512) ?>,

                    get filteredBrands() {
                        return this.brands.filter(brand =>
                            brand.name.toLowerCase().includes(this.search.toLowerCase())
                        );
                    },

                    selectBrand(brand) {
                        this.selectedText = brand.name;
                        this.$refs.hiddenSelect.value = brand.id;
                        this.open = false;
                        this.search = '';

                        // Generate SKU when brand is selected
                        generateSKU();
                    },

                    init() {
                        // Initialize selected brand from product model
                        const existingBrandId = '<?php echo e(old('brand_id', $product->brand_id ?? '')); ?>';
                        if (existingBrandId) {
                            const brand = this.brands.find(b => b.id == existingBrandId);
                            if (brand) {
                                this.selectedText = brand.name;
                                this.$refs.hiddenSelect.value = brand.id;
                            }
                        }
                    }
                };
            }

            function collectionsDropdown() {
                return {
                    open: false,
                    search: '',
                    selectedCollections: [],
                    collections: <?php echo json_encode($collections, 15, 512) ?>,

                    get filteredCollections() {
                        return this.collections.filter(collection =>
                            collection.name.toLowerCase().includes(this.search.toLowerCase())
                        );
                    },

                    toggleCollection(collection) {
                        console.log('🔄 Toggle collection:', collection.name);
                        if (this.isSelected(collection)) {
                            this.removeCollection(collection);
                        } else {
                            this.selectedCollections.push(collection);
                        }
                        console.log('📝 Selected collections now:', this.selectedCollections.map(c => c.name));
                        this.updateSelectField();
                    },

                    removeCollection(collection) {
                        this.selectedCollections = this.selectedCollections.filter(c => c.id !== collection.id);
                        this.updateSelectField();
                    },

                    isSelected(collection) {
                        return this.selectedCollections.find(c => c.id === collection.id) !== undefined;
                    },

                    updateSelectField() {
                        const selectElement = document.querySelector('select[name="collections[]"]');
                        if (selectElement) {
                            // Clear all selections first
                            Array.from(selectElement.options).forEach(option => {
                                option.selected = false;
                            });
                            // Then select the ones in our array
                            this.selectedCollections.forEach(collection => {
                                const option = selectElement.querySelector(`option[value="${collection.id}"]`);
                                if (option) {
                                    option.selected = true;
                                }
                            });
                            console.log('Collections updated:', this.selectedCollections.map(c => c.id));
                        }
                    },

                    init() {
                        // Get existing collection IDs from product model
                        // Note: We use collections() method because there is a 'collections' column in products table causing collision
                        // We pluck 'collections.id' to avoid ambiguity column error
                        const existingCollections = <?php echo json_encode($product->collections()->pluck('collections.id')->toArray() ?? [], 15, 512) ?>;
                        console.log('Existing collections from DB:', existingCollections);

                        const existingIds = existingCollections.map(id => parseInt(id));

                        // Filter collections that match the existing IDs
                        if (existingIds.length > 0) {
                            this.selectedCollections = this.collections.filter(c => {
                                const collectionId = parseInt(c.id);
                                return existingIds.includes(collectionId);
                            });
                        }

                        // Update the hidden select field after a short delay to ensure DOM is ready
                        this.$nextTick(() => {
                            this.updateSelectField();
                        });
                    }
                };
            }

            function tagsDropdown() {
                return {
                    open: false,
                    search: '',
                    selectedTags: [],
                    tags: <?php echo json_encode($tags, 15, 512) ?>,

                    get filteredTags() {
                        return this.tags.filter(tag =>
                            tag.name.toLowerCase().includes(this.search.toLowerCase())
                        );
                    },

                    toggleTag(tag) {
                        console.log('🔄 Toggle tag:', tag.name);
                        if (this.isSelected(tag)) {
                            this.removeTag(tag);
                        } else {
                            this.selectedTags.push(tag);
                        }
                        console.log('📝 Selected tags now:', this.selectedTags.map(t => t.name));
                        this.updateSelectField();
                    },

                    removeTag(tag) {
                        this.selectedTags = this.selectedTags.filter(t => t.id !== tag.id);
                        this.updateSelectField();
                    },

                    isSelected(tag) {
                        return this.selectedTags.find(t => t.id === tag.id) !== undefined;
                    },

                    updateSelectField() {
                        const selectElement = document.querySelector('select[name="tags[]"]');
                        if (selectElement) {
                            // Clear all selections first
                            Array.from(selectElement.options).forEach(option => {
                                option.selected = false;
                            });
                            // Then select the ones in our array
                            this.selectedTags.forEach(tag => {
                                const option = selectElement.querySelector(`option[value="${tag.id}"]`);
                                if (option) {
                                    option.selected = true;
                                }
                            });
                            console.log('Tags updated:', this.selectedTags.map(t => t.id));
                        }
                    },

                    init() {
                        // Get existing tag IDs from product model
                        // Note: We use tags() method because there is a 'tags' column in products table causing collision
                        // We pluck 'tags.id' to avoid ambiguity column error
                        const existingTags = <?php echo json_encode($product->tags()->pluck('tags.id')->toArray() ?? [], 15, 512) ?>;
                        console.log('Existing tags from DB:', existingTags);

                        const existingIds = existingTags.map(id => parseInt(id));

                        if (existingIds.length > 0) {
                            this.selectedTags = this.tags.filter(t => {
                                const tagId = parseInt(t.id);
                                return existingIds.includes(tagId);
                            });
                        }

                        // Update the hidden select field after a short delay to ensure DOM is ready
                        this.$nextTick(() => {
                            this.updateSelectField();
                        });
                    }
                };
            }

            // Initialize SKU on page load if empty
            window.addEventListener('DOMContentLoaded', function () {
                const skuField = document.getElementById('productSku');
                if (skuField && !skuField.value) {
                    generateSKU();
                }
            });

            // Generate unique SKU in format: NAME-BRAND-0001
            function generateSKU() {
                const skuField = document.getElementById('productSku');
                if (!skuField) return;

                // Get product name from session storage or from step1
                let productName = sessionStorage.getItem('productName') || '';
                if (!productName) {
                    // Try to get from step1 if we're on the same page
                    const nameField = document.getElementById('productName');
                    if (nameField) {
                        productName = nameField.value;
                    }
                }

                // Get selected brand name
                const brandSelect = document.querySelector('select[name="brand_id"]');
                let brandName = '';
                if (brandSelect && brandSelect.value) {
                    const selectedOption = brandSelect.options[brandSelect.selectedIndex];
                    brandName = selectedOption ? selectedOption.text : '';
                }

                // Generate name code (first 2 letters)
                let nameCode = 'XX';
                if (productName.length >= 2) {
                    nameCode = productName.substring(0, 2).toUpperCase().replace(/[^A-Z]/g, 'X');
                } else if (productName.length === 1) {
                    nameCode = productName.toUpperCase() + 'X';
                }

                // Generate brand code (first 2 letters)
                let brandCode = 'XX';
                if (brandName && brandName !== 'Select Brand') {
                    if (brandName.length >= 2) {
                        brandCode = brandName.substring(0, 2).toUpperCase().replace(/[^A-Z]/g, 'X');
                    } else if (brandName.length === 1) {
                        brandCode = brandName.toUpperCase() + 'X';
                    }
                }

                // Generate unique 4-digit number
                const timestamp = Date.now();
                const randomNum = Math.floor(Math.random() * 1000);
                const uniqueNumber = ((timestamp % 10000) + randomNum) % 10000;
                const numericPart = String(uniqueNumber).padStart(4, '0');

                // Create SKU: NAME-BRAND-0001
                const sku = `${nameCode}-${brandCode}-${numericPart}`;
                skuField.value = sku;
            }

            // ========================================
            // UPDATE BUTTON HANDLER - STEP 3 SPECIFIC
            // ========================================
            window.addEventListener('DOMContentLoaded', function () {
                const updateBtn = document.getElementById('updateBtn');
                const stepForm = document.getElementById('stepForm');

                if (updateBtn && stepForm) {
                    console.log('✅ Update button found, attaching handler');

                    // Remove any existing listeners
                    const newUpdateBtn = updateBtn.cloneNode(true);
                    updateBtn.parentNode.replaceChild(newUpdateBtn, updateBtn);

                    newUpdateBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();

                        console.log('🔵 Update button clicked!');

                        // Debug: Check select fields before submission
                        const collectionsSelect = document.querySelector('select[name="collections[]"]');
                        const tagsSelect = document.querySelector('select[name="tags[]"]');

                        console.log('📋 Collections select options:');
                        if (collectionsSelect) {
                            Array.from(collectionsSelect.options).forEach(opt => {
                                console.log(`  Option ${opt.value}: ${opt.text} - Selected: ${opt.selected}`);
                            });
                        }

                        console.log('📋 Tags select options:');
                        if (tagsSelect) {
                            Array.from(tagsSelect.options).forEach(opt => {
                                console.log(`  Option ${opt.value}: ${opt.text} - Selected: ${opt.selected}`);
                            });
                        }

                        // Show loading
                        newUpdateBtn.disabled = true;
                        const originalText = newUpdateBtn.innerHTML;
                        newUpdateBtn.innerHTML = '<svg class="animate-spin h-5 w-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Updating...';

                        // Wait for Alpine.js to update select fields
                        setTimeout(() => {
                            const formData = new FormData(stepForm);

                            console.log('📦 === FORM DATA ===');
                            for (let [key, value] of formData.entries()) {
                                console.log(`  ${key}: ${value}`);
                            }
                            console.log('📦 === END ===');

                            // Submit
                            fetch(stepForm.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                                .then(response => response.json())
                                .then(data => {
                                    console.log('✅ Server response:', data);

                                    if (data.success) {
                                        // Show success message
                                        const notification = document.createElement('div');
                                        notification.className = 'fixed top-4 right-4 px-6 py-3 rounded-lg text-white font-semibold z-50 bg-green-500';
                                        notification.textContent = data.message || 'Updated successfully!';
                                        document.body.appendChild(notification);

                                        // Reload same page to show updated data
                                        setTimeout(() => {
                                            window.location.reload();
                                        }, 1000);
                                    } else {
                                        console.error('❌ Update failed:', data);
                                        alert('❌ ' + (data.message || 'Update failed'));
                                        newUpdateBtn.disabled = false;
                                        newUpdateBtn.innerHTML = originalText;
                                    }
                                })
                                .catch(error => {
                                    console.error('❌ Error:', error);
                                    alert('❌ An error occurred');
                                    newUpdateBtn.disabled = false;
                                    newUpdateBtn.innerHTML = originalText;
                                });
                        }, 300);
                    });
                } else {
                    console.error('❌ Update button or form not found!');
                }
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('seller.products.edit._layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/seller/products/edit/step3.blade.php ENDPATH**/ ?>