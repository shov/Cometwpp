<?php

/*
 * This file is part of the Cometwpp package.
 *
 * (c) Alexandr Shevchenko [comet.by] alexandr@comet.by
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cometwpp;

use Cometwpp\Core\AjaxHandler;
use Cometwpp\Core\Core;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Make the sheaf of ajax callbacks (@see AjaxHandler) for one hook name
 * @package Cometwpp
 * @category Class
 */
class SheafAjaxSetter
{
    protected $ajaxHandler;
    protected $aSheafStore = [];

    public function __construct()
    {
        $this->ajaxHandler = Core::getInstance()->getAjaxHandler();
    }

    /**
     * Register full sheaf of hooks for ajax call name. If you use it twice, the second sheaf remove the first
     * Better way use @see SheafAjaxSetter::addCandidate()
     * @param $name
     * @param $sheaf
     */
    public function registerTheSheaf($name, $sheaf)
    {
        $name = (string)$name;
        if (false === $this->ajaxHandler->nameValidation($name)) throw new \InvalidArgumentException(sprintf("Bad name for ajax hook, %s passed", $name));
        if (is_callable($sheaf)) $sheaf = [$sheaf,];
        if (!is_array($sheaf)) throw new \InvalidArgumentException(sprintf("Sheaf should be array of callable or callable"));

        foreach ($sheaf as $hook) {
            if (!is_callable($hook)) throw new \InvalidArgumentException(sprintf("Sheaf should be array of callable or callable"));
        }

        $this->ajaxHandler->addHandler($name, function () use ($sheaf) {
            $ajaxResult = ['hasError' => false,];

            foreach ($sheaf as $i => $hook) {
                $reflection = new \ReflectionFunction($hook);
                $aArgument = $reflection->getParameters();
                $argumentCount = count($aArgument);
                if (1 === $argumentCount) {
                    if (!isset($_POST['data'])) $_POST['data'] = []; // safety
                    $ajaxResult[$i] = call_user_func($hook, $_POST['data']);

                } elseif (0 === $argumentCount) {
                    $ajaxResult[$i] = call_user_func($hook, $_POST['data']);
                }
                $ajaxResult['hasError'] &= $ajaxResult[$i]['hasError'];
                $reflection = null;
            }

            echo json_encode($ajaxResult);
        });
    }


    /**
     * Add candidate's sheaf sets to queue for full register
     * @param AjaxUserTrait $candidate
     * @return null
     * @throws \Exception
     */
    public function addCandidate(AjaxUserTrait $candidate)
    {
        $aCandidateSheafStore = $candidate->getSheafStore();

        if (!is_array($aCandidateSheafStore)) throw new \InvalidArgumentException(sprintf("Wrong candidate SheafStore!"));
        if (empty($aCandidateSheafStore)) return null; //nothing to register

        $candidateClassName = get_class($candidate);
        foreach ($aCandidateSheafStore as $name => $sheaf) {
            if (!is_array($sheaf) || !$this->ajaxHandler->nameValidation($name)) throw new \Exception(sprintf("Bad candidate Sheaf, class name %s", $candidateClassName));
            foreach ($sheaf as $hook) {
                if (!is_callable($hook)) throw new \Exception(sprintf("Bad candidate hook, class name %s", $candidateClassName));
            }

            $this->aSheafStore[$name] = array_merge($this->aSheafStore[$name], $sheaf);
        }
    }

    /**
     * Call it after adding all candidates, this push them's hooks to ajax handler
     */
    public function registerCandidates()
    {
        foreach ($this->aSheafStore as $name => $sheaf) {
            $this->registerTheSheaf($name, $sheaf);
        }
    }
}