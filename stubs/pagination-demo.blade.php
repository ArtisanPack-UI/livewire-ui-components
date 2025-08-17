<?php

use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use ArtisanPack\LivewireUiComponents\Traits\Toast;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

new class extends Component {
    use Toast;

    // Per-page values for different demos
    public int $defaultPerPage = 10;
    public int $compactPerPage = 5;
    public int $advancedPerPage = 15;
    public int $minimalPerPage = 8;
    public int $simplePerPage = 6;

    // Generate sample data for demonstrations
    public function generateSampleData(int $total = 150): Collection
    {
        $departments = ['Engineering', 'Marketing', 'Sales', 'HR', 'Finance', 'Operations', 'Design', 'Support'];
        $positions = ['Manager', 'Developer', 'Analyst', 'Coordinator', 'Specialist', 'Director', 'Associate', 'Lead'];
        $firstNames = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emma', 'James', 'Lisa', 'Robert', 'Maria', 'William', 'Jennifer', 'Richard', 'Jessica', 'Thomas', 'Ashley'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Taylor'];

        return collect(range(1, $total))->map(function ($i) use ($departments, $positions, $firstNames, $lastNames) {
            return [
                'id' => $i,
                'name' => $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)],
                'email' => strtolower(str_replace(' ', '.', $firstNames[array_rand($firstNames)] . '.' . $lastNames[array_rand($lastNames)])) . '@company.com',
                'department' => $departments[array_rand($departments)],
                'position' => $positions[array_rand($positions)],
                'salary' => rand(40000, 150000),
                'hire_date' => now()->subDays(rand(30, 1825))->format('Y-m-d'),
                'status' => rand(0, 10) > 8 ? 'Inactive' : 'Active',
            ];
        });
    }

    // Create paginated collections for each variant
    public function defaultUsers(): LengthAwarePaginator
    {
        $data = $this->generateSampleData();
        return new LengthAwarePaginator(
            $data->forPage(1, $this->defaultPerPage),
            $data->count(),
            $this->defaultPerPage,
            1,
            ['path' => request()->url(), 'pageName' => 'default_page']
        );
    }

    public function compactUsers(): LengthAwarePaginator
    {
        $data = $this->generateSampleData(75);
        return new LengthAwarePaginator(
            $data->forPage(1, $this->compactPerPage),
            $data->count(),
            $this->compactPerPage,
            1,
            ['path' => request()->url(), 'pageName' => 'compact_page']
        );
    }

    public function advancedUsers(): LengthAwarePaginator
    {
        $data = $this->generateSampleData(500);
        return new LengthAwarePaginator(
            $data->forPage(1, $this->advancedPerPage),
            $data->count(),
            $this->advancedPerPage,
            1,
            ['path' => request()->url(), 'pageName' => 'advanced_page']
        );
    }

    public function minimalPosts(): LengthAwarePaginator
    {
        $posts = collect(range(1, 100))->map(function ($i) {
            return [
                'id' => $i,
                'title' => 'Blog Post #' . $i . ': ' . fake()->sentence(4),
                'excerpt' => fake()->paragraph(2),
                'author' => fake()->name(),
                'published_at' => now()->subDays(rand(1, 365))->format('M j, Y'),
                'category' => fake()->randomElement(['Technology', 'Business', 'Design', 'Marketing', 'Development']),
            ];
        });

        return new LengthAwarePaginator(
            $posts->forPage(1, $this->minimalPerPage),
            $posts->count(),
            $this->minimalPerPage,
            1,
            ['path' => request()->url(), 'pageName' => 'minimal_page']
        );
    }

    public function simpleProducts(): LengthAwarePaginator
    {
        $products = collect(range(1, 50))->map(function ($i) {
            return [
                'id' => $i,
                'name' => 'Product ' . $i,
                'price' => '$' . number_format(rand(10, 999), 2),
                'stock' => rand(0, 100),
                'category' => fake()->randomElement(['Electronics', 'Clothing', 'Books', 'Home', 'Sports']),
            ];
        });

        return new LengthAwarePaginator(
            $products->forPage(1, $this->simplePerPage),
            $products->count(),
            $this->simplePerPage,
            1,
            ['path' => request()->url(), 'pageName' => 'simple_page']
        );
    }

    public function with(): array
    {
        return [
            'defaultUsers' => $this->defaultUsers(),
            'compactUsers' => $this->compactUsers(),
            'advancedUsers' => $this->advancedUsers(),
            'minimalPosts' => $this->minimalPosts(),
            'simpleProducts' => $this->simpleProducts(),
        ];
    }
}; ?>

<div>
    <!-- PAGE HEADER -->
    <x-artisanpack-header title="Pagination Component Variants" separator>
        <x-slot:subtitle>
            Explore different pagination variants for various use cases - from simple navigation to feature-rich data tables.
        </x-slot:subtitle>
    </x-artisanpack-header>

    <div class="space-y-12">
        
        <!-- DEFAULT VARIANT -->
        <x-artisanpack-card title="Default Variant" subtitle="Enhanced standard pagination with per-page selection and page information" shadow>
            <x-slot:menu>
                <x-artisanpack-badge value="Default" class="badge-primary" />
            </x-slot:menu>
            
            <div class="mb-4">
                <p class="text-base-content/70">
                    <strong>Best for:</strong> Standard data tables and general-purpose applications. 
                    Includes per-page selection, page numbers with ellipsis, and detailed page information.
                </p>
            </div>

            <!-- Sample table with default pagination -->
            <x-artisanpack-table 
                :rows="$defaultUsers" 
                :headers="[
                    ['key' => 'id', 'label' => '#', 'class' => 'w-16'],
                    ['key' => 'name', 'label' => 'Employee'],
                    ['key' => 'department', 'label' => 'Department'],
                    ['key' => 'position', 'label' => 'Position'],
                    ['key' => 'status', 'label' => 'Status', 'class' => 'w-24']
                ]"
            />

            <x-artisanpack-pagination :rows="$defaultUsers" wire:model.live="defaultPerPage" />
        </x-artisanpack-card>

        <!-- SIMPLE VARIANT -->
        <x-artisanpack-card title="Simple Variant" subtitle="Minimal navigation with previous/next buttons only" shadow>
            <x-slot:menu>
                <x-artisanpack-badge value="Simple" class="badge-secondary" />
            </x-slot:menu>
            
            <div class="mb-4">
                <p class="text-base-content/70">
                    <strong>Best for:</strong> Mobile interfaces, basic data browsing, and minimal UI designs. 
                    Shows only previous/next buttons with current page indicator.
                </p>
            </div>

            <!-- Sample product list with simple pagination -->
            <div class="grid gap-4 mb-6">
                @foreach($simpleProducts as $product)
                <div class="flex items-center justify-between p-4 border border-base-300 rounded-lg">
                    <div>
                        <h3 class="font-semibold">{{ $product['name'] }}</h3>
                        <p class="text-sm text-base-content/70">{{ $product['category'] }}</p>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-primary">{{ $product['price'] }}</div>
                        <div class="text-sm text-base-content/70">Stock: {{ $product['stock'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <x-artisanpack-pagination :rows="$simpleProducts" wire:model.live="simplePerPage" :simple="true" />
        </x-artisanpack-card>

        <!-- COMPACT VARIANT -->
        <x-artisanpack-card title="Compact Variant" subtitle="Mobile-optimized with condensed buttons and icons" shadow>
            <x-slot:menu>
                <x-artisanpack-badge value="Compact" class="badge-accent" />
            </x-slot:menu>
            
            <div class="mb-4">
                <p class="text-base-content/70">
                    <strong>Best for:</strong> Mobile devices, tight spaces, and responsive designs. 
                    Features condensed buttons with icons and limited page numbers.
                </p>
            </div>

            <!-- Sample mobile-friendly user list -->
            <div class="space-y-2 mb-6">
                @foreach($compactUsers as $user)
                <div class="flex items-center gap-3 p-3 bg-base-100 border border-base-300 rounded-lg">
                    <div class="avatar placeholder">
                        <div class="bg-primary text-primary-content rounded-full w-10">
                            <span class="text-xs">{{ substr($user['name'], 0, 2) }}</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold truncate">{{ $user['name'] }}</div>
                        <div class="text-sm text-base-content/70 truncate">{{ $user['email'] }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-medium">{{ $user['department'] }}</div>
                        <x-artisanpack-badge value="{{ $user['status'] }}" 
                            class="{{ $user['status'] === 'Active' ? 'badge-success' : 'badge-error' }} badge-sm" />
                    </div>
                </div>
                @endforeach
            </div>

            <x-artisanpack-pagination :rows="$compactUsers" wire:model.live="compactPerPage" :compact="true" :size="'sm'" />
        </x-artisanpack-card>

        <!-- ADVANCED VARIANT -->
        <x-artisanpack-card title="Advanced Variant" subtitle="Feature-rich pagination with jump-to-page and bulk navigation" shadow>
            <x-slot:menu>
                <x-artisanpack-badge value="Advanced" class="badge-warning" />
            </x-slot:menu>
            
            <div class="mb-4">
                <p class="text-base-content/70">
                    <strong>Best for:</strong> Data-heavy applications, admin panels, and power users. 
                    Includes jump-to-page input, bulk navigation, and extended controls.
                </p>
            </div>

            <!-- Sample detailed data table -->
            <x-artisanpack-table 
                :rows="$advancedUsers" 
                :headers="[
                    ['key' => 'id', 'label' => 'ID', 'class' => 'w-16'],
                    ['key' => 'name', 'label' => 'Full Name'],
                    ['key' => 'email', 'label' => 'Email'],
                    ['key' => 'department', 'label' => 'Department'],
                    ['key' => 'position', 'label' => 'Position'],
                    ['key' => 'salary', 'label' => 'Salary', 'class' => 'w-32'],
                    ['key' => 'hire_date', 'label' => 'Hired', 'class' => 'w-32'],
                    ['key' => 'status', 'label' => 'Status', 'class' => 'w-24']
                ]"
            >
                @scope('cell_salary', $user)
                    ${{ number_format($user['salary']) }}
                @endscope
                
                @scope('cell_status', $user)
                    <x-artisanpack-badge value="{{ $user['status'] }}" 
                        class="{{ $user['status'] === 'Active' ? 'badge-success' : 'badge-error' }} badge-sm" />
                @endscope
            </x-artisanpack-table>

            <x-artisanpack-pagination 
                :rows="$advancedUsers" 
                wire:model.live="advancedPerPage" 
                :advanced="true"
                :show-jump-to="true"
                :on-each-side="2"
                :per-page-values="[10, 15, 25, 50, 100]"
                :page-info-template="'Records {from} to {to} of {total} total employees'"
            />
        </x-artisanpack-card>

        <!-- MINIMAL VARIANT -->
        <x-artisanpack-card title="Minimal Variant" subtitle="Clean, distraction-free design with elegant typography" shadow>
            <x-slot:menu>
                <x-artisanpack-badge value="Minimal" class="badge-info" />
            </x-slot:menu>
            
            <div class="mb-4">
                <p class="text-base-content/70">
                    <strong>Best for:</strong> Blog sites, content platforms, and clean interfaces. 
                    Features just page numbers with subtle typography and elegant hover effects.
                </p>
            </div>

            <!-- Sample blog post list -->
            <div class="space-y-6 mb-8">
                @foreach($minimalPosts as $post)
                <article class="prose prose-sm max-w-none">
                    <h3 class="mb-2 text-lg font-bold text-base-content">{{ $post['title'] }}</h3>
                    <div class="flex items-center gap-4 mb-3 text-sm text-base-content/70">
                        <span>By {{ $post['author'] }}</span>
                        <span>{{ $post['published_at'] }}</span>
                        <x-artisanpack-badge value="{{ $post['category'] }}" class="badge-ghost badge-sm" />
                    </div>
                    <p class="text-base-content/80">{{ $post['excerpt'] }}</p>
                </article>
                @endforeach
            </div>

            <x-artisanpack-pagination 
                :rows="$minimalPosts" 
                wire:model.live="minimalPerPage" 
                :minimal="true"
                :hide-per-page="true"
                :on-each-side="2"
            />
        </x-artisanpack-card>

        <!-- IMPLEMENTATION GUIDE -->
        <x-artisanpack-card title="Implementation Guide" subtitle="How to use these pagination variants in your application" shadow>
            <div class="space-y-6">
                
                <div>
                    <h3 class="text-lg font-semibold mb-3">Quick Start</h3>
                    <div class="mockup-code">
                        <pre data-prefix="1"><code>&lt;x-artisanpack-pagination :rows="$users" wire:model.live="perPage" /&gt;</code></pre>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    
                    <div>
                        <h4 class="font-semibold mb-2">Simple Variant</h4>
                        <div class="mockup-code text-xs">
                            <pre data-prefix="$"><code>&lt;x-artisanpack-pagination</code></pre>
                            <pre data-prefix=" "><code>    :rows="$products"</code></pre>
                            <pre data-prefix=" "><code>    wire:model.live="perPage"</code></pre>
                            <pre data-prefix=" "><code>    :simple="true" /&gt;</code></pre>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold mb-2">Compact Variant</h4>
                        <div class="mockup-code text-xs">
                            <pre data-prefix="$"><code>&lt;x-artisanpack-pagination</code></pre>
                            <pre data-prefix=" "><code>    :rows="$items"</code></pre>
                            <pre data-prefix=" "><code>    :compact="true"</code></pre>
                            <pre data-prefix=" "><code>    :size="'sm'" /&gt;</code></pre>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold mb-2">Advanced Variant</h4>
                        <div class="mockup-code text-xs">
                            <pre data-prefix="$"><code>&lt;x-artisanpack-pagination</code></pre>
                            <pre data-prefix=" "><code>    :rows="$reports"</code></pre>
                            <pre data-prefix=" "><code>    :advanced="true"</code></pre>
                            <pre data-prefix=" "><code>    :show-jump-to="true" /&gt;</code></pre>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold mb-2">Minimal Variant</h4>
                        <div class="mockup-code text-xs">
                            <pre data-prefix="$"><code>&lt;x-artisanpack-pagination</code></pre>
                            <pre data-prefix=" "><code>    :rows="$posts"</code></pre>
                            <pre data-prefix=" "><code>    :minimal="true"</code></pre>
                            <pre data-prefix=" "><code>    :hide-per-page="true" /&gt;</code></pre>
                        </div>
                    </div>
                    
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-3">Available Props</h3>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    <th>Prop</th>
                                    <th>Type</th>
                                    <th>Default</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>simple</code></td>
                                    <td>bool</td>
                                    <td>false</td>
                                    <td>Enable simple variant (prev/next only)</td>
                                </tr>
                                <tr>
                                    <td><code>compact</code></td>
                                    <td>bool</td>
                                    <td>false</td>
                                    <td>Enable compact variant (mobile-optimized)</td>
                                </tr>
                                <tr>
                                    <td><code>advanced</code></td>
                                    <td>bool</td>
                                    <td>false</td>
                                    <td>Enable advanced variant (feature-rich)</td>
                                </tr>
                                <tr>
                                    <td><code>minimal</code></td>
                                    <td>bool</td>
                                    <td>false</td>
                                    <td>Enable minimal variant (clean typography)</td>
                                </tr>
                                <tr>
                                    <td><code>size</code></td>
                                    <td>string</td>
                                    <td>'default'</td>
                                    <td>Size variant ('sm', 'default', 'lg')</td>
                                </tr>
                                <tr>
                                    <td><code>show-jump-to</code></td>
                                    <td>bool</td>
                                    <td>false</td>
                                    <td>Show jump-to-page input (advanced variant)</td>
                                </tr>
                                <tr>
                                    <td><code>on-each-side</code></td>
                                    <td>int</td>
                                    <td>1</td>
                                    <td>Number of page links on each side</td>
                                </tr>
                                <tr>
                                    <td><code>hide-per-page</code></td>
                                    <td>bool</td>
                                    <td>false</td>
                                    <td>Hide the per-page selector</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </x-artisanpack-card>
    </div>
</div>