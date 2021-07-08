<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Algosemueve
 * @author     Ciro Artigot Cordero <info@algosemueve.es>
 * @copyright  2021 Ciro Artigot Cordero
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */

namespace Algosemueve\Component\Algosemueve\Site\Service;

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Factory;
use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Categories\CategoryInterface;
use Joomla\Database\DatabaseInterface;
use Joomla\CMS\Menu\AbstractMenu;

/**
 * Class AlgosemueveRouter
 *
 */
class Router extends RouterView
{
	private $noIDs;
	/**
	 * The category factory
	 *
	 * @var CategoryFactoryInterface
	 *
	 * @since  4.0.0
	 */
	private $categoryFactory;

	/**
	 * The category cache
	 *
	 * @var  array
	 *
	 * @since  4.0.0
	 */
	private $categoryCache = [];

	public function __construct(SiteApplication $app, AbstractMenu $menu, CategoryFactoryInterface $categoryFactory, DatabaseInterface $db)
	{
		$params = Factory::getApplication()->getParams('com_algosemueve');
		$this->noIDs = (bool) $params->get('sef_ids');
		$this->categoryFactory = $categoryFactory;
		
		$leads = new RouterViewConfiguration('leads');
		$blankviews = new RouterViewConfiguration('blankviews');
		
		$leads->setKey('id')->setNestable();
		$this->registerView($leads);
		
		$blankviews->setKey('id')->setNestable();
		$this->registerView($blankviews);

		
		$lead = new RouterViewConfiguration('lead');
		$lead->setKey('id')->setParent($leads, 'catid');
		$this->registerView($lead);
		$leadform = new RouterViewConfiguration('leadform');
		$leadform->setKey('id');
		$this->registerView($leadform);

		parent::__construct($app, $menu);

		$this->attachRule(new MenuRules($this));
		$this->attachRule(new StandardRules($this));
		$this->attachRule(new NomenuRules($this));
	}


	
			/**
			 * Method to get the segment(s) for a category
			 *
			 * @param   string  $id     ID of the category to retrieve the segments for
			 * @param   array   $query  The request that is built right now
			 *
			 * @return  array|string  The segments of this item
			 */
			public function getLeadsSegment($id, $query)
			{
				$category = $this->getCategories(["access" => true])->get($id);

				if ($category)
				{
					$path = array_reverse($category->getPath(), true);
					$path[0] = '1:root';

					if ($this->noIDs)
					{
						foreach ($path as &$segment)
						{
							list($id, $segment) = explode(':', $segment, 2);
						}
					}

					return $path;
				}

				return array();
			}
		
	
		public function getBlankviewsSegment($id, $query)
		{
			return array((int) $id => $id);
		}
	
	
	
	
			/**
			 * Method to get the segment(s) for an leadform
			 *
			 * @param   string  $id     ID of the leadform to retrieve the segments for
			 * @param   array   $query  The request that is built right now
			 *
			 * @return  array|string  The segments of this item
			 */
			public function getLeadformSegment($id, $query)
			{
				return $this->getLeadSegment($id, $query);
			}

	
			/**
			 * Method to get the id for a category
			 *
			 * @param   string  $segment  Segment to retrieve the ID for
			 * @param   array   $query    The request that is parsed right now
			 *
			 * @return  mixed   The id of this item or false
			 */
			public function getLeadsId($segment, $query)
			{
				if (isset($query['id']))
				{
					$category = $this->getCategories(["access" => true])->get($query['id']);

					if ($category)
					{
						foreach ($category->getChildren() as $child)
						{
							if ($this->noIDs)
							{
								if ($child->alias == $segment)
								{
									return $child->id;
								}
							}
							else
							{
								if ($child->id == (int) $segment)
								{
									return $child->id;
								}
							}
						}
					}
				}

				return false;
			}
		/**
		 * Method to get the segment(s) for an lead
		 *
		 * @param   string  $segment  Segment of the lead to retrieve the ID for
		 * @param   array   $query    The request that is parsed right now
		 *
		 * @return  mixed   The id of this item or false
		 */
		public function getLeadId($segment, $query)
		{
			return (int) $segment;
		}
			/**
			 * Method to get the segment(s) for an leadform
			 *
			 * @param   string  $segment  Segment of the leadform to retrieve the ID for
			 * @param   array   $query    The request that is parsed right now
			 *
			 * @return  mixed   The id of this item or false
			 */
			public function getLeadformId($segment, $query)
			{
				return $this->getLeadId($segment, $query);
			}

	/**
	 * Method to get categories from cache
	 *
	 * @param   array  $options   The options for retrieving categories
	 *
	 * @return  CategoryInterface  The object containing categories
	 *
	 * @since   4.0.0
	 */
	private function getCategories(array $options = []): CategoryInterface
	{
		$key = serialize($options);

		if (!isset($this->categoryCache[$key]))
		{
			$this->categoryCache[$key] = $this->categoryFactory->createCategory($options);
		}

		return $this->categoryCache[$key];
	}
}
