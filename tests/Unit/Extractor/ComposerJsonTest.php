<?php
declare(strict_types=1);

namespace App\Tests\Unit\Extractor;

use App\Exception\Composer\DocsComposerMissingValueException;
use App\Exception\ComposerJsonInvalidException;
use App\Extractor\ComposerJson;
use PHPUnit\Framework\TestCase;

class ComposerJsonTest extends TestCase
{
    /**
     * @return array
     */
    public function attributesDataProvider(): array
    {
        return [
            ['name', 'foobar/baz'],
            ['type', 'foobar'],
            ['require', ['foobar/bark' => '^4.2']],
            ['authors', [['name' => 'Husel Pusel', 'email' => 'husel@example.com']]],
        ];
    }

    /**
     * @test
     */
    public function nameIsReturnedAsExpected(): void
    {
        $composerJson = new ComposerJson(['name' => 'foobar/baz']);
        $this->assertSame('foobar/baz', $composerJson->getName());
    }

    /**
     * @return array
     */
    public function emptyNameDataProvider(): array
    {
        return [
            [''],
            ['     '],
            [null],
        ];
    }

    /**
     * @test
     * @dataProvider emptyNameDataProvider
     * @param mixed $value
     */
    public function emptyNameThrowsException($value): void
    {
        $this->expectException(DocsComposerMissingValueException::class);
        $this->expectExceptionCode(1557309364);

        $composerJson = new ComposerJson(['name', $value]);
        $composerJson->getName();
    }

    /**
     * @test
     */
    public function typeIsReturnedAsExpected(): void
    {
        $composerJson = new ComposerJson(['type' => 'package']);
        $this->assertSame('package', $composerJson->getType());
    }

    /**
     * @return array
     */
    public function emptyTypeDataProvider(): array
    {
        return [
            [''],
            ['     '],
            [null],
        ];
    }

    /**
     * @test
     * @dataProvider emptyNameDataProvider
     * @param mixed $value
     */
    public function emptyTypeThrowsException($value): void
    {
        $this->expectException(DocsComposerMissingValueException::class);
        $this->expectExceptionCode(1557309364);

        $composerJson = new ComposerJson(['type', $value]);
        $composerJson->getType();
    }

    /**
     * @test
     */
    public function requirementsAreFoundCorrectly(): void
    {
        $composerJson = new ComposerJson(['require' => ['foobar/bark' => '^4.2']]);
        $this->assertTrue($composerJson->requires('foobar/bark'));
        $this->assertFalse($composerJson->requires('idonot/exist'));
    }

    /**
     * @test
     */
    public function emptyRequirementsDefinitionDoNotThrowException(): void
    {
        $composerJson = new ComposerJson(['require' => []]);
        $this->assertFalse($composerJson->requires('idonot/exist'));
    }

    /**
     * @test
     */
    public function missingRequirementsDefinitionDoNotThrowException(): void
    {
        $composerJson = new ComposerJson([]);
        $this->assertFalse($composerJson->requires('idonot/exist'));
    }

    /**
     * @test
     */
    public function emptyMinimumCmsCoreRequireThrowsException(): void
    {
        $composerJson = new ComposerJson([
            'name' => 'foobar/baz',
            'type' => 'typo3-cms-extension',
            'require' => ['foobar/bark' => '^4.2'],
            'authors' => [['name' => 'Husel Pusel', 'email' => 'husel@example.com']],
        ]);

        $this->expectException(DocsComposerMissingValueException::class);
        $this->expectExceptionCode(1558084137);
        $composerJson->getMinimumTypoVersion();
    }

    /**
     * @test
     */
    public function emptyMaximumCmsCoreRequireThrowsException(): void
    {
        $composerJson = new ComposerJson([
            'name' => 'foobar/baz',
            'type' => 'typo3-cms-extension',
            'require' => ['foobar/bark' => '^4.2'],
            'authors' => [['name' => 'Husel Pusel', 'email' => 'husel@example.com']],
        ]);

        $this->expectException(DocsComposerMissingValueException::class);
        $this->expectExceptionCode(1558084146);
        $composerJson->getMaximumTypoVersion();
    }

    /**
     * @test
     */
    public function maximumCmsCoreVersionIsReturnedAsExpected(): void
    {
        $composerJson = new ComposerJson([
            'name' => 'foobar/baz',
            'type' => 'typo3-cms-extension',
            'require' => ['foobar/bark' => '^4.2', 'typo3/cms-core' => '^9.5'],
            'authors' => [['name' => 'Husel Pusel', 'email' => 'husel@example.com']],
        ]);

        $this->assertEquals('9.5', $composerJson->getMaximumTypoVersion());

        $composerJson = new ComposerJson([
            'name' => 'foobar/baz',
            'type' => 'typo3-cms-extension',
            'require' => ['foobar/bark' => '^4.2', 'typo3/cms-core' => '8.7.*, <= 9.5.*'],
            'authors' => [['name' => 'Husel Pusel', 'email' => 'husel@example.com']],
        ]);

        $this->assertEquals('9.5', $composerJson->getMaximumTypoVersion());
    }

    /**
     * @test
     */
    public function minimumCmsCoreVersionIsReturnedAsExpected(): void
    {
        $composerJson = new ComposerJson([
            'name' => 'foobar/baz',
            'type' => 'typo3-cms-extension',
            'require' => ['foobar/bark' => '^4.2', 'typo3/cms-core' => '^9.5'],
            'authors' => [['name' => 'Husel Pusel', 'email' => 'husel@example.com']],
        ]);

        $this->assertEquals('9.5', $composerJson->getMinimumTypoVersion());

        $composerJson = new ComposerJson([
            'name' => 'foobar/baz',
            'type' => 'typo3-cms-extension',
            'require' => ['foobar/bark' => '^4.2', 'typo3/cms-core' => '8.7.*, <= 9.5.*'],
            'authors' => [['name' => 'Husel Pusel', 'email' => 'husel@example.com']],
        ]);

        $this->assertEquals('8.7', $composerJson->getMinimumTypoVersion());
    }

    /**
     * @test
     */
    public function exceptionIsNotThrownWhenCmsCoreVersionNotPresentInNonExtensionPackage(): void
    {
        $composerJson = new ComposerJson([
            'name' => 'foobar/baz',
            'type' => 'not-a-typo3-cms-extension',
            'require' => ['foobar/bark' => '^4.2'],
            'authors' => [['name' => 'Husel Pusel', 'email' => 'husel@example.com']],
        ]);

        $this->assertEquals('', $composerJson->getMinimumTypoVersion());
        $this->assertEquals('', $composerJson->getMaximumTypoVersion());
    }
}