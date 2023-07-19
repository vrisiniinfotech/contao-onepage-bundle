<?php
/* 
 * @package   [contao-onepage-bundle]
 * @author    Anupam Chatterjee
 * @license   GNU/LGPL
 * @copyright Vrisini Infotech 2022 - 2026
 */

namespace Vrisiniinfotech\ContaoOnepageBundle\Hooks;

// Hook for one page contents
class OnePageAssorter
{
	protected static $strBuffer;
	/**
	 * Implement hook for page articles on landing (single) page
	 *
	 * @param	Integer 	$pageId
	 * @param	String 		$column
	 *
	 * @return String
	 */
	public function getMyArticles($pageId, $column){
		// Add one-page navigation JS to end of body
		$GLOBALS['TL_BODY'][] = '<script src="bundles/vionepage/js/vi-onepage-nav.js"></script>';
		
		// Assort all articles
		$pageArticles = $this->getPageArticles($pageId, $column);
		
		// Return the final buffer
		return $pageArticles;
	}
	
	/**
	 * Implement hook for content element on landing (single) page
	 *
	 * @param	Integer 	$pageId
	 * @param	String 		$column
	 * @param	Integer 	$pagePid
	 *
	 * @return String
	 */
	protected function getPageArticles($pageId, $column){
		$pageArticles = '';		
		// Find the initial page
		$objPage = \PageModel::findById($pageId);
		$objOtherPages = \Database::getInstance()->prepare("SELECT * FROM tl_page WHERE pid=? AND type='regular' AND hide_in_onepage='' ORDER BY sorting")->execute($objPage->pid);
		if($objOtherPages->numRows){
			while($objOtherPages->next()){ // Iteration for page
				$htmlPageArticles = '';
				$objArticles = \ArticleModel::findPublishedByPidAndColumn($objOtherPages->id, $column, ['order' => 'sorting']);
				if($objArticles){
					foreach($objArticles as $objArticle){ // Iteration for article
						$arrayArticleContent = [];
						// Create template object
						$objArticleTemplate = new \FrontendTemplate(($objArticle->customTpl ? $objArticle->customTpl : 'mod_article'));
						$arrayArticleCssId = unserialize($objArticle->cssID);
						$objArticleTemplate->cssID = ($arrayArticleCssId[0] ? 'id="' . $arrayArticleCssId[0] . '"' : '');
						$objArticleTemplate->class = $arrayArticleCssId[1];
						
						// Find article contents
						$objContents = \ContentModel::findPublishedByPidAndTable($objArticle->id, 'tl_article', ['order' => 'sorting']);
						if($objContents){
							foreach($objContents as $objContent){ // Iteration for content
									$objRowTemp = \ContentModel::findByPk($objContent->id);
									$strClass = \ContentElement::findClass($objContent->type);
									$objElementTemp = new $strClass($objRowTemp, 'main');
									$arrayArticleContent[] = $objElementTemp->generate();					
							}
							$objArticleTemplate->elements = $arrayArticleContent;
							$htmlPageArticles .= $objArticleTemplate->parse();
						}
					}
					$pageArticles .= '<section id="' . $objOtherPages->alias . '">' . $htmlPageArticles . '</section>';
				}
				
				// Assort child pages after current page - Recursion!
				$objChildPage = \PageModel::findByPid($objOtherPages->id);
				if($objChildPage && $oobjChildPage->hide_from_onepage == ''){
					$pageArticles .= $this->getPageArticles($objChildPage->id, $column);
				}
			}
		}
				
		return $pageArticles;
	}
	
	/**
	 * Implement hook for replacing the navigation template
	 *
	 * @param	Object	$templatemn
	 *
	 * @return 	void
	 */
	public function replaceNavTemplates(\Template $template){		
		if($template->getName() == 'nav_default' || strpos($template->getName(), 'nav_default') === 0){
			$template->setName('nav_default_onepage');
		}
		if($template->getName() == 'mod_navigation' || strpos($template->getName(), 'mod_navigation') === 0){
			$template->setName('mod_navigation_onepage');
		}
	}
	
	/**
	 * Replacing the navigation url's in sitemap and search
	 *
	 * @param	Array	$pages
	 * @param	Int		$root
	 * @param	Bool	$isSitemap
	 * @param	String	$language
	 *
	 * @return 	array
	 */
	public function getSearchablePages(array $pages, int $root = null, bool $isSitemap = true, string $language = null): array{			
		// Assort the links
		foreach($pages as $pageIndex => $pageUrl){
			$objPage = \PageModel::findById($pageIndex);
			if(!$objPage->hide_in_onepage){
				$arrayPageUrlParts = explode('/', $pageUrl);
				$lastIndex = (count($arrayPageUrlParts) - 1);
				$urlSlug = str_replace('.html', '', $arrayPageUrlParts[$lastIndex]);
				$arrayPageUrlParts[$lastIndex] = '#' . $urlSlug;
				$pages[$pageIndex] = implode('/', $arrayPageUrlParts);
			}
		}
		return $pages;
	}
}
