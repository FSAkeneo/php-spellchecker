<?php

declare(strict_types=1);

use PhpSpellcheck\Exception\LogicException;
use PhpSpellcheck\Misspelling;
use PhpSpellcheck\Spellchecker\PHPPspell;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class PHPPspellTest extends TestCase
{
    public function setUp(): void
    {
        if (!extension_loaded('pspell')) {
            Assert::markTestSkipped('Pspell extension is not loaded');
        }
    }

    public function testCheck(): void
    {
        $pspell = new PHPPspell(PSPELL_FAST);
        /** @var Misspelling[] $misspellings */
        $misspellings = iterator_to_array($pspell->check('mispell', ['en'], ['ctx']));

        $this->assertSame($misspellings[0]->getContext(), ['ctx']);
        $this->assertSame($misspellings[0]->getWord(), 'mispell');
        $this->assertSame($misspellings[0]->getOffset(), 0);
        $this->assertSame($misspellings[0]->getLineNumber(), 1);
        $this->assertNotEmpty($misspellings[0]->getSuggestions());
    }

    public function testGetSupportedLanguages(): void
    {
        $this->expectException(LogicException::class);
        $pspell = new PHPPspell(PSPELL_FAST);
        $pspell->getSupportedLanguages();
    }
}
