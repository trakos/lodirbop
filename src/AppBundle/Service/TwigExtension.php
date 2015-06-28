<?php

namespace Trakos\AppBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;

class TwigExtension extends \Twig_Extension
{
    /**
     * @var Registry
     */
    protected $doctrine;

    function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('generateJavascriptConstants', [ $this, 'generateJavascriptConstants' ]),
            new \Twig_SimpleFunction('getDatabaseDictionaries', [ $this, 'getDatabaseDictionaries' ]),
        ];
    }

    public function generateJavascriptConstants($array)
    {
        $string = '<script type="text/javascript">';
        foreach ($array as $name => $value) {
            if (!$this->isValidJavascriptIdentifier($name)) {
                throw new \Twig_Error_Runtime("$name is not a valid javascript variable name");
            }
            $string .= sprintf("var %s = %s; ", $name, json_encode($value));
        }
        $string .= '</script>';

        return $string;
    }

    public function getDatabaseDictionaries()
    {
        return [
            'games' => $this->doctrine->getRepository('AppBundle:Game')->findAll(),
            'mercs' => $this->doctrine->getRepository('AppBundle:Merc')->findAll(),
            'communities' => $this->doctrine->getRepository('AppBundle:Community')->findAll(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'ibu.twig_extension';
    }

    /**
     * Returns whether $subject is a proper javascript identifier.
     * From http://www.geekality.net/2011/08/03/valid-javascript-identifier/
     *
     * @param $subject
     *
     * @return bool
     */
    protected function isValidJavascriptIdentifier($subject)
    {
        $identifierSyntax = '/^[$_\p{L}][$_\p{L}\p{Mn}\p{Mc}\p{Nd}\p{Pc}\x{200C}\x{200D}]*+$/u';

        $reserved_words = [
            'break',
            'do',
            'instanceof',
            'typeof',
            'case',
            'else',
            'new',
            'var',
            'catch',
            'finally',
            'return',
            'void',
            'continue',
            'for',
            'switch',
            'while',
            'debugger',
            'function',
            'this',
            'with',
            'default',
            'if',
            'throw',
            'delete',
            'in',
            'try',
            'class',
            'enum',
            'extends',
            'super',
            'const',
            'export',
            'import',
            'implements',
            'let',
            'private',
            'public',
            'yield',
            'interface',
            'package',
            'protected',
            'static',
            'null',
            'true',
            'false'
        ];

        return preg_match($identifierSyntax, $subject) && !in_array(mb_strtolower($subject, 'UTF-8'), $reserved_words);
    }
}