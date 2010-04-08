<?php

	require_once(TOOLKIT . '/class.administrationpage.php');
	require_once(TOOLKIT . '/class.datasourcemanager.php');	
	
	class contentExtensionQuickDSEdit extends AdministrationPage {
		protected $_driver;
		private $_pages;
		
		public function __viewIndex() {
			$this->setPageType('form');
			$this->setTitle('Symphony &ndash; QuickDS');
			
			$this->appendSubheading('QuickDS');
			
			$this->_pages = $this->__getPages();
			$datasources = $this->__getDataSources();
			
			if(count($this->_pages) > 0 && count($datasources) > 0) {
			
				foreach($datasources as $datasource) {
					$group = new XMLElement('div');
					$group->setAttribute('class', 'group');
					
					$total_selected = $this->__createPageList($group, $datasource['handle']);
					
					$container = new XMLElement('fieldset');
					$container->setAttribute('class', 'settings');
					$container->appendChild(
						new XMLElement('legend', $datasource['name'] . ' (' . $total_selected . ')')
					);
					$container->appendChild($group);
					$this->Form->appendChild($container);
				}
				
			} else {
				
				if (count($this->_pages) == 0) {
					$container = new XMLElement('fieldset');
					$container->setAttribute('class', 'settings error');
					$container->appendChild(new XMLElement('legend', 'No Pages Found'));
					$group = new XMLElement('div');
					$group->setAttribute('class', 'group');
					$group->appendChild(Widget::Label('Please create some pages before using QuickDS'));
					$container->appendChild($group);
					$this->Form->appendChild($container);
				}
				
				if (count($datasources) == 0) {
					$container = new XMLElement('fieldset');
					$container->setAttribute('class', 'settings error');
					$container->appendChild(new XMLElement('legend', 'No Datasources Found'));
					$group = new XMLElement('div');
					$group->setAttribute('class', 'group');
					$group->appendChild(Widget::Label('Please create some datasources before using QuickDS'));
					$container->appendChild($group);
					$this->Form->appendChild($container);
				}
				return;
				
			}
			
			$div = new XMLElement('div');
			$div->setAttribute('class', 'actions');
			$attr = array('accesskey' => 's');
			$div->appendChild(Widget::Input('action[save]', 'Save Changes', 'submit', $attr));
			$this->Form->appendChild($div);
		}
		
		public function __getPages() {
			return Symphony::Database()->fetch("
				SELECT
					p.*
				FROM
					tbl_pages AS p
				ORDER BY
					p.sortorder ASC
			");
		}
		
		public function __getDataSources() {
			$DSManager = new DatasourceManager($this->_Parent);
			return $DSManager->listAll();
		}
		
		public function __createPageList($context, $datasource) {
			$options = array();
			$total_selected = 0;
			foreach ($this->_pages as $page) {
				$selected = in_array($datasource, explode(',', $page['data_sources']));
				if($selected) $total_selected++;
				
				$options[] = array(
					$page['id'], $selected, '/' . $this->_Parent->resolvePagePath($page['id'])
				);
			}
			
			$section = Widget::Label('Pages');
			$section->appendChild(Widget::Select(
				'settings[' . $datasource . '][]', $options, array(
					'multiple'	=> 'multiple'
				)
			));
			
			$context->appendChild($section);
			return $total_selected;
		}
		
		public function __actionIndex() {
			
			if (@isset($_POST['action']['save'])) {
			
				// extract the settings
				$settings  = @$_POST['settings'];
				
				// extract all the pages
				$this->_pages = $this->__getPages();
				
				// create an empty datasource array for each page
				$page_datasources = array();
				foreach($this->_pages as $page) $page_datasources[$page['id']] = array();
				
				// loop through the datasources and add to each page
				foreach($settings as $datasource => $pages) {
					foreach($pages as $page) $page_datasources[$page][] = $datasource;
				}
				
				// loop through the final datasources and add to the database
				$error = false;
				foreach($page_datasources as $page => $datasources) {
					
					// create the fields to be updated
					$fields = array('data_sources' => @implode(',', $datasources));
					
					// update the fields
					if (!Symphony::Database()->update($fields, 'tbl_pages', "`id` = '$page'")) {
						$error = true;
						break;
					}
				}
				
				// show the success message
				if(!$error) {
					$this->pageAlert(
						__(
							'Datasources updated at %1$s.', 
							array(DateTimeObj::getTimeAgo(__SYM_TIME_FORMAT__))
						), 
						Alert::SUCCESS);
					return;
				}
				
				// show the error message
				$this->pageAlert(
					__(
						'Unknown errors occurred while attempting to save. Please check your <a href="%s">activity log</a>.',
						array(URL . '/symphony/system/log/')
					),
					Alert::ERROR);
			}
		}
	}
	
?>