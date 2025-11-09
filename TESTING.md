# Comprehensive Testing Framework Documentation

## Overview

This document provides comprehensive documentation for the testing framework implemented for the ArtisanPack LivewireUiComponents package. The framework covers all 88+ components with multiple testing approaches to ensure quality, accessibility, performance, and security.

## Table of Contents

- [Testing Architecture](#testing-architecture)
- [Test Types](#test-types)
- [Getting Started](#getting-started)
- [Component Test Generation](#component-test-generation)
- [Testing Patterns](#testing-patterns)
- [CI/CD Integration](#cicd-integration)
- [Coverage Requirements](#coverage-requirements)
- [Best Practices](#best-practices)
- [Troubleshooting](#troubleshooting)

## Testing Architecture

The testing framework is built with a layered architecture:

```
tests/
├── Support/                    # Testing infrastructure
│   ├── ComponentTestCase.php           # Base class for component tests
│   ├── LivewireIntegrationTestCase.php # Base class for Livewire integration tests
│   ├── ComponentDataFactory.php        # Test data generation
│   ├── TestHelpers.php                # Testing utilities
│   ├── ComponentTestGenerator.php      # Automated test generation
│   └── GenerateComponentTests.php     # Test generation script
├── Unit/                      # Unit tests for components
│   └── Components/           # Component-specific unit tests
├── Integration/              # Livewire integration tests
├── Browser/                  # Dusk browser tests
│   ├── DuskTestCase.php     # Base Dusk test case
│   └── CreatesApplication.php # Application creation trait
├── Feature/                  # Feature tests
├── Accessibility/            # Accessibility-specific tests
└── Performance/             # Performance tests
```

## Test Types

### 1. Unit Tests

**Purpose**: Test individual component functionality in isolation.

**Location**: `tests/Unit/Components/`

**Example**:
```php
class ButtonComponentTest extends ComponentTestCase
{
    protected string $componentClass = Button::class;
    
    protected array $defaultProperties = [
        'variant' => 'primary',
        'responsive' => false,
    ];

    public function test_button_handles_variants(): void
    {
        $button = $this->createComponent(['variant' => 'success']);
        $this->assertEquals('success', $button->variant);
    }
}
```

**What's Tested**:
- Component instantiation
- Property validation
- Method functionality
- Default values
- Edge cases
- Security (XSS protection)
- Performance
- Memory usage

### 2. Integration Tests

**Purpose**: Test components within Livewire contexts.

**Location**: `tests/Integration/`

**Example**:
```php
class ButtonLivewireIntegrationTest extends LivewireIntegrationTestCase
{
    public function test_button_with_wire_click(): void
    {
        $testable = Livewire::test(TestComponent::class);
        
        $testable->call('handleClick')
                 ->assertSet('clicked', true);
    }
}
```

**What's Tested**:
- Livewire property binding
- Event handling
- State management
- Validation
- Loading states
- Real-time updates

### 3. Browser Tests

**Purpose**: Test components in real browser environments.

**Location**: `tests/Browser/`

**Example**:
```php
class ButtonBrowserTest extends DuskTestCase
{
    public function test_responsive_behavior(): void
    {
        $this->testResponsiveComponent(function ($browser, $breakpoint) {
            $browser->visit('/test-page')
                   ->assertVisible('.btn')
                   ->click('.btn')
                   ->assertSee('Clicked');
        });
    }
}
```

**What's Tested**:
- Real user interactions
- Responsive design
- Cross-browser compatibility
- JavaScript functionality
- Visual behavior
- Performance in browser

### 4. Accessibility Tests

**Purpose**: Ensure components meet accessibility standards.

**Example**:
```php
public function test_keyboard_navigation(): void
{
    $this->testKeyboardNavigation(
        fn($browser) => $browser->visit('/form'),
        ['#name-input', '#email-input', '#submit-btn']
    );
}
```

**What's Tested**:
- WCAG compliance
- Keyboard navigation
- Screen reader compatibility
- Color contrast
- ARIA attributes
- Focus management

### 5. Performance Tests

**Purpose**: Ensure components perform efficiently.

**Example**:
```php
public function test_rendering_performance(): void
{
    $performance = TestHelpers::measureRenderingPerformance($component);
    $this->assertLessThan(100, $performance['average_time']);
}
```

**What's Tested**:
- Rendering speed
- Memory usage
- Large dataset handling
- Network performance
- Loading times

## Getting Started

### Prerequisites

1. **PHP 8.2+**
2. **Node.js 18+** (for browser tests)
3. **Chrome/Chromium** (for Dusk tests)

### Installation

1. Install testing dependencies:
```bash
composer install --dev
```

2. Install browser testing tools:
```bash
npm install -g @axe-core/cli
```

3. Set up environment:
```bash
cp .env.example .env.testing
```

### Running Tests

#### All Tests
```bash
composer test
```

#### Specific Test Types
```bash
# Unit tests only
./vendor/bin/pest tests/Unit

# Integration tests
./vendor/bin/pest tests/Integration

# Browser tests
./vendor/bin/pest tests/Browser

# With coverage
./vendor/bin/pest --coverage --coverage-html=coverage
```

#### Parallel Testing
```bash
# Run tests in parallel for faster execution
./vendor/bin/pest --parallel
```

## Component Test Generation

### Automatic Generation

Generate tests for all components:
```bash
php tests/Support/GenerateComponentTests.php
```

Generate test for specific component:
```bash
php tests/Support/GenerateComponentTests.php specific Button
```

### Manual Creation

1. **Create Unit Test**:
```php
<?php

use Tests\Support\ComponentTestCase;

class NewComponentTest extends ComponentTestCase
{
    protected string $componentClass = NewComponent::class;
    
    protected array $defaultProperties = [
        'property1' => 'default_value',
    ];

    // Component-specific tests...
}
```

2. **Create Integration Test**:
```php
<?php

use Tests\Support\LivewireIntegrationTestCase;

class NewComponentIntegrationTest extends LivewireIntegrationTestCase
{
    // Livewire integration tests...
}
```

## Testing Patterns

### Component Property Testing

```php
public function test_component_properties(): void
{
    $testData = ComponentDataFactory::forComponent('ComponentName');
    
    foreach ($testData['colors'] as $color) {
        $component = $this->createComponent(['color' => $color]);
        $this->assertEquals($color, $component->color);
    }
}
```

### Accessibility Testing Pattern

```php
public function test_accessibility_compliance(): void
{
    $component = $this->createComponent();
    $html = $component->render()->render();
    
    $validation = TestHelpers::validateHtmlStructure($html);
    $this->assertTrue($validation['is_valid']);
}
```

### Security Testing Pattern

```php
public function test_xss_protection(): void
{
    $xssPayloads = TestHelpers::securityTestPayloads();
    
    foreach ($xssPayloads as $payload) {
        $component = $this->createComponent(['label' => $payload]);
        $html = $component->render()->render();
        
        $this->assertStringNotContainsString('<script', $html);
    }
}
```

### Performance Testing Pattern

```php
public function test_performance(): void
{
    $performance = TestHelpers::measureRenderingPerformance(
        $this->createComponent(), 
        100 // iterations
    );
    
    $this->assertLessThan(50, $performance['average_time']); // 50ms
}
```

## CI/CD Integration

The testing framework integrates with GitLab CI/CD through multiple pipeline stages:

### Pipeline Stages

1. **Build**: Install dependencies
2. **Test**: Unit and feature tests (parallel execution)
3. **Integration**: Livewire integration tests
4. **Browser**: Dusk browser tests with Selenium
5. **Accessibility**: aXe-core accessibility tests
6. **Performance**: Performance benchmarks
7. **Coverage**: Combined coverage analysis (>95% target)
8. **Code Style**: PHPStan static analysis
9. **Security**: SAST security scanning

### Coverage Requirements

- **Minimum Coverage**: 95%
- **Mutation Score**: 80%
- **Performance Threshold**: <100ms average rendering

### Pipeline Configuration

Key features:
- **Parallel Execution**: Unit tests run in 4 parallel jobs
- **Browser Testing**: Automated with Selenium Chrome
- **Accessibility**: Automated aXe-core integration
- **Coverage Reporting**: Cobertura format for GitLab
- **Artifact Storage**: Test reports, screenshots, coverage

## Best Practices

### Writing Tests

1. **Use Descriptive Names**:
   ```php
   public function test_button_renders_with_custom_variant(): void
   ```

2. **Test Edge Cases**:
   ```php
   public function test_handles_empty_values(): void
   {
       $component = $this->createComponent(['label' => '']);
       $this->assertInstanceOf(Component::class, $component);
   }
   ```

3. **Use Data Factories**:
   ```php
   $testData = ComponentDataFactory::forComponent('Button');
   ```

4. **Test Accessibility**:
   ```php
   $this->assertAccessibilityCompliance();
   ```

### Performance Considerations

1. **Limit Large Dataset Tests**: Use appropriate dataset sizes
2. **Mock External Dependencies**: Don't make real HTTP requests
3. **Clean Up Resources**: Use `tearDown()` methods
4. **Use Parallel Testing**: For faster feedback

### Security Testing

1. **Test XSS Protection**: Always test with malicious payloads
2. **Validate Input Sanitization**: Test with special characters
3. **Test CSRF Protection**: For form components
4. **Validate Output Encoding**: Ensure proper HTML escaping

## Coverage Requirements

### Target Metrics

- **Line Coverage**: 95%+ required
- **Branch Coverage**: 90%+ recommended
- **Method Coverage**: 100% required for public methods
- **Class Coverage**: 100% required

### Coverage Analysis

```bash
# Generate detailed coverage report
./vendor/bin/pest --coverage --coverage-html=coverage --min=95

# Check coverage in CI
./vendor/bin/pest --coverage --coverage-clover=coverage.xml --min=95
```

### Coverage Exemptions

Some code may be exempted from coverage requirements:
- Debug/development code
- Legacy compatibility code
- Third-party integrations

Use `@codeCoverageIgnore` annotations sparingly.

## Troubleshooting

### Common Issues

#### 1. Browser Tests Failing

**Problem**: Dusk tests timing out or failing to connect.

**Solutions**:
- Check Chrome/Chromium installation
- Verify Selenium service is running
- Increase timeout values
- Check network connectivity

#### 2. Memory Issues

**Problem**: Tests running out of memory.

**Solutions**:
- Increase PHP memory limit: `php -d memory_limit=512M`
- Use smaller test datasets
- Clean up objects in `tearDown()`

#### 3. Slow Test Execution

**Problem**: Tests taking too long to run.

**Solutions**:
- Use parallel testing: `--parallel`
- Optimize database queries
- Use factories instead of fixtures
- Mock external services

#### 4. Coverage Not Meeting Targets

**Problem**: Coverage below 95% threshold.

**Solutions**:
- Review uncovered code with coverage reports
- Add missing test cases
- Test edge cases and error conditions
- Remove dead code

### Debug Commands

```bash
# Run specific test with verbose output
./vendor/bin/pest tests/Unit/ButtonTest.php --verbose

# Run with debugging information
./vendor/bin/pest --debug

# Generate coverage report for analysis
./vendor/bin/pest --coverage --coverage-html=debug-coverage
```

### Environment Variables

Set these for debugging:

```bash
# Enable Dusk debugging
export DUSK_HEADLESS_DISABLED=1
export DUSK_START_MAXIMIZED=1

# Enable verbose testing
export PEST_VERBOSE=1

# Memory debugging
export PHP_MEMORY_LIMIT=1G
```

## Advanced Features

### Custom Test Data

Create custom test data for specific scenarios:

```php
// In ComponentDataFactory
public static function customScenario(): array
{
    return [
        'edge_case_1' => ['property' => 'value'],
        'edge_case_2' => ['property' => 'other_value'],
    ];
}
```

### Performance Profiling

Enable detailed performance profiling:

```php
public function test_detailed_performance(): void
{
    $profiler = new PerformanceProfiler();
    
    $profiler->start();
    $component = $this->createComponent();
    $profiler->checkpoint('component_created');
    
    $component->render();
    $profiler->checkpoint('component_rendered');
    
    $report = $profiler->getReport();
    $this->assertLessThan(100, $report['total_time']);
}
```

### Visual Regression Testing

For visual regression testing integration:

```php
public function test_visual_regression(): void
{
    $this->browse(function ($browser) {
        $browser->visit('/component-showcase')
               ->screenshot('component-baseline')
               ->assertVisualDiff('component-baseline');
    });
}
```

## Continuous Improvement

The testing framework is designed to evolve with the codebase:

1. **Regular Updates**: Keep testing tools and dependencies updated
2. **New Patterns**: Add new testing patterns as components evolve
3. **Performance Monitoring**: Continuously monitor and improve test performance
4. **Coverage Analysis**: Regular review of coverage reports
5. **Accessibility Updates**: Stay current with WCAG guidelines

## Contributing

When contributing to the testing framework:

1. Follow existing patterns and conventions
2. Add documentation for new testing utilities
3. Ensure new tests are comprehensive
4. Update this documentation for new features
5. Maintain backward compatibility where possible

## Support

For issues with the testing framework:

1. Check this documentation first
2. Review existing test examples
3. Check GitLab CI/CD logs for pipeline issues
4. Create issues with detailed reproduction steps
5. Include test environment details

---

This comprehensive testing framework ensures that all 88+ UI components maintain high quality, accessibility, performance, and security standards through automated testing and continuous integration.