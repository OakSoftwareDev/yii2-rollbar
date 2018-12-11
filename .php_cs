<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.DIRECTORY_SEPARATOR.'src')
;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@PSR1'    => true,
        '@PSR2'    => true,

        // custom
        'array_indentation' => true,
        'binary_operator_spaces' => [
            'default'   => 'align_single_space_minimal',
            'operators' => [
                '|' => 'no_space'
            ],
        ],
        'blank_line_before_statement' => false,
        'braces' => [
            'allow_single_line_closure' => true,
        ],
        'function_typehint_space'                => true,
        'increment_style'                        => false,
        'method_chaining_indentation'            => true,
        'modernize_types_casting'                => true,
        'multiline_comment_opening_closing'      => true,
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'new_line_for_chained_calls',
        ],
        'no_extra_blank_lines' => [
            'tokens' => [
                'switch',
                'break',
                'continue',
                'return',
                'throw',
                'use',
                'use_trait',
                'parenthesis_brace_block',
                'square_brace_block',
            ],
        ],
        'no_null_property_initialization'   => true,
        'no_useless_else'                   => true,
        'no_useless_return'                 => true,
        'non_printable_character'           => true,
        'not_operator_with_successor_space' => true,
        'ordered_class_elements'            => [
            'order' => [
                'use_trait',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                //'property_private',
                'construct',
                'destruct',
                'magic',
            ],
        ],
        'phpdoc_add_missing_param_annotation'           => true,
        'phpdoc_order'                                  => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types_order'                            => true,
        'phpdoc_separation'                             => false,
        'phpdoc_summary'                                => false,
        'self_accessor'                                 => true,
        'semicolon_after_instruction'                   => false,
        'unary_operator_spaces'                         => false,
        'yoda_style'                                    => false,
    ])
    ->setFinder($finder)
;
