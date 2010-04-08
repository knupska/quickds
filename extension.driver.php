<?php

	class Extension_QuickDS extends Extension {
	
		public function about() {
			return array(
				'name'			=> 'QuickDS',
				'version'		=> '1.2',
				'release-date'	=> '2010-04-05',
				'author'		=> array(
					'name'			=> 'Nathan Martin',
					'website'		=> 'http://knupska.com',
					'email'			=> 'nathan@knupska.com'
				),
				'description'	=> 'Quickly add & remove datasources from pages.'
	 		);
		}
		
		public function getSubscribedDelegates() {
			return array(
				array(
					'page'		=> '/system/preferences/',
					'delegate'	=> 'AddCustomPreferenceFieldsets',
					'callback'	=> 'edit'
				),
				array(
					'page'		=> '/backend/',
					'delegate'	=> 'InitaliseAdminPageHead',
					'callback'	=> 'initaliseAdminPageHead'
				)
			);
		}
		
		public function fetchNavigation() {
			return array(
				array(
					'location'	=> 'Blueprints',
					'name'	=> 'QuickDS',
					'link'	=> '/edit/'
				)
			);
		}
		
		public function initaliseAdminPageHead($context) {
			$page = $context['parent']->Page;
			if ($page instanceof contentExtensionQuickDSEdit) {
				$page->addScriptToHead(URL . '/extensions/quickds/assets/collapse_quickds.js', 100000);
			}
			if ($page instanceof contentBlueprintsDatasources) {
				$page->addScriptToHead(URL . '/extensions/quickds/assets/shortcut_quickds.js', 100000);
			}
		}
	}

?>