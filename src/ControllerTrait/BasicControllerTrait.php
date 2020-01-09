<?php


namespace LamBelcebur\MvcBasicTools\ControllerTrait;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Laminas\Http\PhpEnvironment\Request;
use Laminas\Http\PhpEnvironment\Response;
use Laminas\Http\Response as HttpResponse;
use Laminas\I18n\Translator\Translator;
use Laminas\Mvc\Controller\Plugin\Params;
use Laminas\Mvc\Controller\Plugin\Redirect;
use Laminas\Mvc\Controller\Plugin\Url;
use Laminas\Mvc\I18n\Router\TranslatorAwareTreeRouteStack;
use Laminas\Mvc\I18n\Translator as MvcTranslator;
use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\Paginator\Paginator;
use Laminas\Router\Http\TreeRouteStack;
use Laminas\Router\RouteStackInterface;
use Laminas\Router\SimpleRouteStack;
use Laminas\View\Model\JsonModel;
use LamBelcebur\MvcBasicTools\Controller\Plugin\AuthenticatePlugin;


/**
 * Trait BasicControllerTrait
 * @package LamBelcebur\MvcBasicTools\ControllerTrait
 *
 * @method AuthenticatePlugin authenticatePlugin()
 * @method FlashMessenger flashMessenger()
 * @method Params params()
 * @method Request getRequest()
 * @method Response getResponse()
 * @method Redirect redirect()
 * @method Url url()
 */
trait BasicControllerTrait
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var DoctrineObject */
    protected $hydrator;

    /** @var MvcTranslator */
    protected $mvcTranslator;

    /** @var TranslatorAwareTreeRouteStack|TreeRouteStack|SimpleRouteStack|RouteStackInterface */
    protected $router;

    /** @var Translator */
    protected $translator;

    /** @var int defined from PHP CONSTANT "DEFAULT_LIMIT_ITEMS_PER_PAGE", if constant is not defined the value is 50 */
    protected $limitItemsPerPage;

    /** @var int defined from query param "page", by default 1 */
    protected $currentPageNumber;

    /** @var int defined from query param "limit", by default $this->limitItemsPerPage */
    protected $itemCountPerPage;


    public function onDispatch(MvcEvent $e)
    {
        $this->currentPageNumber = (int)$this->params()->fromQuery('page', 1);
        $this->itemCountPerPage = (int)$this->params()->fromQuery('limit', $this->limitItemsPerPage);
        return parent::onDispatch($e);
    }


    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @return TranslatorAwareTreeRouteStack|TreeRouteStack|SimpleRouteStack|RouteStackInterface
     */
    public function getRouter(): RouteStackInterface
    {
        return $this->router;
    }

    /**
     * @param $message
     * @param string $textDomain
     * @param string|null $locale
     * @return string
     */
    public function translate($message, string $textDomain = 'default', string $locale = null): string
    {
        return $this->getMvcTranslator()->translate($message, $textDomain, $locale);
    }

    /**
     * @return MvcTranslator
     */
    public function getMvcTranslator(): MvcTranslator
    {
        return $this->mvcTranslator;
    }

    /**
     * @return Translator
     */
    public function getTranslator(): Translator
    {
        return $this->translator;
    }

    /**
     * @param string $routeName
     * @param array $routeParams
     * @param int $statusCode
     * @return HttpResponse|null
     */
    public function checkAndRedirectUrl(string $routeName, array $routeParams = [], int $statusCode = 301): ?HttpResponse
    {
        if ($this->getRequest()->getUri()->getPath() !== $this->url()->fromRoute($routeName, $routeParams)) {
            return $this->redirect()->toRoute($routeName, $routeParams, [], true)->setStatusCode($statusCode);
        }
        return null;
    }

    /**
     * @param Paginator $paginator
     * @param bool $isJsonModel
     * @param array $extraParams
     * @return array
     */
    public function getPaginatorArrayResult(Paginator $paginator, bool $isJsonModel = true, array $extraParams = []): array
    {
        $pageCount = $paginator->count();
        $page = $paginator->getCurrentPageNumber();
        $data = $paginator;
        if ($isJsonModel) {
            $jsonModel = new JsonModel($paginator);
            $data = $jsonModel->getVariables();
        }

        $params = [
            'nextPage' => $pageCount > $page && $page > 0 ? $this->url()->fromRoute(null, [], [
                'query' => [
                    'page' => $page + 1,
                    'limit' => $paginator->getItemCountPerPage(),
                ],
                'force_canonical' => true,
            ], true) : null,
            'previewPage' => $page <= $pageCount && $page > 1 ? $this->url()->fromRoute(null, [], [
                'query' => [
                    'page' => $page - 1,
                    'limit' => $paginator->getItemCountPerPage(),
                ],
                'force_canonical' => true,
            ], true) : null,
            'page' => $paginator->getCurrentPageNumber(),
            'pages' => $pageCount,
            'limit' => $paginator->getItemCountPerPage(),
            'total' => $paginator->getTotalItemCount(),
            'data' => $data,
        ];

        return array_merge($params, $extraParams);
    }

    /**
     * @param QueryBuilder $qb
     * @param bool $fetchJoinCollection
     * @param string|null $prefix
     * @param int $queryHydrationMode
     * @return Paginator
     */
    public function createPaginator(QueryBuilder $qb, bool $fetchJoinCollection = false, string $prefix = null, int $queryHydrationMode = Query::HYDRATE_OBJECT): Paginator
    {
        /** Languages near Admin 3  */
        $adapter = new DoctrineAdapter(new DoctrinePaginator($qb, $fetchJoinCollection));
        $adapter->getPaginator()->getQuery()->setHydrationMode($queryHydrationMode);
        $paginator = new Paginator($adapter);
        $prefix = $prefix ? $prefix . '-' : '';
        $paginator
            ->setCurrentPageNumber((int)$this->params()->fromQuery("{$prefix}page", 1))
            ->setItemCountPerPage((int)$this->params()->fromQuery("{$prefix}limit", $this->limitItemsPerPage));
        return $paginator;
    }

}
