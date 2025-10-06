<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Support;

use ReflectionClass;
use ReflectionParameter;
use Illuminate\Support\Str;

/**
 * Component Test Generator
 * 
 * Automatically generates comprehensive unit tests for all UI components
 * based on component analysis and established testing patterns.
 */
class ComponentTestGenerator
{
    private array $componentClasses = [];
    private string $testDirectory;
    private string $namespace = 'ArtisanPack\\LivewireUiComponents\\Tests\\Unit\\Components';

    public function __construct(string $testDirectory = null)
    {
        $this->testDirectory = $testDirectory ?? __DIR__ . '/../Unit/Components';
    }

    /**
     * Scan for all component classes.
     */
    public function scanComponents(string $componentDirectory): array
    {
        $components = [];
        $files = glob($componentDirectory . '/*.php');
        
        foreach ($files as $file) {
            $className = $this->extractClassName($file);
            if ($className && $this->isValidComponent($className)) {
                $components[] = $className;
            }
        }
        
        $this->componentClasses = $components;
        return $components;
    }

    /**
     * Generate tests for all discovered components.
     */
    public function generateAllTests(): array
    {
        $generated = [];
        
        foreach ($this->componentClasses as $componentClass) {
            $testFile = $this->generateTestForComponent($componentClass);
            if ($testFile) {
                $generated[] = $testFile;
            }
        }
        
        return $generated;
    }

    /**
     * Generate a comprehensive test for a specific component.
     */
    public function generateTestForComponent(string $componentClass): ?string
    {
        try {
            $reflection = new ReflectionClass($componentClass);
            $componentName = $reflection->getShortName();
            $testClassName = $componentName . 'ComponentTest';
            $testFilePath = $this->testDirectory . '/' . $testClassName . '.php';
            
            // Skip if test already exists
            if (file_exists($testFilePath)) {
                return null;
            }
            
            $testContent = $this->generateTestContent($reflection, $testClassName, $componentClass);
            
            // Create directory if it doesn't exist
            if (!is_dir($this->testDirectory)) {
                mkdir($this->testDirectory, 0755, true);
            }
            
            file_put_contents($testFilePath, $testContent);
            
            return $testFilePath;
        } catch (\Exception $e) {
            // Log error or handle as needed
            return null;
        }
    }

    /**
     * Generate test content based on component analysis.
     */
    private function generateTestContent(ReflectionClass $reflection, string $testClassName, string $componentClass): string
    {
        $componentName = $reflection->getShortName();
        $constructor = $reflection->getConstructor();
        $properties = $this->analyzeConstructorParameters($constructor);
        $methods = $this->analyzePublicMethods($reflection);
        
        $defaultProperties = $this->generateDefaultProperties($properties);
        $requiredProperties = $this->generateRequiredProperties($properties);
        $testMethods = $this->generateTestMethods($componentName, $properties, $methods);
        
        return $this->buildTestFileContent(
            $testClassName,
            $componentClass,
            $componentName,
            $defaultProperties,
            $requiredProperties,
            $testMethods
        );
    }

    /**
     * Analyze constructor parameters to understand component properties.
     */
    private function analyzeConstructorParameters(?ReflectionMethod $constructor): array
    {
        if (!$constructor) {
            return [];
        }
        
        $properties = [];
        foreach ($constructor->getParameters() as $parameter) {
            $properties[] = [
                'name' => $parameter->getName(),
                'type' => $this->getParameterType($parameter),
                'hasDefault' => $parameter->isDefaultValueAvailable(),
                'defaultValue' => $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null,
                'nullable' => $parameter->allowsNull()
            ];
        }
        
        return $properties;
    }

    /**
     * Analyze public methods for component functionality.
     */
    private function analyzePublicMethods(ReflectionClass $reflection): array
    {
        $methods = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->getDeclaringClass()->getName() === $reflection->getName()) {
                $methods[] = [
                    'name' => $method->getName(),
                    'parameters' => array_map(fn($p) => $p->getName(), $method->getParameters()),
                    'returnType' => $method->getReturnType()?->getName()
                ];
            }
        }
        
        return $methods;
    }

    /**
     * Generate default properties array for the test.
     */
    private function generateDefaultProperties(array $properties): array
    {
        $defaults = [];
        
        foreach ($properties as $property) {
            if ($property['hasDefault'] && $property['defaultValue'] !== null) {
                $defaults[$property['name']] = $property['defaultValue'];
            }
        }
        
        return $defaults;
    }

    /**
     * Generate required properties array for the test.
     */
    private function generateRequiredProperties(array $properties): array
    {
        $required = [];
        
        foreach ($properties as $property) {
            if (!$property['hasDefault'] && !$property['nullable']) {
                $required[] = $property['name'];
            }
        }
        
        return $required;
    }

    /**
     * Generate test methods based on component analysis.
     */
    private function generateTestMethods(string $componentName, array $properties, array $methods): string
    {
        $testMethods = [];
        
        // Generate property-based tests
        $testMethods[] = $this->generatePropertyTests($componentName, $properties);
        
        // Generate method-based tests
        foreach ($methods as $method) {
            if (!in_array($method['name'], ['__construct', 'render', 'shouldRender'])) {
                $testMethods[] = $this->generateMethodTest($componentName, $method);
            }
        }
        
        // Generate common tests
        $testMethods[] = $this->generateRenderingTest($componentName);
        $testMethods[] = $this->generateAccessibilityTest($componentName);
        $testMethods[] = $this->generateSecurityTest($componentName);
        $testMethods[] = $this->generatePerformanceTest($componentName);
        
        return implode("\n\n", array_filter($testMethods));
    }

    /**
     * Generate tests for component properties.
     */
    private function generatePropertyTests(string $componentName, array $properties): string
    {
        $tests = [];
        $lowerComponentName = strtolower($componentName);
        
        // Group properties by type for better testing
        $stringProperties = array_filter($properties, fn($p) => $p['type'] === 'string');
        $boolProperties = array_filter($properties, fn($p) => $p['type'] === 'bool');
        $arrayProperties = array_filter($properties, fn($p) => $p['type'] === 'array');
        
        if (!empty($stringProperties)) {
            $tests[] = $this->generateStringPropertyTest($lowerComponentName, $stringProperties);
        }
        
        if (!empty($boolProperties)) {
            $tests[] = $this->generateBooleanPropertyTest($lowerComponentName, $boolProperties);
        }
        
        if (!empty($arrayProperties)) {
            $tests[] = $this->generateArrayPropertyTest($lowerComponentName, $arrayProperties);
        }
        
        return implode("\n\n", $tests);
    }

    /**
     * Generate test for string properties.
     */
    private function generateStringPropertyTest(string $componentName, array $properties): string
    {
        $propertyNames = array_column($properties, 'name');
        $propertyList = "'" . implode("', '", $propertyNames) . "'";
        
        return "    public function test_{$componentName}_string_properties(): void
    {
        \$stringProperties = [{$propertyList}];
        \$testValues = ComponentDataFactory::sampleTexts();
        
        foreach (\$stringProperties as \$property) {
            foreach (\$testValues as \$value) {
                if (empty(\$value)) continue; // Skip empty values for some properties
                
                \$component = \$this->createComponent([\$property => \$value]);
                \$this->assertEquals(\$value, \$component->\$property);
            }
        }
    }";
    }

    /**
     * Generate test for boolean properties.
     */
    private function generateBooleanPropertyTest(string $componentName, array $properties): string
    {
        $propertyNames = array_column($properties, 'name');
        $propertyList = "'" . implode("', '", $propertyNames) . "'";
        
        return "    public function test_{$componentName}_boolean_properties(): void
    {
        \$booleanProperties = [{$propertyList}];
        
        foreach (\$booleanProperties as \$property) {
            \$component = \$this->createComponent([\$property => true]);
            \$this->assertTrue(\$component->\$property);
            
            \$component = \$this->createComponent([\$property => false]);
            \$this->assertFalse(\$component->\$property);
        }
    }";
    }

    /**
     * Generate test for array properties.
     */
    private function generateArrayPropertyTest(string $componentName, array $properties): string
    {
        $propertyNames = array_column($properties, 'name');
        $propertyList = "'" . implode("', '", $propertyNames) . "'";
        
        return "    public function test_{$componentName}_array_properties(): void
    {
        \$arrayProperties = [{$propertyList}];
        \$testArrays = ComponentDataFactory::arrayData();
        
        foreach (\$arrayProperties as \$property) {
            foreach (\$testArrays as \$array) {
                \$component = \$this->createComponent([\$property => \$array]);
                \$this->assertEquals(\$array, \$component->\$property);
            }
        }
    }";
    }

    /**
     * Generate test for a specific method.
     */
    private function generateMethodTest(string $componentName, array $method): string
    {
        $methodName = $method['name'];
        $lowerComponentName = strtolower($componentName);
        
        return "    public function test_{$lowerComponentName}_{$methodName}_method(): void
    {
        \$component = \$this->createComponent();
        
        if (method_exists(\$component, '{$methodName}')) {
            \$result = \$component->{$methodName}();
            // Add specific assertions based on expected return type
            \$this->assertNotNull(\$result);
        }
    }";
    }

    /**
     * Generate rendering test.
     */
    private function generateRenderingTest(string $componentName): string
    {
        $lowerComponentName = strtolower($componentName);
        
        return "    public function test_{$lowerComponentName}_renders_successfully(): void
    {
        \$component = \$this->createComponent();
        \$view = \$component->render();
        \$html = \$view->render();
        
        \$this->assertNotEmpty(\$html);
        \$this->assertTrue(TestHelpers::assertValidHtml(\$html));
    }";
    }

    /**
     * Generate accessibility test.
     */
    private function generateAccessibilityTest(string $componentName): string
    {
        $lowerComponentName = strtolower($componentName);
        
        return "    public function test_{$lowerComponentName}_accessibility_compliance(): void
    {
        \$component = \$this->createComponent();
        \$view = \$component->render();
        \$html = \$view->render();
        
        \$validation = TestHelpers::validateHtmlStructure(\$html);
        \$this->assertTrue(\$validation['is_valid'], 
            '{$componentName} should have valid HTML structure. Issues: ' . implode(', ', \$validation['issues'])
        );
    }";
    }

    /**
     * Generate security test.
     */
    private function generateSecurityTest(string $componentName): string
    {
        $lowerComponentName = strtolower($componentName);
        
        return "    public function test_{$lowerComponentName}_security_against_xss(): void
    {
        \$xssPayloads = TestHelpers::securityTestPayloads();
        
        foreach (\$xssPayloads as \$payload) {
            \$component = \$this->createComponent(['label' => \$payload]);
            \$view = \$component->render();
            \$html = \$view->render();
            
            \$this->assertStringNotContainsString('<script', \$html);
            \$this->assertStringNotContainsString('javascript:', \$html);
        }
    }";
    }

    /**
     * Generate performance test.
     */
    private function generatePerformanceTest(string $componentName): string
    {
        $lowerComponentName = strtolower($componentName);
        
        return "    public function test_{$lowerComponentName}_performance(): void
    {
        \$performance = TestHelpers::measureRenderingPerformance(\$this->createComponent(), 10);
        \$this->assertLessThan(100, \$performance['average_time'], 
            '{$componentName} rendering should be under 100ms on average'
        );
    }";
    }

    /**
     * Build the complete test file content.
     */
    private function buildTestFileContent(
        string $testClassName,
        string $componentClass,
        string $componentName,
        array $defaultProperties,
        array $requiredProperties,
        string $testMethods
    ): string {
        $defaultPropertiesStr = $this->arrayToPhpString($defaultProperties, 8);
        $requiredPropertiesStr = $this->arrayToPhpString($requiredProperties, 8);
        
        return "<?php

namespace {$this->namespace};

use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentTestCase;
use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentDataFactory;
use ArtisanPack\LivewireUiComponents\Tests\Support\TestHelpers;
use {$componentClass};

/**
 * Comprehensive unit tests for the {$componentName} component.
 * 
 * Auto-generated test class that extends ComponentTestCase to inherit
 * common testing patterns and implements component-specific tests.
 */
class {$testClassName} extends ComponentTestCase
{
    protected string \$componentClass = {$componentName}::class;

    protected array \$defaultProperties = {$defaultPropertiesStr};

    protected array \$requiredProperties = {$requiredPropertiesStr};

{$testMethods}
}
";
    }

    /**
     * Helper methods.
     */
    private function extractClassName(string $file): ?string
    {
        $content = file_get_contents($file);
        if (preg_match('/namespace\s+([^;]+);/', $content, $namespaceMatches) &&
            preg_match('/class\s+(\w+)/', $content, $classMatches)) {
            return $namespaceMatches[1] . '\\' . $classMatches[1];
        }
        return null;
    }

    private function isValidComponent(string $className): bool
    {
        try {
            $reflection = new ReflectionClass($className);
            return $reflection->isSubclassOf('Illuminate\View\Component') && 
                   !$reflection->isAbstract();
        } catch (\Exception $e) {
            return false;
        }
    }

    private function getParameterType(ReflectionParameter $parameter): string
    {
        $type = $parameter->getType();
        if (!$type) return 'mixed';
        
        if ($type instanceof \ReflectionUnionType) {
            return implode('|', array_map(fn($t) => $t->getName(), $type->getTypes()));
        }
        
        return $type->getName();
    }

    private function arrayToPhpString(array $array, int $indent = 0): string
    {
        if (empty($array)) {
            return '[]';
        }
        
        $spaces = str_repeat(' ', $indent);
        $items = [];
        
        foreach ($array as $key => $value) {
            if (is_string($key)) {
                $items[] = "'{$key}' => " . var_export($value, true);
            } else {
                $items[] = var_export($value, true);
            }
        }
        
        return "[\n{$spaces}    " . implode(",\n{$spaces}    ", $items) . "\n{$spaces}]";
    }
}