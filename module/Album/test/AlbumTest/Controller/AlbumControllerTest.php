<?phpnamespace AlbumTest\Controller;use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;use Zend\Mvc\Controller\AbstractActionController;use Zend\View\Model\ViewModel;//use Album\Model\Album;//use Album\Form\AlbumForm;use Doctrine\ORM\EntityManager;use Album\Entity\Album;class AlbumControllerTest extends AbstractHttpControllerTestCase{	protected $traceError = true;    public function setUp()    {        $this->setApplicationConfig(            include __DIR__ .'/../../../../../config/application.config.php'        );        parent::setUp();    }		/*public function setUp()	{		$di = Bootstrap::getDi();		$this->controller = $di->newInstance('Album\Controller\AlbumController');		$this->request    = new Request();		$this->routeMatch = new RouteMatch(array('controller' => 'album'));		$this->event      = new MvcEvent();		$this->event->setRouteMatch($this->routeMatch);		$this->controller->setEvent($this->event);		$this->controller->setServiceLocator(Bootstrap::getServiceManager());	}*/		public function testIndexActionCanBeAccessed()	{		$this->dispatch('/album');		$this->assertResponseStatusCode(200);		$this->assertModuleName('Album');		$this->assertControllerName('Album\Controller\Album');		$this->assertControllerClass('AlbumController');		$this->assertMatchedRouteName('album');	}	public function testSuccess()    {        $this->assertContains(4, array(1, 2, 3, 4));    }}