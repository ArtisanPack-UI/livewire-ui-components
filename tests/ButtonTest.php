<?php

use ArtisanPack\LivewireUiComponents\View\Components\Button;
use PHPUnit\Framework\TestCase;

class ButtonTest extends TestCase
{
    public function test_button_uses_primary_variant_by_default()
    {
        $button = new Button();

        $this->assertEquals('primary', $button->variant);
        $this->assertEquals('btn-primary', $button->getVariantClasses());
    }

    public function test_button_accepts_valid_variants()
    {
        $validVariants = [
            'primary' => 'btn-primary',
            'secondary' => 'btn-secondary',
            'accent' => 'btn-accent',
            'success' => 'btn-success',
            'warning' => 'btn-warning',
            'error' => 'btn-error',
            'ghost' => 'btn-ghost',
            'outline' => 'btn-outline'
        ];

        foreach ($validVariants as $variant => $expectedClass) {
            $button = new Button(variant: $variant);

            $this->assertEquals($variant, $button->variant);
            $this->assertEquals($expectedClass, $button->getVariantClasses());
        }
    }

    public function test_button_falls_back_to_primary_for_invalid_variants()
    {
        $invalidVariants = ['invalid', 'nonexistent', '', null];

        foreach ($invalidVariants as $variant) {
            $button = new Button(variant: $variant);

            $this->assertEquals('primary', $button->variant);
            $this->assertEquals('btn-primary', $button->getVariantClasses());
        }
    }

    public function test_button_maintains_existing_functionality()
    {
        $button = new Button(
            id: 'test-btn',
            label: 'Test Button',
            icon: 'save',
            variant: 'success'
        );

        $this->assertEquals('test-btn', $button->id);
        $this->assertEquals('Test Button', $button->label);
        $this->assertEquals('save', $button->icon);
        $this->assertEquals('success', $button->variant);
        $this->assertEquals('btn-success', $button->getVariantClasses());
    }

    public function test_button_generates_uuid()
    {
        $button = new Button();

        $this->assertNotEmpty($button->uuid);
        $this->assertStringStartsWith('artisanpack', $button->uuid);
    }
}
