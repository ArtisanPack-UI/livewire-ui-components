<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Pagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class PaginationVariantsTest extends TestCase
{
    public function testPaginationComponentAcceptsVariantParameters()
    {
        $items = Collection::range(1, 100);
        $paginated = new LengthAwarePaginator($items->take(10), 100, 10, 1);
        
        $component = new Pagination(
            rows: $paginated,
            simple: true,
            compact: false,
            advanced: false,
            minimal: false
        );
        
        $this->assertTrue($component->simple);
        $this->assertFalse($component->compact);
        $this->assertFalse($component->advanced);
        $this->assertFalse($component->minimal);
    }

    public function testPaginationComponentGeneratesCorrectVariantClasses()
    {
        $items = Collection::range(1, 100);
        $paginated = new LengthAwarePaginator($items->take(10), 100, 10, 1);
        
        $component = new Pagination(rows: $paginated, simple: true);
        $this->assertStringContainsString('pagination-simple', $component->getVariantClasses());
        
        $component = new Pagination(rows: $paginated, compact: true);
        $this->assertStringContainsString('pagination-compact', $component->getVariantClasses());
        
        $component = new Pagination(rows: $paginated, advanced: true);
        $this->assertStringContainsString('pagination-advanced', $component->getVariantClasses());
        
        $component = new Pagination(rows: $paginated, minimal: true);
        $this->assertStringContainsString('pagination-minimal', $component->getVariantClasses());
        
        $component = new Pagination(rows: $paginated);
        $this->assertStringContainsString('pagination-default', $component->getVariantClasses());
    }

    public function testPaginationComponentHandlesSizeParameterCorrectly()
    {
        $items = Collection::range(1, 100);
        $paginated = new LengthAwarePaginator($items->take(10), 100, 10, 1);
        
        $component = new Pagination(rows: $paginated, size: 'sm');
        $this->assertStringContainsString('pagination-sm', $component->getVariantClasses());
        
        $component = new Pagination(rows: $paginated, size: 'lg');
        $this->assertStringContainsString('pagination-lg', $component->getVariantClasses());
        
        $component = new Pagination(rows: $paginated, size: 'default');
        // When size is 'default', it should not add an additional size class, but still have the variant class
        $this->assertStringContainsString('pagination-default', $component->getVariantClasses());
        $this->assertStringNotContainsString('pagination-default pagination-default', $component->getVariantClasses());
    }

    public function testShouldShowPerPageSelectorMethodWorksCorrectly()
    {
        $items = Collection::range(1, 100);
        $paginated = new LengthAwarePaginator($items->take(10), 100, 10, 1);
        
        // Mock the attributes to avoid the dependency on Laravel's ComponentAttributeBag
        // For unit testing, we'll test the logic by mocking the modelName method
        
        // Should show for default variant with model binding
        $component = $this->getMockBuilder(Pagination::class)
            ->setConstructorArgs(['rows' => $paginated, 'id' => 'test'])
            ->onlyMethods(['modelName'])
            ->getMock();
        $component->method('modelName')->willReturn('perPage');
        $this->assertTrue($component->shouldShowPerPageSelector());
        
        // Should not show for simple variant
        $component = $this->getMockBuilder(Pagination::class)
            ->setConstructorArgs(['rows' => $paginated, 'simple' => true, 'id' => 'test'])
            ->onlyMethods(['modelName'])
            ->getMock();
        $component->method('modelName')->willReturn('perPage');
        $this->assertFalse($component->shouldShowPerPageSelector());
        
        // Should not show for minimal variant
        $component = $this->getMockBuilder(Pagination::class)
            ->setConstructorArgs(['rows' => $paginated, 'minimal' => true, 'id' => 'test'])
            ->onlyMethods(['modelName'])
            ->getMock();
        $component->method('modelName')->willReturn('perPage');
        $this->assertFalse($component->shouldShowPerPageSelector());
        
        // Should not show when hidePerPage is true
        $component = $this->getMockBuilder(Pagination::class)
            ->setConstructorArgs(['rows' => $paginated, 'hidePerPage' => true, 'id' => 'test'])
            ->onlyMethods(['modelName'])
            ->getMock();
        $component->method('modelName')->willReturn('perPage');
        $this->assertFalse($component->shouldShowPerPageSelector());
    }

    public function testShouldShowPageInfoMethodWorksCorrectly()
    {
        $items = Collection::range(1, 100);
        $paginated = new LengthAwarePaginator($items->take(10), 100, 10, 1);
        
        // Should show for default variant
        $component = new Pagination(rows: $paginated);
        $this->assertTrue($component->shouldShowPageInfo());
        
        // Should not show for simple variant
        $component = new Pagination(rows: $paginated, simple: true);
        $this->assertFalse($component->shouldShowPageInfo());
        
        // Should not show for minimal variant
        $component = new Pagination(rows: $paginated, minimal: true);
        $this->assertFalse($component->shouldShowPageInfo());
        
        // Should not show when showPageInfo is false
        $component = new Pagination(rows: $paginated, showPageInfo: false);
        $this->assertFalse($component->shouldShowPageInfo());
        
        // Should not show when hidePageInfo is true
        $component = new Pagination(rows: $paginated, hidePageInfo: true);
        $this->assertFalse($component->shouldShowPageInfo());
    }

    public function testMultipleVariantsCannotBeActiveSimultaneously()
    {
        $items = Collection::range(1, 100);
        $paginated = new LengthAwarePaginator($items->take(10), 100, 10, 1);
        
        // When multiple variants are specified, priority should be simple > compact > advanced > minimal > default
        $component = new Pagination(
            rows: $paginated,
            simple: true,
            compact: true,
            advanced: true,
            minimal: true
        );
        
        $classes = $component->getVariantClasses();
        $this->assertStringContainsString('pagination-simple', $classes);
        $this->assertStringNotContainsString('pagination-compact', $classes);
        $this->assertStringNotContainsString('pagination-advanced', $classes);
        $this->assertStringNotContainsString('pagination-minimal', $classes);
    }
}