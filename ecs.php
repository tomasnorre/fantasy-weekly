<?php

declare(strict_types=1);

use PHP_CodeSniffer\Standards\Generic\Sniffs\Metrics\CyclomaticComplexitySniff;
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\Import\NoLeadingImportSlashFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\TernaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Phpdoc\AlignMultilineCommentFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\NoBlankLinesAfterPhpdocFixer;
use PhpCsFixer\Fixer\Whitespace\NoExtraBlankLinesFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(
        Option::SETS,
        [
            SetList::PSR_12,
            SetList::COMMON
        ]
    );

    $parameters->set(Option::SKIP,
        [
            __DIR__ . '/app/Console/Kernel.php',
            __DIR__ . '/app/Exceptions',
            __DIR__ . '/app/Http/Middleware',
            __DIR__ . '/app/Http/Controllers/Controller.php',
            __DIR__ . '/app/Providers',
            PhpCsFixer\Fixer\StringNotation\ExplicitStringVariableFixer::class,
        ]
    );

    $parameters->set(
        Option::PATHS,
        [
            __DIR__ . '/app',
            __DIR__ . '/tests',
        ]
    );

    $services = $containerConfigurator->services();

    $services->set(ArraySyntaxFixer::class)
        ->call('configure', [['syntax' => 'short']]);

    $services->set(ConcatSpaceFixer::class)
        ->call('configure', [['spacing' => 'one']]);

    $services->set(BinaryOperatorSpacesFixer::class)
        ->call('configure', [['default' => 'single_space']]);

    $services->set(NoExtraBlankLinesFixer::class);

    $services->set(TernaryOperatorSpacesFixer::class);

    $services->set(NoBlankLinesAfterPhpdocFixer::class);

    $services->set(AlignMultilineCommentFixer::class)
        ->call('configure', [['comment_type' => 'phpdocs_only']]);

    $services->set(GeneralPhpdocAnnotationRemoveFixer::class)
        ->call('configure', [['annotations' => ['author', 'since']]]);

    $services->set(NoLeadingImportSlashFixer::class);

    $services->set(NoUnusedImportsFixer::class);

    $services->set(OrderedImportsFixer::class)
        ->call('configure', [['imports_order' => ['class', 'const', 'function']]]);

    $services->set(CyclomaticComplexitySniff::class)
        ->property('complexity', 20)
        ->property('absoluteComplexity', 20);
};
