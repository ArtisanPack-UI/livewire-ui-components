<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vertical Tabs Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="p-8 bg-gray-50">
    <h1 class="text-3xl font-bold mb-8">Vertical Tabs Implementation Test</h1>
    
    <!-- Horizontal Tabs (Default) -->
    <div class="mb-12">
        <h2 class="text-xl font-semibold mb-4">Horizontal Tabs (Default)</h2>
        <x-artisanpack-tabs>
            <x-artisanpack-tab name="h-tab1" label="Home">
                <div class="p-4">
                    <h3 class="text-lg font-bold mb-2">Home Content</h3>
                    <p>This is the home tab content in horizontal layout.</p>
                </div>
            </x-artisanpack-tab>
            <x-artisanpack-tab name="h-tab2" label="Profile">
                <div class="p-4">
                    <h3 class="text-lg font-bold mb-2">Profile Content</h3>
                    <p>This is the profile tab content in horizontal layout.</p>
                </div>
            </x-artisanpack-tab>
            <x-artisanpack-tab name="h-tab3" label="Settings">
                <div class="p-4">
                    <h3 class="text-lg font-bold mb-2">Settings Content</h3>
                    <p>This is the settings tab content in horizontal layout.</p>
                </div>
            </x-artisanpack-tab>
        </x-artisanpack-tabs>
    </div>

    <!-- Vertical Left Tabs -->
    <div class="mb-12">
        <h2 class="text-xl font-semibold mb-4">Vertical Left Tabs</h2>
        <div class="h-64">
            <x-artisanpack-tabs orientation="vertical-left">
                <x-artisanpack-tab name="vl-tab1" label="Dashboard">
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-2">Dashboard Content</h3>
                        <p>This is the dashboard tab content in vertical-left layout. The tabs are positioned on the left side.</p>
                    </div>
                </x-artisanpack-tab>
                <x-artisanpack-tab name="vl-tab2" label="Analytics">
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-2">Analytics Content</h3>
                        <p>This is the analytics tab content in vertical-left layout. Notice how the content area takes up the remaining space.</p>
                    </div>
                </x-artisanpack-tab>
                <x-artisanpack-tab name="vl-tab3" label="Reports">
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-2">Reports Content</h3>
                        <p>This is the reports tab content in vertical-left layout.</p>
                    </div>
                </x-artisanpack-tab>
            </x-artisanpack-tabs>
        </div>
    </div>

    <!-- Vertical Right Tabs -->
    <div class="mb-12">
        <h2 class="text-xl font-semibold mb-4">Vertical Right Tabs</h2>
        <div class="h-64">
            <x-artisanpack-tabs orientation="vertical-right">
                <x-artisanpack-tab name="vr-tab1" label="Messages">
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-2">Messages Content</h3>
                        <p>This is the messages tab content in vertical-right layout. The tabs are positioned on the right side.</p>
                    </div>
                </x-artisanpack-tab>
                <x-artisanpack-tab name="vr-tab2" label="Notifications">
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-2">Notifications Content</h3>
                        <p>This is the notifications tab content in vertical-right layout. Content appears before the tabs in the DOM.</p>
                    </div>
                </x-artisanpack-tab>
                <x-artisanpack-tab name="vr-tab3" label="History">
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-2">History Content</h3>
                        <p>This is the history tab content in vertical-right layout.</p>
                    </div>
                </x-artisanpack-tab>
            </x-artisanpack-tabs>
        </div>
    </div>

    <!-- Test with Alpine.js Integration -->
    <div class="mb-12">
        <h2 class="text-xl font-semibold mb-4">Alpine.js Integration Test (Vertical Left)</h2>
        <div x-data="{ activeTab: 'alpine-tab1' }" class="h-64">
            <x-artisanpack-tabs orientation="vertical-left" x-model="activeTab">
                <x-artisanpack-tab name="alpine-tab1" label="Tab A">
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-2">Tab A Content</h3>
                        <p>This tab is controlled by Alpine.js state.</p>
                    </div>
                </x-artisanpack-tab>
                <x-artisanpack-tab name="alpine-tab2" label="Tab B">
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-2">Tab B Content</h3>
                        <p>This tab is also controlled by Alpine.js state.</p>
                    </div>
                </x-artisanpack-tab>
            </x-artisanpack-tabs>
            
            <div class="mt-4">
                <button @click="activeTab = 'alpine-tab1'" class="px-4 py-2 bg-blue-500 text-white rounded mr-2">Show Tab A</button>
                <button @click="activeTab = 'alpine-tab2'" class="px-4 py-2 bg-green-500 text-white rounded">Show Tab B</button>
            </div>
        </div>
    </div>

    <!-- Custom Styling Test -->
    <div class="mb-12">
        <h2 class="text-xl font-semibold mb-4">Custom Styling Test (Vertical Right)</h2>
        <div class="h-80">
            <x-artisanpack-tabs 
                orientation="vertical-right"
                vertical-tabs-class="relative w-full flex flex-col lg:flex-row bg-base-100 rounded-lg shadow-lg"
                vertical-right-label-div-class="border-l-4 border-primary bg-primary/10 flex flex-col min-w-60 p-2"
                vertical-label-class="font-bold px-4 py-3 rounded-lg m-1 hover:bg-primary/20 transition-colors"
                vertical-right-active-class="bg-primary text-primary-content shadow-lg">
                
                <x-artisanpack-tab name="custom-tab1" label="Custom Style 1">
                    <div class="p-6 bg-base-50">
                        <h3 class="text-2xl font-bold mb-4 text-primary">Custom Styled Content</h3>
                        <p class="text-lg">This demonstrates custom styling capabilities with vertical-right orientation.</p>
                        <div class="mt-4 p-4 bg-info/20 rounded-lg">
                            <p class="text-info-content">Notice the custom background colors, borders, and spacing.</p>
                        </div>
                    </div>
                </x-artisanpack-tab>
                
                <x-artisanpack-tab name="custom-tab2" label="Custom Style 2">
                    <div class="p-6 bg-base-50">
                        <h3 class="text-2xl font-bold mb-4 text-secondary">Another Custom Tab</h3>
                        <p class="text-lg">Each tab can have different content while maintaining consistent styling.</p>
                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div class="p-3 bg-success/20 rounded-lg">
                                <h4 class="font-bold text-success">Feature 1</h4>
                                <p class="text-sm">Custom layouts work perfectly.</p>
                            </div>
                            <div class="p-3 bg-warning/20 rounded-lg">
                                <h4 class="font-bold text-warning">Feature 2</h4>
                                <p class="text-sm">Responsive design included.</p>
                            </div>
                        </div>
                    </div>
                </x-artisanpack-tab>
            </x-artisanpack-tabs>
        </div>
    </div>

    <!-- Edge Case Tests -->
    <div class="mb-12">
        <h2 class="text-xl font-semibold mb-4">Edge Case Tests</h2>
        
        <!-- Disabled and Hidden Tabs -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-2">Disabled and Hidden Tabs (Vertical Left)</h3>
            <div class="h-48">
                <x-artisanpack-tabs orientation="vertical-left">
                    <x-artisanpack-tab name="edge-tab1" label="Active Tab">
                        <div class="p-4">
                            <h4 class="font-bold">Active Tab Content</h4>
                            <p>This tab is fully functional.</p>
                        </div>
                    </x-artisanpack-tab>
                    <x-artisanpack-tab name="edge-tab2" label="Disabled Tab" :disabled="true">
                        <div class="p-4">
                            <h4 class="font-bold">Disabled Tab Content</h4>
                            <p>This tab should be disabled and unclickable.</p>
                        </div>
                    </x-artisanpack-tab>
                    <x-artisanpack-tab name="edge-tab3" label="Hidden Tab" :hidden="true">
                        <div class="p-4">
                            <h4 class="font-bold">Hidden Tab Content</h4>
                            <p>This tab should be hidden.</p>
                        </div>
                    </x-artisanpack-tab>
                    <x-artisanpack-tab name="edge-tab4" label="Normal Tab">
                        <div class="p-4">
                            <h4 class="font-bold">Normal Tab Content</h4>
                            <p>Another functional tab.</p>
                        </div>
                    </x-artisanpack-tab>
                </x-artisanpack-tabs>
            </div>
        </div>

        <!-- Mobile Responsiveness Test -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-2">Mobile Responsiveness (Resize window to test)</h3>
            <div class="h-64 border-2 border-dashed border-gray-300 rounded-lg">
                <x-artisanpack-tabs orientation="vertical-left">
                    <x-artisanpack-tab name="mobile-tab1" label="Mobile Test 1">
                        <div class="p-4">
                            <h4 class="font-bold">Mobile Responsive Content</h4>
                            <p>On mobile: Tabs display horizontally at top</p>
                            <p>On desktop: Tabs display vertically on left</p>
                            <div class="mt-2 text-sm text-gray-600">
                                <p>Current breakpoint behavior:</p>
                                <p class="block md:hidden">📱 Mobile view active</p>
                                <p class="hidden md:block">🖥️ Desktop view active</p>
                            </div>
                        </div>
                    </x-artisanpack-tab>
                    <x-artisanpack-tab name="mobile-tab2" label="Mobile Test 2">
                        <div class="p-4">
                            <h4 class="font-bold">Adaptive Layout</h4>
                            <p>The layout automatically adjusts based on screen size using Tailwind's responsive utilities.</p>
                        </div>
                    </x-artisanpack-tab>
                </x-artisanpack-tabs>
            </div>
        </div>
    </div>

    <!-- Implementation Summary -->
    <div class="mb-12 bg-green-50 p-6 rounded-lg border border-green-200">
        <h2 class="text-xl font-semibold mb-4 text-green-800">✅ Implementation Summary</h2>
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-bold text-green-700 mb-2">Features Implemented</h3>
                <ul class="list-disc list-inside space-y-1 text-green-600">
                    <li>Horizontal tabs (existing functionality preserved)</li>
                    <li>Vertical-left orientation</li>
                    <li>Vertical-right orientation</li>
                    <li>Responsive mobile-first design</li>
                    <li>Custom class overrides</li>
                    <li>Alpine.js integration</li>
                    <li>Accessibility features (ARIA attributes)</li>
                    <li>Edge case handling</li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold text-green-700 mb-2">Backward Compatibility</h3>
                <ul class="list-disc list-inside space-y-1 text-green-600">
                    <li>All existing props maintained</li>
                    <li>Default behavior unchanged</li>
                    <li>No breaking changes</li>
                    <li>Existing implementations work as before</li>
                    <li>Tab component unchanged</li>
                    <li>Alpine.js integration preserved</li>
                    <li>Custom styling still supported</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>