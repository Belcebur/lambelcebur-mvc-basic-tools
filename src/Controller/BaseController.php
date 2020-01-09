<?php
/**
 * Created by PhpStorm.
 * User: Deibi
 * Date: 01/10/2018
 * Time: 19:25
 */

namespace LamBelcebur\MvcBasicTools\Controller;


use Doctrine\ORM\EntityManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\I18n\Router\TranslatorAwareTreeRouteStack;
use Laminas\Mvc\I18n\Translator as MvcTranslator;
use Laminas\Router\Http\TreeRouteStack;
use Laminas\Router\RouteStackInterface;
use Laminas\Router\SimpleRouteStack;
use LamBelcebur\MvcBasicTools\ControllerTrait\BasicControllerTrait;
use function defined;

/**
 * Class BaseController
 * @package LamBelcebur\MvcBasicTools\Controller
 */
abstract class BaseController extends AbstractActionController
{

    use BasicControllerTrait;

    /**
     * BaseController constructor.
     * @param EntityManager $entityManager
     * @param MvcTranslator $mvcTranslator
     * @param TranslatorAwareTreeRouteStack|TreeRouteStack|SimpleRouteStack|RouteStackInterface $router
     */
    public function __construct(EntityManager $entityManager, MvcTranslator $mvcTranslator, RouteStackInterface $router)
    {
        $this->limitItemsPerPage = defined('DEFAULT_LIMIT_ITEMS_PER_PAGE') ? DEFAULT_LIMIT_ITEMS_PER_PAGE : 50;
        $this->entityManager = $entityManager;
        $this->mvcTranslator = $mvcTranslator;
        $this->router = $router;
        $this->translator = $mvcTranslator->getTranslator();
    }
}
