<?php
/**
 * Created by PhpStorm.
 * User: Deibi
 * Date: 01/10/2018
 * Time: 19:25
 */

namespace LamBelcebur\MvcBasicTools\Controller;


use Doctrine\ORM\EntityManager;
use Laminas\Form\FormElementManager\FormElementManagerV3Polyfill as FormManager;
use Laminas\Mvc\I18n\Router\TranslatorAwareTreeRouteStack;
use Laminas\Mvc\I18n\Translator as MvcTranslator;
use Laminas\Router\Http\TreeRouteStack;
use Laminas\Router\RouteStackInterface;
use Laminas\Router\SimpleRouteStack;

abstract class FormManagerRestfulController extends BaseRestfulController
{
    /**
     * @var FormManager
     */
    protected $formManager;

    /**
     * FormManagerController constructor.
     * @param FormManager $formManager
     * @param EntityManager $entityManager
     * @param MvcTranslator $mvcTranslator
     * @param TranslatorAwareTreeRouteStack|TreeRouteStack|SimpleRouteStack|RouteStackInterface $router
     */
    public function __construct(FormManager $formManager, EntityManager $entityManager, MvcTranslator $mvcTranslator, RouteStackInterface $router)
    {
        $this->formManager = $formManager;
        parent::__construct($entityManager, $mvcTranslator, $router);
    }

    /**
     * @return FormManager
     */
    public function getFormManager(): FormManager
    {
        return $this->formManager;
    }


}
