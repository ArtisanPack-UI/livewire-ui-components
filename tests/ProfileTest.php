<?php

use ArtisanPack\LivewireUiComponents\Tests\TestCase;
use ArtisanPack\LivewireUiComponents\View\Components\Profile;

class ProfileTest extends TestCase
{
    public function test_profile_can_be_instantiated_with_default_values()
    {
        $profile = new Profile();

        $this->assertNull($profile->id);
        $this->assertEquals('', $profile->image);
        $this->assertEquals('', $profile->alt);
        $this->assertEquals('', $profile->placeholder);
        $this->assertNull($profile->color);
        $this->assertNull($profile->colorAdjustment);
        $this->assertNull($profile->title);
        $this->assertNull($profile->subtitle);
        $this->assertFalse($profile->right);
        $this->assertFalse($profile->top);
        $this->assertFalse($profile->noXAnchor);
    }

    public function test_profile_accepts_avatar_properties()
    {
        $profile = new Profile(
            image: '/path/to/avatar.jpg',
            alt: 'User Avatar',
            placeholder: 'JD',
            title: 'John Doe',
            subtitle: 'Software Engineer',
            color: 'primary'
        );

        $this->assertEquals('/path/to/avatar.jpg', $profile->image);
        $this->assertEquals('User Avatar', $profile->alt);
        $this->assertEquals('JD', $profile->placeholder);
        $this->assertEquals('John Doe', $profile->title);
        $this->assertEquals('Software Engineer', $profile->subtitle);
        $this->assertEquals('primary', $profile->color);
    }

    public function test_profile_accepts_dropdown_properties()
    {
        $profile = new Profile(
            right: true,
            top: true,
            noXAnchor: true
        );

        $this->assertTrue($profile->right);
        $this->assertTrue($profile->top);
        $this->assertTrue($profile->noXAnchor);
    }

    public function test_profile_generates_uuid()
    {
        $profile = new Profile();

        $this->assertNotEmpty($profile->uuid);
        $this->assertStringStartsWith('artisanpack', $profile->uuid);
    }

    public function test_profile_uuid_includes_id_when_provided()
    {
        $profile = new Profile(id: 'test-profile');

        $this->assertNotEmpty($profile->uuid);
        $this->assertStringStartsWith('artisanpack', $profile->uuid);
        $this->assertStringEndsWith('test-profile', $profile->uuid);
    }

    public function test_profile_returns_empty_color_classes_when_no_color_set()
    {
        $profile = new Profile();
        $colorClasses = $profile->getColorClasses();

        $this->assertIsArray($colorClasses);
        $this->assertEmpty($colorClasses);
    }

    public function test_profile_resolves_predefined_color_variants()
    {
        $colorVariants = ['primary', 'secondary', 'accent', 'success', 'warning', 'error', 'info', 'neutral'];

        foreach ($colorVariants as $color) {
            $profile = new Profile(color: $color);
            $colorClasses = $profile->getColorClasses();

            $this->assertNotEmpty($colorClasses, "Color classes should not be empty for color: {$color}");
        }
    }

    public function test_profile_resolves_tailwind_colors()
    {
        $profile = new Profile(color: 'blue-500');
        $colorClasses = $profile->getColorClasses();

        $this->assertNotEmpty($colorClasses);
        $this->assertIsArray($colorClasses);
    }

    public function test_profile_applies_color_adjustments()
    {
        $adjustments = ['lighter', 'darker', 'transparent', 'subtle'];

        foreach ($adjustments as $adjustment) {
            $profile = new Profile(color: 'primary', colorAdjustment: $adjustment);
            $colorClasses = $profile->getColorClasses();

            $this->assertNotEmpty($colorClasses, "Color classes should not be empty for adjustment: {$adjustment}");
        }
    }

    public function test_profile_handles_hex_colors()
    {
        $profile = new Profile(color: '#ff0000');
        $colorClasses = $profile->getColorClasses();

        $this->assertNotEmpty($colorClasses);
        $this->assertIsArray($colorClasses);
    }

    public function test_profile_supports_image_mode()
    {
        $profile = new Profile(
            image: '/path/to/image.jpg',
            alt: 'Profile Picture'
        );

        $this->assertEquals('/path/to/image.jpg', $profile->image);
        $this->assertEquals('Profile Picture', $profile->alt);
        $this->assertEquals('', $profile->placeholder);
    }

    public function test_profile_supports_placeholder_mode()
    {
        $profile = new Profile(
            placeholder: 'AB',
            alt: 'Alex Brown',
            color: 'accent'
        );

        $this->assertEquals('', $profile->image);
        $this->assertEquals('AB', $profile->placeholder);
        $this->assertEquals('Alex Brown', $profile->alt);
        $this->assertEquals('accent', $profile->color);
    }

    public function test_profile_combines_avatar_and_dropdown_functionality()
    {
        $profile = new Profile(
            id: 'user-profile',
            image: '/avatar.jpg',
            alt: 'User Avatar',
            title: 'Jane Smith',
            subtitle: 'Product Manager',
            color: 'primary',
            right: true,
            top: false,
            noXAnchor: false
        );

        // Avatar properties
        $this->assertEquals('/avatar.jpg', $profile->image);
        $this->assertEquals('User Avatar', $profile->alt);
        $this->assertEquals('Jane Smith', $profile->title);
        $this->assertEquals('Product Manager', $profile->subtitle);
        $this->assertEquals('primary', $profile->color);

        // Dropdown properties
        $this->assertTrue($profile->right);
        $this->assertFalse($profile->top);
        $this->assertFalse($profile->noXAnchor);

        // UUID generation
        $this->assertStringContainsString('user-profile', $profile->uuid);
    }

    public function test_profile_renders_correct_view()
    {
        $profile = new Profile();
        $view = $profile->render();

        $this->assertEquals('livewire-ui-components::components.profile', $view->name());
    }
}