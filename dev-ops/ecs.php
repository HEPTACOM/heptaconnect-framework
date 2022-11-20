<?php

declare(strict_types=1);

use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\AssignmentInConditionSniff;
use PhpCsFixer\Fixer\CastNotation\ModernizeTypesCastingFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfAccessorFixer;
use PhpCsFixer\Fixer\ConstantNotation\NativeConstantInvocationFixer;
use PhpCsFixer\Fixer\FunctionNotation\FopenFlagsFixer;
use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use PhpCsFixer\Fixer\FunctionNotation\NullableTypeDeclarationForDefaultNullValueFixer;
use PhpCsFixer\Fixer\FunctionNotation\SingleLineThrowFixer;
use PhpCsFixer\Fixer\FunctionNotation\VoidReturnFixer;
use PhpCsFixer\Fixer\LanguageConstruct\ExplicitIndirectVariableFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer;
use PhpCsFixer\Fixer\Operator\OperatorLinebreakFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocOrderFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSummaryFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitConstructFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertInternalTypeFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMockFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMockShortWillReturnFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestCaseStaticMethodCallsFixer;
use PhpCsFixer\Fixer\ReturnNotation\NoUselessReturnFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\StringNotation\ExplicitStringVariableFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBeforeStatementFixer;
use PhpCsFixer\Fixer\Whitespace\CompactNullableTypehintFixer;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet\RuleSet;
use PhpCsFixerCustomFixers\Fixer\NoImportFromGlobalNamespaceFixer;
use PhpCsFixerCustomFixers\Fixer\NoSuperfluousConcatenationFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessParenthesisFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessStrlenFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocNoIncorrectVarAnnotationFixer;
use PhpCsFixerCustomFixers\Fixer\SingleSpaceAfterStatementFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayListItemNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayOpenerAndCloserNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\StandaloneLineInMultilineArrayFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $config): void {
    $config->parallel();

    $services = $config->services();

    /* Use @Symfony and @Symfony:risky https://github.com/symplify/symplify/pull/4070#issuecomment-1135896943 */
    $fixerFactory = new FixerFactory();
    $fixerFactory->registerBuiltInFixers();
    $ruleSet = new RuleSet([
        '@Symfony' => true,
        '@Symfony:risky' => true,
    ]);
    $fixerFactory->useRuleSet($ruleSet);
    foreach ($fixerFactory->getFixers() as $fixer) {
        $configurator = $services->set($fixer::class);

        if ($ruleConfiguration = $ruleSet->getRuleConfiguration($fixer->getName())) {
            $configurator->call('configure', [$ruleConfiguration]);
        }
    }
    /* End custom @Symfony and @Symfony:risky */

    $config->import(SetList::ARRAY);
    $config->import(SetList::CONTROL_STRUCTURES);
    $config->import(SetList::STRICT);
    $config->import(SetList::PSR_12);
    $config->import(SetList::SPACES);
    $config->import(SetList::NAMESPACES);
    $config->import(SetList::COMMENTS);
    $config->import(SetList::DOCBLOCK);

    $services->set(ModernizeTypesCastingFixer::class);
    $services->set(ClassAttributesSeparationFixer::class)
        ->call('configure', [['elements' => ['property' => 'one', 'method' => 'one']]]);
    $services->set(FopenFlagsFixer::class);
    $services->set(MethodArgumentSpaceFixer::class)
        ->call('configure', [['on_multiline' => 'ensure_fully_multiline']]);
    $services->set(NativeFunctionInvocationFixer::class)
        ->call('configure', [[
            'include' => [NativeFunctionInvocationFixer::SET_COMPILER_OPTIMIZED],
            'scope' => 'namespaced',
            'strict' => false,
        ]]);
    $services->set(NativeConstantInvocationFixer::class);
    $services->set(NullableTypeDeclarationForDefaultNullValueFixer::class);
    $services->set(VoidReturnFixer::class);
    $services->set(ConcatSpaceFixer::class)
        ->call('configure', [['spacing' => 'one']]);
    $services->set(OperatorLinebreakFixer::class);
    $services->set(GeneralPhpdocAnnotationRemoveFixer::class)
        ->call('configure', [['annotations' => ['copyright', 'category']]]);
    $services->set(NoSuperfluousPhpdocTagsFixer::class)
        ->call('configure', [['allow_unused_params' => true]]);
    $services->set(PhpdocLineSpanFixer::class);
    $services->set(PhpdocOrderFixer::class);
    $services->set(PhpUnitConstructFixer::class);
    $services->set(PhpUnitDedicateAssertFixer::class)
        ->call('configure', [['target' => 'newest']]);
    $services->set(PhpUnitDedicateAssertInternalTypeFixer::class);
    $services->set(PhpUnitMockFixer::class);
    $services->set(PhpUnitMockShortWillReturnFixer::class);
    $services->set(PhpUnitTestCaseStaticMethodCallsFixer::class);
    $services->set(NoUselessReturnFixer::class);
    $services->set(DeclareStrictTypesFixer::class);
    $services->set(BlankLineBeforeStatementFixer::class);
    $services->set(CompactNullableTypehintFixer::class);
    $services->set(NoImportFromGlobalNamespaceFixer::class);
    $services->set(NoSuperfluousConcatenationFixer::class);
    $services->set(NoUselessCommentFixer::class);
    $services->set(PhpdocNoIncorrectVarAnnotationFixer::class);
    $services->set(SingleSpaceAfterStatementFixer::class);
    $services->set(NoUselessParenthesisFixer::class);
    $services->set(NoUselessStrlenFixer::class);

    $parameters = $config->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/bin/phpstan/src',
        __DIR__ . '/../src',
        __DIR__ . '/../test',
    ]);
    $parameters->set(Option::SKIP, [
        ArrayOpenerAndCloserNewlineFixer::class => null,
        ArrayListItemNewlineFixer::class => null,
        SingleLineThrowFixer::class => null,
        SelfAccessorFixer::class => null,
        ExplicitIndirectVariableFixer::class => null,
        PhpdocSummaryFixer::class => null,
        ExplicitStringVariableFixer::class => null,
        StandaloneLineInMultilineArrayFixer::class => null,
        NotOperatorWithSuccessorSpaceFixer::class => null,
        AssignmentInConditionSniff::class => null,
    ]);
};
