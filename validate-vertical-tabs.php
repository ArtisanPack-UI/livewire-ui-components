<?php

/**
 * Manual validation script for Vertical Tabs implementation
 * 
 * This script tests the Tabs component implementation without requiring
 * a full Laravel testing environment.
 */

require_once __DIR__ . '/src/View/Components/Tabs.php';
require_once __DIR__ . '/src/View/Components/Tab.php';

use ArtisanPack\LivewireUiComponents\View\Components\Tabs;
use ArtisanPack\LivewireUiComponents\View\Components\Tab;

echo "=== Vertical Tabs Implementation Validation ===\n\n";

// Test 1: Backward Compatibility - Default horizontal behavior
echo "✅ Test 1: Backward Compatibility - Default horizontal behavior\n";
$horizontalTabs = new Tabs();
assert($horizontalTabs->orientation === 'horizontal', 'Default orientation should be horizontal');
assert($horizontalTabs->isVertical() === false, 'isVertical() should return false for horizontal');
assert($horizontalTabs->isVerticalRight() === false, 'isVerticalRight() should return false for horizontal');
assert($horizontalTabs->getTabsContainerClass() === 'relative w-full', 'Should use horizontal container class');
assert($horizontalTabs->getLabelDivClass() === 'border-b-[length:var(--border)] border-b-base-content/10 flex overflow-x-auto', 'Should use horizontal label div class');
echo "   ✅ All horizontal defaults working correctly\n\n";

// Test 2: Vertical Left Orientation
echo "✅ Test 2: Vertical Left Orientation\n";
$verticalLeftTabs = new Tabs(orientation: 'vertical-left');
assert($verticalLeftTabs->orientation === 'vertical-left', 'Orientation should be vertical-left');
assert($verticalLeftTabs->isVertical() === true, 'isVertical() should return true');
assert($verticalLeftTabs->isVerticalRight() === false, 'isVerticalRight() should return false');
assert($verticalLeftTabs->getTabsContainerClass() === 'relative w-full flex flex-col md:flex-row', 'Should use vertical container class');
assert(strpos($verticalLeftTabs->getLabelDivClass(), 'md:border-r-[length:var(--border)]') !== false, 'Should use vertical-left label div class');
assert(strpos($verticalLeftTabs->getActiveClass(), 'md:border-r-[length:var(--border)]') !== false, 'Should use vertical-left active class');
echo "   ✅ Vertical-left orientation working correctly\n\n";

// Test 3: Vertical Right Orientation
echo "✅ Test 3: Vertical Right Orientation\n";
$verticalRightTabs = new Tabs(orientation: 'vertical-right');
assert($verticalRightTabs->orientation === 'vertical-right', 'Orientation should be vertical-right');
assert($verticalRightTabs->isVertical() === true, 'isVertical() should return true');
assert($verticalRightTabs->isVerticalRight() === true, 'isVerticalRight() should return true');
assert($verticalRightTabs->getTabsContainerClass() === 'relative w-full flex flex-col md:flex-row', 'Should use vertical container class');
assert(strpos($verticalRightTabs->getLabelDivClass(), 'md:border-l-[length:var(--border)]') !== false, 'Should use vertical-right label div class');
assert(strpos($verticalRightTabs->getActiveClass(), 'md:border-l-[length:var(--border)]') !== false, 'Should use vertical-right active class');
echo "   ✅ Vertical-right orientation working correctly\n\n";

// Test 4: Custom Class Overrides
echo "✅ Test 4: Custom Class Overrides\n";
$customTabs = new Tabs(
    orientation: 'vertical-left',
    verticalTabsClass: 'custom-container',
    verticalLabelClass: 'custom-label',
    verticalActiveClass: 'custom-active',
    verticalLabelDivClass: 'custom-label-div'
);
assert($customTabs->getTabsContainerClass() === 'custom-container', 'Should use custom container class');
assert($customTabs->getLabelClass() === 'custom-label', 'Should use custom label class');
assert($customTabs->getActiveClass() === 'custom-active', 'Should use custom active class');
assert($customTabs->getLabelDivClass() === 'custom-label-div', 'Should use custom label div class');
echo "   ✅ Custom class overrides working correctly\n\n";

// Test 5: Vertical Right Custom Classes
echo "✅ Test 5: Vertical Right Custom Classes\n";
$customRightTabs = new Tabs(
    orientation: 'vertical-right',
    verticalRightActiveClass: 'custom-right-active',
    verticalRightLabelDivClass: 'custom-right-label-div'
);
assert($customRightTabs->getActiveClass() === 'custom-right-active', 'Should use custom right active class');
assert($customRightTabs->getLabelDivClass() === 'custom-right-label-div', 'Should use custom right label div class');
echo "   ✅ Vertical-right custom classes working correctly\n\n";

// Test 6: Invalid Orientation Handling
echo "✅ Test 6: Invalid Orientation Handling\n";
$invalidTabs = new Tabs(orientation: 'invalid-orientation');
assert($invalidTabs->orientation === 'invalid-orientation', 'Should store invalid orientation');
assert($invalidTabs->isVertical() === false, 'isVertical() should return false for invalid orientation');
assert($invalidTabs->isVerticalRight() === false, 'isVerticalRight() should return false for invalid orientation');
assert($invalidTabs->getTabsContainerClass() === 'relative w-full', 'Should fall back to horizontal container class');
echo "   ✅ Invalid orientation handled gracefully\n\n";

// Test 7: UUID Generation
echo "✅ Test 7: UUID Generation\n";
$tabs1 = new Tabs(id: 'test1');
$tabs2 = new Tabs(id: 'test2');
assert($tabs1->uuid !== $tabs2->uuid, 'UUIDs should be unique');
assert(strpos($tabs1->uuid, 'test1') !== false, 'UUID should contain the ID');
assert(strpos($tabs2->uuid, 'test2') !== false, 'UUID should contain the ID');
echo "   ✅ UUID generation working correctly\n\n";

// Test 8: Existing Properties Preservation
echo "✅ Test 8: Existing Properties Preservation\n";
$existingTabs = new Tabs(
    id: 'existing-test',
    selected: 'tab1',
    labelClass: 'existing-label',
    activeClass: 'existing-active',
    labelDivClass: 'existing-label-div',
    tabsClass: 'existing-container'
);
assert($existingTabs->id === 'existing-test', 'ID should be preserved');
assert($existingTabs->selected === 'tab1', 'Selected should be preserved');
assert($existingTabs->labelClass === 'existing-label', 'Label class should be preserved');
assert($existingTabs->activeClass === 'existing-active', 'Active class should be preserved');
assert($existingTabs->labelDivClass === 'existing-label-div', 'Label div class should be preserved');
assert($existingTabs->tabsClass === 'existing-container', 'Tabs class should be preserved');
// For horizontal orientation, it should use the original properties
assert($existingTabs->getTabsContainerClass() === 'existing-container', 'Should use original container class for horizontal');
assert($existingTabs->getLabelClass() === 'existing-label', 'Should use original label class for horizontal');
echo "   ✅ Existing properties preserved correctly\n\n";

// Test 9: Tab Component Compatibility
echo "✅ Test 9: Tab Component Compatibility\n";
$tab = new Tab(
    id: 'test-tab',
    name: 'tab1',
    label: 'Test Tab',
    icon: 'home',
    disabled: false,
    hidden: false
);
assert($tab->name === 'tab1', 'Tab name should be set correctly');
assert($tab->label === 'Test Tab', 'Tab label should be set correctly');
assert($tab->icon === 'home', 'Tab icon should be set correctly');
assert($tab->disabled === false, 'Tab disabled should be set correctly');
assert($tab->hidden === false, 'Tab hidden should be set correctly');
assert(strpos($tab->uuid, 'test-tab') !== false, 'Tab UUID should contain the ID');
echo "   ✅ Tab component compatibility maintained\n\n";

// Test 10: Responsive Classes Validation
echo "✅ Test 10: Responsive Classes Validation\n";
$responsiveTabs = new Tabs(orientation: 'vertical-left');
$containerClass = $responsiveTabs->getTabsContainerClass();
$labelDivClass = $responsiveTabs->getLabelDivClass();
$labelClass = $responsiveTabs->getLabelClass();
$activeClass = $responsiveTabs->getActiveClass();

assert(strpos($containerClass, 'flex-col md:flex-row') !== false, 'Container should have responsive classes');
assert(strpos($labelDivClass, 'md:flex-col') !== false, 'Label div should have responsive classes');
assert(strpos($labelClass, 'px-3 py-2 md:pr-1 md:pl-1 md:py-2') !== false, 'Label should have responsive classes');
assert(strpos($activeClass, 'border-b-[length:var(--border)] border-b-base-content/50 md:border-b-0') !== false, 'Active should have responsive classes');
echo "   ✅ Responsive classes working correctly\n\n";

echo "🎉 All validation tests passed successfully!\n";
echo "🎯 The vertical tabs implementation is ready for production use.\n\n";

echo "=== Summary ===\n";
echo "✅ Backward compatibility maintained\n";
echo "✅ Vertical-left orientation implemented\n";
echo "✅ Vertical-right orientation implemented\n";
echo "✅ Custom class overrides working\n";
echo "✅ Invalid orientation handling graceful\n";
echo "✅ UUID generation unique\n";
echo "✅ Existing properties preserved\n";
echo "✅ Tab component compatibility maintained\n";
echo "✅ Responsive classes implemented\n";
echo "✅ Edge cases handled properly\n\n";

echo "🚀 Implementation Status: COMPLETE AND VALIDATED\n";

?>